<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductForm extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $project;
    public $product;
    public $productId;
    public $name;
    public $description;
    public $basePrice;
    public $image;
    public $existingImage;
    public $hasVariants = false;
    public $trackInventory = false;
    public $stock;
    
    // Change this property name from 'attributes' to 'productAttributes'
    public $productAttributes = [];
    public $variants = [];
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'basePrice' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
        'hasVariants' => 'boolean',
        'trackInventory' => 'boolean',
        'stock' => 'nullable|integer|min:0',
    ];
    
    public function mount($projectId, $productId = null)
    {
        $this->project = Project::findOrFail($projectId);
        $this->authorize('update', $this->project);
        
        if ($productId) {
            $this->productId = $productId;
            $this->product = Product::with(['attributes', 'variants'])->findOrFail($productId);
            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->basePrice = $this->product->base_price;
            $this->existingImage = $this->product->image;
            $this->hasVariants = $this->product->has_variants;
            $this->trackInventory = $this->product->track_inventory;
            $this->stock = $this->product->stock;
            
            // Load attributes - use productAttributes instead of attributes
            $this->productAttributes = $this->product->attributes->map(function ($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'options' => implode(', ', $attribute->options),
                    'is_required' => $attribute->is_required,
                ];
            })->toArray();
            
            // Load variants
            $this->variants = $this->product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'attribute_values' => $variant->attribute_values,
                    'price_adjustment' => $variant->price_adjustment,
                    'stock' => $variant->stock,
                    'sku' => $variant->sku,
                ];
            })->toArray();
        }
    }
    
    public function addAttribute()
    {
        $this->productAttributes[] = [
            'name' => '',
            'options' => '',
            'is_required' => false,
        ];
    }
    
    public function removeAttribute($index)
    {
        unset($this->productAttributes[$index]);
        $this->productAttributes = array_values($this->productAttributes);
    }
    
    public function generateVariants()
    {
        // First, validate that we have at least one attribute with options
        if (empty($this->productAttributes)) {
            session()->flash('error', 'You need at least one attribute with options to generate variants.');
            return;
        }
        
        foreach ($this->productAttributes as $attribute) {
            if (empty($attribute['name']) || empty($attribute['options'])) {
                session()->flash('error', 'All attributes must have a name and at least one option.');
                return;
            }
        }
        
        // Process attributes to get all options
        $processedAttributes = [];
        foreach ($this->productAttributes as $attribute) {
            $options = explode(',', $attribute['options']);
            $options = array_map('trim', $options);
            $processedAttributes[$attribute['name']] = $options;
        }
        
        // Generate all possible combinations of attributes
        $this->variants = $this->generateVariantCombinations($processedAttributes);
    }
    
    private function generateVariantCombinations($attributes, $currentCombination = [], $keys = null, $i = 0)
    {
        if (is_null($keys)) {
            $keys = array_keys($attributes);
        }
        
        if ($i >= count($keys)) {
            return [
                [
                    'attribute_values' => $currentCombination,
                    'price_adjustment' => 0,
                    'stock' => $this->trackInventory ? 0 : null,
                    'sku' => '',
                ]
            ];
        }
        
        $currentKey = $keys[$i];
        $result = [];
        
        foreach ($attributes[$currentKey] as $value) {
            $newCombination = $currentCombination;
            $newCombination[$currentKey] = $value;
            
            $variantsForThisCombination = $this->generateVariantCombinations($attributes, $newCombination, $keys, $i + 1);
            $result = array_merge($result, $variantsForThisCombination);
        }
        
        return $result;
    }
    
    public function updateVariantStock($index, $value)
    {
        $this->variants[$index]['stock'] = $value !== '' ? (int)$value : null;
    }
    
    public function updateVariantPriceAdjustment($index, $value)
    {
        $this->variants[$index]['price_adjustment'] = $value !== '' ? (float)$value : 0;
    }
    
    public function saveProduct()
    {
        $this->validate();
        
        // Additional validation for variants
        if ($this->hasVariants && empty($this->variants)) {
            $this->generateVariants();
            if (empty($this->variants)) {
                session()->flash('error', 'You must generate variants before saving the product.');
                return;
            }
        }
        
        // Validate stock if tracking inventory
        if ($this->trackInventory && !$this->hasVariants && $this->stock === null) {
            $this->validate([
                'stock' => 'required|integer|min:0',
            ]);
        }
        
        // Create or update the product
        $productData = [
            'project_id' => $this->project->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_price' => $this->basePrice,
            'has_variants' => $this->hasVariants,
            'track_inventory' => $this->trackInventory,
            'stock' => $this->hasVariants ? null : $this->stock,
        ];
        
        // Handle image upload if provided
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
            $productData['image'] = $imagePath;
        }
        
        if ($this->product) {
            // Update existing product
            $this->product->update($productData);
            $product = $this->product;
            
            // Delete existing attributes and variants
            $product->attributes()->delete();
            $product->variants()->delete();
        } else {
            // Create new product
            $product = Product::create($productData);
        }
        
        // Save attributes
        if ($this->hasVariants) {
            foreach ($this->productAttributes as $attribute) {
                $options = explode(',', $attribute['options']);
                $options = array_map('trim', $options);
                
                $product->attributes()->create([
                    'name' => $attribute['name'],
                    'options' => $options,
                    'is_required' => $attribute['is_required'] ?? false,
                ]);
            }
            
            // Save variants
            foreach ($this->variants as $variant) {
                $product->variants()->create([
                    'attribute_values' => $variant['attribute_values'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0,
                    'stock' => $this->trackInventory ? ($variant['stock'] ?? 0) : null,
                    'sku' => $variant['sku'] ?? '',
                ]);
            }
        }
        
        session()->flash('success', 'Product saved successfully!');
        return redirect()->route('projects.products.index', $this->project);
    }
    
    public function render()
    {
        return view('livewire.products.product-form');
    }
}