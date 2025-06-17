<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Project;
use App\Models\FormResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller {
    use AuthorizesRequests;
    protected $invoiceService;

    public function __construct( InvoiceService $invoiceService ) {
        $this->invoiceService = $invoiceService;
    }

    /**
    * Display a listing of the orders.
    */

    public function index( Project $project ) {
        $this->authorize( 'view', $project );

        $orders = $project->orders()->orderBy( 'created_at', 'desc' )->get();
        return view( 'orders.index', compact( 'project', 'orders' ) );
    }

    /**
    * Display the specified order.
    */

    public function show( Project $project, Order $order ) {
        $this->authorize( 'view', $project );

        $order->load( [ 'items', 'payments', 'invoices' ] );
        $paymentMethods = $project->paymentMethods()->where( 'is_active', true )->get();

        return view( 'orders.show', compact( 'project', 'order', 'paymentMethods' ) );
    }

    /**
    * Update the specified order in storage.
    */

    public function update( Request $request, Project $project, Order $order ) {
        $this->authorize( 'update', $project );

        $validated = $request->validate( [
            'status' => 'sometimes|required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string',
        ] );

        $order->update( $validated );

        return redirect()->route( 'projects.orders.show', [ $project, $order ] )
        ->with( 'success', 'Order updated successfully.' );
    }

    /**
    * Record a payment for the order.
    */

    public function recordPayment( Request $request, Project $project, Order $order ) {
        $this->authorize( 'update', $project );

        $validated = $request->validate( [
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:full_payment,down_payment,installment',
            'proof_image' => 'nullable|image|max:2048',
            'paid_at' => 'nullable|date',
            'status' => 'required|in:pending,verified',
        ] );

        // Get payment method details
        $paymentMethod = $project->paymentMethods()->findOrFail( $validated[ 'payment_method_id' ] );

        // Handle image upload
        if ( $request->hasFile( 'proof_image' ) ) {
            $proofPath = $request->file( 'proof_image' )->store( 'payments', 'public' );
            $validated[ 'proof_image' ] = $proofPath;
        }

        // Set payment method name
        $validated[ 'payment_method_name' ] = $paymentMethod->name;

        // Set verification timestamp if verified
        if ( $validated[ 'status' ] === 'verified' ) {
            $validated[ 'verified_at' ] = now();
        }

        // Set paid at timestamp if not provided
        $validated[ 'paid_at' ] = $validated[ 'paid_at' ] ?? now();

        // Create payment
        $payment = $order->payments()->create( $validated );

        if ( $payment->status === 'pending' ) {
            // Notify project owner about new payment
            Notification::createNotification(
                $project->user_id,
                'New Payment Received',
                'A new payment of Rp ' . number_format( $payment->amount, 0, ',', '.' ) . " has been received for order {$order->order_number}.",
                'info',
                route( 'projects.orders.show', [ $project->id, $order->id ] )
            );
        }

        // Update order amount paid if payment is verified
        if ( $payment->status === 'verified' ) {
            $order->amount_paid = $order->payments()
            ->where( 'status', 'verified' )
            ->sum( 'amount' );

            // Update order status if fully paid
            if ( $order->isPaid() && $order->status === 'pending' ) {
                $order->status = 'processing';
            }

            $order->save();
        }

        return redirect()->route( 'projects.orders.show', [ $project, $order ] )
        ->with( 'success', 'Payment recorded successfully.' );
    }

    /**
    * Verify a pending payment.
    */

    public function verifyPayment( Project $project, Order $order, Payment $payment ) {
        $this->authorize( 'update', $project );

        if ( $payment->order_id !== $order->id ) {
            abort( 404 );
        }

        if ( $payment->status !== 'pending' ) {
            return redirect()->route( 'projects.orders.show', [ $project, $order ] )
            ->with( 'error', 'Payment is already processed.' );
        }

        // Update payment status
        $payment->update( [
            'status' => 'verified',
            'verified_at' => now(),
        ] );

        Notification::createNotification(
            $project->user_id,
            'Payment Verified',
            'A payment of Rp ' . number_format( $payment->amount, 0, ',', '.' ) . " for order {$order->order_number} has been verified.",
            'success',
            route( 'projects.orders.show', [ $project->id, $order->id ] )
        );

        // Update order amount paid
        $order->amount_paid = $order->payments()
        ->where( 'status', 'verified' )
        ->sum( 'amount' );

        // Update order status if fully paid
        if ( $order->isPaid() && $order->status === 'pending' ) {
            $order->status = 'processing';
        }

        $order->save();

        // Update invoice status if exists
        foreach ( $order->invoices as $invoice ) {
            if ( $order->isPaid() && $invoice->status === 'issued' ) {
                $this->invoiceService->markInvoiceAsPaid( $invoice );
            }
        }

        return redirect()->route( 'projects.orders.show', [ $project, $order ] )
        ->with( 'success', 'Payment verified successfully.' );
    }

    /**
    * Reject a pending payment.
    */

    public function rejectPayment( Project $project, Order $order, Payment $payment ) {
        $this->authorize( 'update', $project );

        if ( $payment->order_id !== $order->id ) {
            abort( 404 );
        }

        if ( $payment->status !== 'pending' ) {
            return redirect()->route( 'projects.orders.show', [ $project, $order ] )
            ->with( 'error', 'Payment is already processed.' );
        }

        // Update payment status
        $payment->update( [
            'status' => 'rejected',
        ] );

        return redirect()->route( 'projects.orders.show', [ $project, $order ] )
        ->with( 'success', 'Payment rejected.' );
    }

    /**
    * Delete a payment.
    */

    public function deletePayment( Project $project, Order $order, Payment $payment ) {
        $this->authorize( 'update', $project );

        if ( $payment->order_id !== $order->id ) {
            abort( 404 );
        }

        // Delete proof image if exists
        if ( $payment->proof_image && Storage::disk( 'public' )->exists( $payment->proof_image ) ) {
            Storage::disk( 'public' )->delete( $payment->proof_image );
        }

        // Delete payment
        $payment->delete();

        // Update order amount paid
        $order->amount_paid = $order->payments()
        ->where( 'status', 'verified' )
        ->sum( 'amount' );
        $order->save();

        return redirect()->route( 'projects.orders.show', [ $project, $order ] )
        ->with( 'success', 'Payment deleted successfully.' );
    }

    /**
    * Show the form for creating a new order from a form response.
    */

    public function createFromResponse( Project $project, FormResponse $formResponse ) {
        $this->authorize( 'view', $project );

        if ( $formResponse->form->project_id !== $project->id ) {
            abort( 404 );
        }

        return view( 'orders.create-from-response', compact( 'project', 'formResponse' ) );
    }
}