<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoice(Order $order, $type = 'commercial')
    {
        // Generate invoice number
        $invoiceNumber = Invoice::generateInvoiceNumber($type);
        
        // Create invoice record
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'type' => $type,
            'status' => $order->isPaid() ? 'paid' : 'issued',
            'due_date' => now()->addDays(7), // Default due date 7 days from now
        ]);
        
        // Generate PDF
        $pdfPath = $this->generatePDF($invoice);
        
        // Update invoice with PDF path
        $invoice->update([
            'pdf_path' => $pdfPath,
        ]);
        
        return $invoice;
    }
    
    public function generatePDF(Invoice $invoice)
    {
        // Load order and related data
        $order = $invoice->order->load(['items', 'payments', 'project']);
        
        // Generate PDF using DomPDF
        $pdf = PDF::loadView('invoices.template', [
            'invoice' => $invoice,
            'order' => $order,
            'project' => $order->project,
        ]);
        
        // Save PDF to storage
        $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());
        
        return $filename;
    }
    
    public function markInvoiceAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
        ]);
        
        return $invoice;
    }
    
    public function generateWhatsAppShareLink(Invoice $invoice)
    {
        $order = $invoice->order;
        $phone = preg_replace('/[^0-9]/', '', $order->guest_phone);
        
        if (empty($phone)) {
            return null;
        }
        
        $message = "Halo {$order->guest_name}!\n\n";
        $message .= "Berikut invoice untuk pesanan Anda:\n";
        $message .= "No. Invoice: {$invoice->invoice_number}\n";
        $message .= "Jumlah: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
        $message .= "Silakan klik link berikut untuk melihat dan mengunduh invoice Anda:\n";
        $message .= route('invoices.public', $invoice->id);
        
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }
}