<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_values',
        'price_adjustment',
        'stock',
        'sku',
    ];

    protected $casts = [
        'attribute_values' => 'array',
        'price_adjustment' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function getPrice()
    {
        return $this->product->base_price + $this->price_adjustment;
    }
    
    public function isInStock()
    {
        if (!$this->product->track_inventory) {
            return true;
        }
        
        return $this->stock > 0;
    }
    
    public function getVariantName()
    {
        $parts = [];
        foreach ($this->attribute_values as $name => $value) {
            $parts[] = "$name: $value";
        }
        
        return implode(', ', $parts);
    }
}