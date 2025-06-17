<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class WhatsAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('whatsapp', function ($app) {
            return new WhatsAppService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

class WhatsAppService
{
    /**
     * Generate a WhatsApp share URL
     *
     * @param string $phoneNumber Phone number in international format without '+' or '00'
     * @param string $message Message to send
     * @return string
     */
    public function generateShareUrl($phoneNumber, $message = '')
    {
        // Clean phone number
        $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
        
        // Encode message
        $encodedMessage = urlencode($message);
        
        // Generate URL
        return "https://wa.me/{$phoneNumber}?text={$encodedMessage}";
    }
    
    /**
     * Generate an invoice share message
     *
     * @param \App\Models\Invoice $invoice
     * @return string
     */
    public function generateInvoiceMessage($invoice)
    {
        $order = $invoice->order;
        $message = "Halo {$order->guest_name}!\n\n";
        $message .= "Berikut invoice untuk pesanan Anda:\n";
        $message .= "No. Invoice: {$invoice->invoice_number}\n";
        $message .= "Jumlah: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
        
        if ($order->getRemainingAmount() > 0) {
            $message .= "Jumlah yang perlu dibayar: Rp " . number_format($order->getRemainingAmount(), 0, ',', '.') . "\n\n";
        }
        
        $message .= "Silakan klik link berikut untuk melihat dan mengunduh invoice Anda:\n";
        $message .= route('invoices.public', $invoice->id);
        
        return $message;
    }
    
    /**
     * Generate a payment receipt message
     *
     * @param \App\Models\Payment $payment
     * @return string
     */
    public function generatePaymentReceiptMessage($payment)
    {
        $order = $payment->order;
        $message = "Halo {$order->guest_name}!\n\n";
        $message .= "Pembayaran Anda sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " telah kami terima dan verifikasi.\n\n";
        
        if ($order->getRemainingAmount() > 0) {
            $message .= "Sisa pembayaran yang perlu dilunasi: Rp " . number_format($order->getRemainingAmount(), 0, ',', '.') . "\n\n";
        } else {
            $message .= "Pesanan Anda telah lunas. Terima kasih!\n\n";
        }
        
        if ($order->invoices->count() > 0) {
            $latestInvoice = $order->invoices->last();
            $message .= "Detail pesanan dapat dilihat pada invoice:\n";
            $message .= route('invoices.public', $latestInvoice->id);
        }
        
        return $message;
    }
    
    /**
     * Clean phone number to ensure correct format
     *
     * @param string $phoneNumber
     * @return string
     */
    private function cleanPhoneNumber($phoneNumber)
    {
        // Remove any non-digit characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Remove leading zeros
        $phoneNumber = ltrim($phoneNumber, '0');
        
        // Add country code if not present (assuming Indonesia +62)
        if (!Str::startsWith($phoneNumber, '62')) {
            $phoneNumber = '62' . $phoneNumber;
        }
        
        return $phoneNumber;
    }
}