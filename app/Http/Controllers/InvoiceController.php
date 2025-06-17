<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceController extends Controller {
    use AuthorizesRequests;

    protected $invoiceService;

    public function __construct( InvoiceService $invoiceService ) {
        $this->invoiceService = $invoiceService;
    }

    /**
    * Generate a new invoice for an order.
    */

    public function generate( Request $request, Project $project, Order $order ) {
        $this->authorize( 'update', $project );

        $validated = $request->validate( [
            'type' => 'required|in:proforma,tax,commercial,receipt',
        ] );

        $invoice = $this->invoiceService->generateInvoice( $order, $validated[ 'type' ] );

        Notification::createNotification(
            $project->user_id,
            'New Invoice Generated',
            "Invoice {$invoice->invoice_number} has been generated for order {$order->order_number}.",
            'info',
            route( 'projects.invoices.show', [ $project->id, $invoice->id ] )
        );

        return redirect()->route( 'projects.orders.show', [ $project, $order ] )
        ->with( 'success', 'Invoice generated successfully.' );
    }

    /**
    * Display invoice details.
    */

    public function show( Project $project, Invoice $invoice ) {
        $this->authorize( 'view', $project );

        $order = $invoice->order;

        if ( $order->project_id !== $project->id ) {
            abort( 404 );
        }

        return view( 'invoices.show', compact( 'project', 'invoice', 'order' ) );
    }

    /**
    * Download invoice PDF.
    */

    public function download( Project $project, Invoice $invoice ) {
        $this->authorize( 'view', $project );

        if ( $invoice->order->project_id !== $project->id ) {
            abort( 404 );
        }

        if ( !$invoice->pdf_path || !Storage::disk( 'public' )->exists( $invoice->pdf_path ) ) {
            // Generate PDF if not exists
            $this->invoiceService->generatePDF( $invoice );
        }

        return response()->download(Storage::disk( 'public' )->path( $invoice->pdf_path ), $invoice->invoice_number . '.pdf' );
    }

    /**
    * Regenerate invoice PDF.
    */

    public function regenerate( Project $project, Invoice $invoice ) {
        $this->authorize( 'update', $project );

        if ( $invoice->order->project_id !== $project->id ) {
            abort( 404 );
        }

        // Delete old PDF if exists
        if ( $invoice->pdf_path && Storage::disk( 'public' )->exists( $invoice->pdf_path ) ) {
            Storage::disk( 'public' )->delete( $invoice->pdf_path );
        }

        // Generate new PDF
        $pdfPath = $this->invoiceService->generatePDF( $invoice );

        // Update invoice with new PDF path
        $invoice->update( [
            'pdf_path' => $pdfPath,
        ] );

        return redirect()->route( 'projects.invoices.show', [ $project, $invoice ] )
        ->with( 'success', 'Invoice regenerated successfully.' );
    }

    /**
    * Display public invoice page for guests.
    */

    public function publicView( Invoice $invoice ) {
        $order = $invoice->order;

        return view( 'invoices.public', compact( 'invoice', 'order' ) );
    }

    /**
    * Generate WhatsApp share link for an invoice.
    */

    public function shareWhatsApp( Project $project, Invoice $invoice ) {
        $this->authorize( 'view', $project );

        if ( $invoice->order->project_id !== $project->id ) {
            abort( 404 );
        }

        $whatsappLink = $this->invoiceService->generateWhatsAppShareLink( $invoice );

        if ( !$whatsappLink ) {
            return redirect()->route( 'projects.invoices.show', [ $project, $invoice ] )
            ->with( 'error', 'Cannot generate WhatsApp link. Customer phone number is missing.' );
        }

        return redirect( $whatsappLink );
    }
}