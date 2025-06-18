<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of orders for a project.
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $orders = $project->orders()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('orders.index', compact('project', 'orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(Project $project)
    {
        $this->authorize('update', $project);
        
        $products = $project->products()->with('variants')->get();
        
        return view('orders.create', compact('project', 'products'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.variant_id' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        
        $orderItems = [];
        $totalAmount = 0;
        
        foreach ($validated['products'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->base_price;
            $variantDetails = null;
            $variantId = !empty($item['variant_id']) ? (int)$item['variant_id'] : null;
            
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $price = $variant->getPrice();
                    $variantDetails = $variant->attribute_values;
                } else {
                    // If variant not found, set to null
                    $variantId = null;
                }
            }
            
            $subtotal = $price * $item['quantity'];
            $totalAmount += $subtotal;
            
            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'product_name' => $product->name,
                'variant_details' => $variantDetails,
                'price' => $price,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];
        }
        
        $order = Order::create([
            'project_id' => $project->id,
            'order_number' => Order::generateOrderNumber(),
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'notes' => $validated['notes'],
            'total_amount' => $totalAmount,
            'amount_paid' => 0,
            'status' => 'pending',
        ]);
        
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'product_name' => $item['product_name'],
                'variant_details' => $item['variant_details'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
        }
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order.
     */
    public function show(Project $project, Order $order)
    {
        $this->authorize('view', $project);
        
        $paymentMethods = $project->paymentMethods()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('orders.show', compact('project', 'order', 'paymentMethods'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Project $project, Order $order)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        
        $order->update($validated);
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Project $project, Order $order)
    {
        $this->authorize('delete', $project);
        
        // Check if order has payments
        if ($order->payments()->count() > 0) {
            return redirect()->route('projects.orders.show', [$project, $order])
                ->with('error', 'Cannot delete order that has payments.');
        }
        
        // Check if order has invoices
        if ($order->invoices()->count() > 0) {
            return redirect()->route('projects.orders.show', [$project, $order])
                ->with('error', 'Cannot delete order that has invoices.');
        }
        
        // Delete order items first
        $order->items()->delete();
        
        // Delete the order
        $order->delete();
        
        return redirect()->route('projects.orders.index', $project)
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Record a payment for an order.
     */
    public function recordPayment(Request $request, Project $project, Order $order)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:down_payment,installment,full_payment',
            'paid_at' => 'required|date',
            'status' => 'required|in:pending,verified,rejected',
            'proof_image' => 'nullable|image|max:5120', // 5MB
        ]);
        
        $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);
        
        $payment = new Payment([
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_method_name' => $paymentMethod->name,
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'paid_at' => $validated['paid_at'],
            'status' => $validated['status'],
        ]);
        
        // If status is verified, set verified_at to now
        if ($validated['status'] === 'verified') {
            $payment->verified_at = now();
            
            // Update order amount_paid
            $order->amount_paid += $validated['amount'];
            $order->save();
        }
        
        // Handle proof image upload
        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payment-proofs', 'public');
            $payment->proof_image = $path;
        }
        
        $payment->save();
        
        // If the payment makes the order fully paid, update any pending invoices
        if ($order->isPaid() && $order->invoices()->where('status', 'pending')->count() > 0) {
            $order->invoices()->where('status', 'pending')->update(['status' => 'paid']);
        }
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Verify a pending payment.
     */
    public function verifyPayment(Project $project, Order $order, Payment $payment)
    {
        $this->authorize('update', $project);
        
        if ($payment->status !== 'pending') {
            return redirect()->route('projects.orders.show', [$project, $order])
                ->with('error', 'Only pending payments can be verified.');
        }
        
        $payment->status = 'verified';
        $payment->verified_at = now();
        $payment->save();
        
        // Update order amount_paid
        $order->amount_paid += $payment->amount;
        $order->save();
        
        // If the payment makes the order fully paid, update any pending invoices
        if ($order->isPaid() && $order->invoices()->where('status', 'pending')->count() > 0) {
            $order->invoices()->where('status', 'pending')->update(['status' => 'paid']);
        }
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Payment verified successfully.');
    }

    /**
     * Reject a pending payment.
     */
    public function rejectPayment(Project $project, Order $order, Payment $payment)
    {
        $this->authorize('update', $project);
        
        if ($payment->status !== 'pending') {
            return redirect()->route('projects.orders.show', [$project, $order])
                ->with('error', 'Only pending payments can be rejected.');
        }
        
        $payment->status = 'rejected';
        $payment->save();
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Payment rejected successfully.');
    }

    /**
     * Delete a payment.
     */
    public function deletePayment(Project $project, Order $order, Payment $payment)
    {
        $this->authorize('update', $project);
        
        if ($payment->status === 'verified') {
            return redirect()->route('projects.orders.show', [$project, $order])
                ->with('error', 'Cannot delete verified payments.');
        }
        
        // Delete proof image if exists
        if ($payment->proof_image) {
            Storage::disk('public')->delete($payment->proof_image);
        }
        
        $payment->delete();
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Generate invoice for an order.
     */
    public function generateInvoice(Request $request, Project $project, Order $order)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'type' => 'required|in:commercial,proforma,receipt',
        ]);
        
        // Generate invoice number
        $invoiceType = strtoupper(substr($validated['type'], 0, 3));
        $invoiceNumber = $invoiceType . '-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        
        // Set due_date based on invoice type
        $dueDate = null;
        if ($validated['type'] === 'commercial' || $validated['type'] === 'proforma') {
            $dueDate = now()->addDays(7);
        }
        
        // Set status based on payment status
        $status = 'pending';
        if ($order->isPaid()) {
            $status = 'paid';
        } elseif ($validated['type'] === 'receipt') {
            $status = 'paid';
        }
        
        // Create the invoice
        $invoice = Invoice::create([
            'project_id' => $project->id,
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'type' => $validated['type'],
            'amount' => $order->total_amount,
            'due_date' => $dueDate,
            'status' => $status,
        ]);
        
        // Generate and store PDF
        $pdfPath = $this->generateInvoicePDF($invoice);
        $invoice->file_path = $pdfPath;
        $invoice->save();
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Invoice generated successfully.');
    }

    /**
     * Generate PDF for the invoice
     */
    private function generateInvoicePDF(Invoice $invoice)
    {
        $order = $invoice->order;
        $project = $invoice->project;
        
        $pdf = PDF::loadView('invoices.pdf', compact('invoice', 'order', 'project'));
        
        $fileName = 'invoice_' . $invoice->invoice_number . '.pdf';
        $path = 'invoices/' . $fileName;
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }

    /**
     * Send invoice via WhatsApp.
     */
    public function shareViaWhatsApp(Project $project, Order $order, Invoice $invoice)
    {
        $this->authorize('view', $project);
        
        if (!$order->guest_phone) {
            return redirect()->route('projects.orders.show', [$project, $order])
                ->with('error', 'Order does not have a phone number.');
        }
        
        // Format phone number for WhatsApp
        $phone = preg_replace('/[^0-9]/', '', $order->guest_phone);
        
        // Add country code if needed (assuming Indonesia with +62)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        // Generate WhatsApp message
        $invoiceUrl = route('invoices.public', ['uuid' => $invoice->uuid]);
        $message = "Hello {$order->guest_name},\n\nYour invoice {$invoice->invoice_number} for order {$order->order_number} is ready.\n\nView and download your invoice: {$invoiceUrl}\n\nTotal Amount: Rp " . number_format($invoice->amount, 0, ',', '.') . "\n";
        
        if ($invoice->due_date) {
            $message .= "Due Date: " . $invoice->due_date->format('d M Y') . "\n";
        }
        
        $message .= "\nThank you for your order!";
        
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
        
        return redirect()->away($whatsappUrl);
    }

    /**
     * Create order from form response.
     */
    public function storeFromResponse(Request $request, Project $project, FormResponse $formResponse)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.variant_id' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        
        $orderItems = [];
        $totalAmount = 0;
        
        foreach ($validated['products'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->base_price;
            $variantDetails = null;
            $variantId = !empty($item['variant_id']) ? (int)$item['variant_id'] : null;
            
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $price = $variant->getPrice();
                    $variantDetails = $variant->attribute_values;
                } else {
                    // If variant not found, set to null
                    $variantId = null;
                }
            }
            
            $subtotal = $price * $item['quantity'];
            $totalAmount += $subtotal;
            
            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'product_name' => $product->name,
                'variant_details' => $variantDetails,
                'price' => $price,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];
        }
        
        $order = Order::create([
            'project_id' => $project->id,
            'form_response_id' => $formResponse->id,
            'order_number' => Order::generateOrderNumber(),
            'guest_name' => $formResponse->guest_name,
            'guest_email' => $formResponse->guest_email,
            'guest_phone' => $formResponse->guest_phone,
            'total_amount' => $totalAmount,
            'amount_paid' => 0,
            'status' => 'pending',
        ]);
        
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'product_name' => $item['product_name'],
                'variant_details' => $item['variant_details'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
        }
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Order created successfully from form response.');
    }

    /**
     * Export orders to CSV.
     */
    public function exportOrders(Project $project)
    {
        $this->authorize('view', $project);
        
        $orders = $project->orders()->with(['items', 'payments'])->get();
        
        // Prepare CSV headers
        $headers = [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Date',
            'Total Amount',
            'Amount Paid',
            'Status',
            'Items',
            'Payments'
        ];
        
        // Prepare CSV data
        $data = [];
        
        foreach ($orders as $order) {
            $items = [];
            foreach ($order->items as $item) {
                $variants = '';
                if ($item->variant_details) {
                    $variantParts = [];
                    foreach ($item->variant_details as $key => $value) {
                        $variantParts[] = "$key: $value";
                    }
                    $variants = ' (' . implode(', ', $variantParts) . ')';
                }
                $items[] = "{$item->quantity}x {$item->product_name}{$variants}";
            }
            
            $payments = [];
            foreach ($order->payments()->where('status', 'verified')->get() as $payment) {
                $payments[] = "{$payment->payment_method_name}: Rp " . number_format($payment->amount, 0, ',', '.');
            }
            
            $data[] = [
                $order->order_number,
                $order->guest_name,
                $order->guest_email,
                $order->guest_phone,
                $order->created_at->format('Y-m-d H:i:s'),
                $order->total_amount,
                $order->amount_paid,
                ucfirst($order->status),
                implode('; ', $items),
                implode('; ', $payments)
            ];
        }
        
        // Generate CSV
        $filename = 'orders_' . $project->id . '_' . date('Ymd_His') . '.csv';
        
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}