<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',  // Make sure this is nullable in the migration
        'product_name',
        'variant_details',
        'price',
        'quantity',
        'subtotal'
    ];

    protected $casts = [
        'variant_details' => 'array',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'product_variant_id' => 'integer',  // Add this to cast as integer, but will cast null as null
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}