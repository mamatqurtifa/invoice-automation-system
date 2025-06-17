<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'base_price',
        'image',
        'has_variants',
        'track_inventory',
        'stock',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'has_variants' => 'boolean',
        'track_inventory' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    
    public function getPrice()
    {
        return $this->base_price;
    }
    
    public function isInStock()
    {
        if (!$this->track_inventory) {
            return true;
        }
        
        if ($this->has_variants) {
            return $this->variants()->where('stock', '>', 0)->exists();
        }
        
        return $this->stock > 0;
    }
    
    public function getStockCount()
    {
        if (!$this->track_inventory) {
            return null;
        }
        
        if ($this->has_variants) {
            return $this->variants->sum('stock');
        }
        
        return $this->stock;
    }
}