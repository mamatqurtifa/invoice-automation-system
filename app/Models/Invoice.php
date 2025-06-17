<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'type',
        'status',
        'due_date',
        'pdf_path',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }
    
    public static function generateInvoiceNumber($type = 'commercial')
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}-{$date}%")->orderBy('invoice_number', 'desc')->first();
        
        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "{$prefix}-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}