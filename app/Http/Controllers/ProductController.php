<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the products.
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $products = $project->products;
        return view('products.index', compact('project', 'products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(Project $project)
    {
        $this->authorize('update', $project);
        
        return view('products.create', compact('project'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        // Product creation is handled by Livewire component
        // This method is included for completeness but not used
        return redirect()->route('projects.products.index', $project);
    }

    /**
     * Display the specified product.
     */
    public function show(Project $project, Product $product)
    {
        $this->authorize('view', $project);
        
        return view('products.show', compact('project', 'product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Project $project, Product $product)
    {
        $this->authorize('update', $project);
        
        return view('products.edit', compact('project', 'product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Project $project, Product $product)
    {
        $this->authorize('update', $project);
        
        // Product update is handled by Livewire component
        // This method is included for completeness but not used
        return redirect()->route('projects.products.index', $project);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Project $project, Product $product)
    {
        $this->authorize('update', $project);
        
        // Delete product image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Delete the product (cascade will handle attributes and variants)
        $product->delete();
        
        return redirect()->route('projects.products.index', $project)
            ->with('success', 'Product deleted successfully');
    }
}