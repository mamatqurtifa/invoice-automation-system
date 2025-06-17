<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display financial reports for a project
     */
    public function financialReport(Request $request, Project $project)
    {
        $this->authorize('view', $project);
        
        $period = $request->input('period', 'monthly');
        $startDate = null;
        $endDate = null;
        
        // Parse custom date range if provided
        if ($period === 'custom' && $request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            // Set date range based on selected period
            switch ($period) {
                case 'weekly':
                    $startDate = Carbon::now()->subWeek()->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
                case 'monthly':
                    $startDate = Carbon::now()->subMonth()->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
                case 'quarterly':
                    $startDate = Carbon::now()->subMonths(3)->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
                case 'yearly':
                    $startDate = Carbon::now()->subYear()->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
                case 'all_time':
                    $startDate = Carbon::parse('2000-01-01')->startOfDay(); // Very early date
                    $endDate = Carbon::now()->endOfDay();
                    break;
                default:
                    $startDate = Carbon::now()->subMonth()->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
            }
        }
        
        // Get orders within date range
        $orders = Order::where('project_id', $project->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get payments within date range
        $payments = Payment::whereIn('order_id', $orders->pluck('id'))
            ->where('status', 'verified')
            ->whereBetween('verified_at', [$startDate, $endDate])
            ->orderBy('verified_at', 'desc')
            ->get();
            
        // Calculate summary
        $totalOrders = $orders->count();
        $totalSales = $orders->sum('total_amount');
        $totalRevenue = $payments->sum('amount');
        $pendingRevenue = $totalSales - $totalRevenue;
        
        // Group by status
        $ordersByStatus = $orders->groupBy('status');
        
        // Prepare data for chart (payments by date)
        $paymentsByDate = $payments->groupBy(function($payment) {
            return Carbon::parse($payment->verified_at)->format('Y-m-d');
        });
        
        $chartData = [];
        $dateRange = [];
        
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->format('Y-m-d');
            $dateRange[] = $currentDate->format('d M Y');
            $chartData[] = $paymentsByDate->has($formattedDate) 
                ? $paymentsByDate[$formattedDate]->sum('amount') 
                : 0;
            $currentDate->addDay();
        }
        
        return view('reports.financial', compact(
            'project', 
            'period', 
            'startDate', 
            'endDate', 
            'orders', 
            'payments', 
            'totalOrders', 
            'totalSales', 
            'totalRevenue', 
            'pendingRevenue',
            'ordersByStatus',
            'dateRange',
            'chartData'
        ));
    }
    
    /**
     * Export order data as CSV
     */
    public function exportOrdersCSV(Request $request, Project $project)
    {
        $this->authorize('view', $project);
        
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
        
        // Get orders with optional date filter
        $query = Order::with(['items', 'payments'])
            ->where('project_id', $project->id);
            
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $orders = $query->get();
        
        // Prepare CSV data
        $headers = [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Date',
            'Total Amount',
            'Amount Paid',
            'Status',
            'Payment Status',
            'Order Items'
        ];
        
        $data = [];
        
        foreach ($orders as $order) {
            $items = [];
            foreach ($order->items as $item) {
                $variant = '';
                if ($item->variant_details) {
                    $variantParts = [];
                    foreach ($item->variant_details as $attribute => $value) {
                        $variantParts[] = "$attribute: $value";
                    }
                    $variant = '(' . implode(', ', $variantParts) . ')';
                }
                $items[] = "{$item->quantity}x {$item->product_name} {$variant}";
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
                $order->isPaid() ? 'Fully Paid' : 'Partially Paid (' . number_format($order->getPaymentPercentage(), 1) . '%)',
                implode('; ', $items)
            ];
        }
        
        // Generate CSV
        $filename = 'orders_export_' . date('Ymd_His') . '.csv';
        
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    /**
     * Export payment data as CSV
     */
    public function exportPaymentsCSV(Request $request, Project $project)
    {
        $this->authorize('view', $project);
        
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
        
        // Get verified payments with optional date filter
        $query = Payment::whereHas('order', function($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->where('status', 'verified');
            
        if ($startDate && $endDate) {
            $query->whereBetween('verified_at', [$startDate, $endDate]);
        }
        
        $payments = $query->with('order')->get();
        
        // Prepare CSV data
        $headers = [
            'Payment ID',
            'Order Number',
            'Customer Name',
            'Payment Method',
            'Amount',
            'Payment Type',
            'Payment Date',
            'Verification Date'
        ];
        
        $data = [];
        
        foreach ($payments as $payment) {
            $data[] = [
                $payment->id,
                $payment->order->order_number,
                $payment->order->guest_name,
                $payment->payment_method_name,
                $payment->amount,
                ucfirst($payment->type),
                $payment->paid_at ? $payment->paid_at->format('Y-m-d') : 'N/A',
                $payment->verified_at ? $payment->verified_at->format('Y-m-d H:i:s') : 'N/A'
            ];
        }
        
        // Generate CSV
        $filename = 'payments_export_' . date('Ymd_His') . '.csv';
        
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}