<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
            <a href="{{ route('projects.products.edit', [$project, $product]) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Edit Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.products.index', $project) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Products
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Product Image -->
                        <div class="bg-gray-100 p-4 rounded-lg flex items-center justify-center">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="max-w-full max-h-64 object-contain">
                            @else
                                <div class="text-center p-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No image available</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900">Product Information</h3>
                            
                            <div class="mt-4 border-t pt-4">
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                                        <dd class="mt-1 text-gray-900">{{ $product->name }}</dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Base Price</dt>
                                        <dd class="mt-1 text-gray-900 font-bold">Rp {{ number_format($product->base_price, 0, ',', '.') }}</dd>
                                    </div>
                                    
                                                                        <div class="md:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="mt-1 text-gray-900">{{ $product->description ?? 'No description available' }}</dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Product Type</dt>
                                        <dd class="mt-1 text-gray-900">{{ $product->has_variants ? 'Product with variants' : 'Simple product' }}</dd>
                                    </div>
                                    
                                    @if($product->track_inventory)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Inventory Status</dt>
                                            <dd class="mt-1">
                                                @if($product->isInStock())
                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                        In Stock
                                                        @if($product->has_variants)
                                                            (Total: {{ $product->getStockCount() ?? 'N/A' }})
                                                        @else
                                                            ({{ $product->stock }} available)
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                                        Out of Stock
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Variants -->
                    @if($product->has_variants && $product->variants->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Variants</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            @if($product->track_inventory)
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                            @endif
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($product->variants as $variant)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @foreach($variant->attribute_values as $name => $value)
                                                        <span class="inline-block bg-gray-100 rounded-md px-2 py-1 text-xs text-gray-800 mr-1 mb-1">
                                                            {{ $name }}: {{ $value }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-gray-900 font-medium">
                                                        Rp {{ number_format($variant->getPrice(), 0, ',', '.') }}
                                                    </span>
                                                    @if($variant->price_adjustment != 0)
                                                        <span class="text-xs text-gray-500 ml-1">
                                                            ({{ $variant->price_adjustment > 0 ? '+' : '' }}{{ number_format($variant->price_adjustment, 0, ',', '.') }})
                                                        </span>
                                                    @endif
                                                </td>
                                                @if($product->track_inventory)
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($variant->isInStock())
                                                            <span class="text-green-600">{{ $variant->stock }}</span>
                                                        @else
                                                            <span class="text-red-600">Out of stock</span>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                    {{ $variant->sku ?: 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>