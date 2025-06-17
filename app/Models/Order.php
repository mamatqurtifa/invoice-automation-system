<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'form_response_id',
        'order_number',
        'guest_name',
        'guest_email',
        'guest_phone',
        'total_amount',
        'amount_paid',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function formResponse()
    {
        return $this->belongsTo(FormResponse::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function isPaid()
    {
        return $this->amount_paid >= $this->total_amount;
    }

    public function getRemainingAmount()
    {
        return max(0, $this->total_amount - $this->amount_paid);
    }

    public function getPaymentPercentage()
    {
        if ($this->total_amount <= 0) {
            return 100;
        }
        
        return min(100, ($this->amount_paid / $this->total_amount) * 100);
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = self::where('order_number', 'like', "{$prefix}-{$date}%")->orderBy('order_number', 'desc')->first();
        
        if ($lastOrder) {
            $lastNumber = (int)substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "{$prefix}-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}