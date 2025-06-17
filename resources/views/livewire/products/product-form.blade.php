<div>
    <form wire:submit.prevent="saveProduct">
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" id="name" wire:model="name" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" wire:model="description" rows="3"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="basePrice" class="block text-sm font-medium text-gray-700 mb-1">Base Price *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" id="basePrice" wire:model="basePrice" required min="0" step="0.01"
                            class="block w-full pl-10 pr-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    @error('basePrice') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                    <input type="file" id="image" wire:model="image"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    
                    <div class="mt-2">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                        @elseif ($existingImage)
                            <img src="{{ Storage::url($existingImage) }}" alt="{{ $name }}" class="h-20 w-20 object-cover rounded">
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col space-y-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="hasVariants"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">This product has multiple variants</span>
                    </label>
                    
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="trackInventory"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Track inventory for this product</span>
                    </label>
                </div>
                
                @if($trackInventory && !$hasVariants)
                    <div class="mt-4">
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                        <input type="number" id="stock" wire:model="stock" min="0" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>
            
            <!-- Variants Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-4">
                    {{ $hasVariants ? 'Product Variants' : 'Additional Information' }}
                </h3>
                
                @if($hasVariants)
                    <!-- Attributes -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium mb-2">Attributes</h4>
                        <p class="text-sm text-gray-500 mb-4">
                            Add attributes like size, color, etc. to generate variants.
                        </p>
                        
                        @foreach($productAttributes as $index => $attribute)
                            <div class="mb-4 p-3 border rounded-md bg-gray-50">
                                <div class="flex justify-between mb-2">
                                    <h5 class="text-sm font-medium">Attribute {{ $index + 1 }}</h5>
                                    <button type="button" wire:click="removeAttribute({{ $index }})" class="text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="mb-2">
                                    <input type="text" wire:model="productAttributes.{{ $index }}.name" placeholder="Name (e.g. Size, Color)"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                </div>
                                
                                <div class="mb-2">
                                    <textarea wire:model="productAttributes.{{ $index }}.options" placeholder="Options (comma separated, e.g. Small, Medium, Large)" rows="2"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"></textarea>
                                </div>
                                
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="productAttributes.{{ $index }}.is_required"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Required</span>
                                </label>
                            </div>
                        @endforeach
                        
                        <div class="mt-2">
                            <button type="button" wire:click="addAttribute"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Attribute
                            </button>
                            
                            @if(count($productAttributes) > 0)
                                <button type="button" wire:click="generateVariants"
                                    class="ml-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Generate Variants
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Variants -->
                    @if(count($variants) > 0)
                        <div class="mt-6">
                            <h4 class="text-md font-medium mb-2">Variants</h4>
                            <p class="text-sm text-gray-500 mb-4">
                                Manage pricing and stock for each variant.
                            </p>
                            
                            <div class="overflow-x-auto border rounded-md">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Adj.</th>
                                            @if($trackInventory)
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                            @endif
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($variants as $index => $variant)
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                    @foreach($variant['attribute_values'] as $name => $value)
                                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded mr-1">
                                                            {{ $name }}: {{ $value }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                                        </div>
                                                        <input type="number" wire:model="variants.{{ $index }}.price_adjustment" step="0.01"
                                                            class="block w-24 pl-10 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                                    </div>
                                                </td>
                                                @if($trackInventory)
                                                    <td class="px-3 py-2 whitespace-nowrap">
                                                        <input type="number" wire:model="variants.{{ $index }}.stock" min="0"
                                                            class="block w-20 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                                    </td>
                                                @endif
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <input type="text" wire:model="variants.{{ $index }}.sku" placeholder="SKU"
                                                        class="block w-24 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-gray-50 rounded-md">
                        <p class="text-gray-600">
                            This product doesn't have variants. If this product comes in different variants (like sizes or colors), check the "This product has multiple variants" box above.
                        </p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="mt-6 text-right">
            <a href="{{ route('projects.products.index', $project) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="ml-3 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ isset($product) ? 'Update Product' : 'Create Product' }}
            </button>
        </div>
    </form>
</div>