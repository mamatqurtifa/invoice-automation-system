<div>
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">Create Order from Form Response</h2>
        <p class="mt-1 text-sm text-gray-500">This will create a new order based on the form response.</p>
    </div>
    
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <form wire:submit.prevent="createOrder">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-md font-medium mb-4">Customer Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="guestName" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" id="guestName" wire:model="guestName" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('guestName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label for="guestEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="guestEmail" wire:model="guestEmail"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('guestEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label for="guestPhone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" id="guestPhone" wire:model="guestPhone"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('guestPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-md font-medium">Products</h3>
                <button type="button" wire:click="addProductRow" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
                    Add Product
                </button>
            </div>
            
            <div class="space-y-6">
                @foreach($selectedProducts as $index => $product)
                    <div class="border rounded-md p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-medium">Product {{ $index + 1 }}</h4>
                            @if(count($selectedProducts) > 1)
                                <button type="button" wire:click="removeProductRow({{ $index }})" class="text-red-500 hover:text-red-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                                <select wire:model="selectedProducts.{{ $index }}.product_id" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select Product</option>
                                    @foreach($availableProducts as $availableProduct)
                                        <option value="{{ $availableProduct->id }}">{{ $availableProduct->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedProducts.'.$index.'.product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Variant</label>
                                <select wire:model="selectedProducts.{{ $index }}.variant_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ empty($selectedProducts[$index]['product_id']) ? 'disabled' : '' }}>
                                    <option value="">Select Variant</option>
                                    @if(!empty($selectedProducts[$index]['product_id']))
                                        @php
                                            $selectedProduct = $availableProducts->firstWhere('id', $selectedProducts[$index]['product_id']);
                                        @endphp
                                        
                                        @if($selectedProduct && $selectedProduct->has_variants && $selectedProduct->variants->count() > 0)
                                            @foreach($selectedProduct->variants as $variant)
                                                <option value="{{ $variant->id }}">
                                                    @foreach($variant->attribute_values as $attribute => $value)
                                                        {{ $attribute }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                    ({{ $variant->price_adjustment != 0 ? ($variant->price_adjustment > 0 ? '+' : '') . number_format($variant->price_adjustment, 0, ',', '.') : 'No Adjustment' }})
                                                </option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                                @error('selectedProducts.'.$index.'.variant_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                <input type="number" wire:model="selectedProducts.{{ $index }}.quantity" min="1" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('selectedProducts.'.$index.'.quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                <div class="block w-full py-2 px-3 bg-gray-100 rounded-md border border-gray-200 text-gray-700">
                                    Rp {{ number_format($product['subtotal'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 text-right">
                <p class="text-lg font-bold">Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-md font-medium mb-4">Additional Information</h3>
            
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Order Notes</label>
                <textarea id="notes" wire:model="notes" rows="3"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div class="text-right">
            <a href="{{ route('projects.forms.show', [$project->id, $formResponse->form_id]) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="ml-3 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Create Order
            </button>
        </div>
    </form>
</div>