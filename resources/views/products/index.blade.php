<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products for {{ $project->name }}
            </h2>
            <a href="{{ route('projects.products.create', $project) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Create New Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Project
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($products) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                    <div class="h-48 bg-gray-200 relative">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full bg-gray-100">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        @if($product->has_variants)
                                            <span class="absolute top-2 right-2 px-2 py-1 bg-indigo-500 text-white text-xs rounded-md">
                                                Has Variants
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-gray-500 text-sm truncate">{{ $product->description ?? 'No description' }}</p>
                                        
                                        <div class="mt-2">
                                            <span class="text-gray-900 font-bold">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                                            
                                            @if($product->track_inventory)
                                                @if($product->isInStock())
                                                    <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                        In Stock: {{ $product->getStockCount() ?? 'Available' }}
                                                    </span>
                                                @else
                                                    <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                                        Out of Stock
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        <div class="mt-4 flex justify-between">
                                            <a href="{{ route('projects.products.show', [$project, $product]) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                View Details
                                            </a>
                                            
                                            <div>
                                                <a href="{{ route('projects.products.edit', [$project, $product]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mr-3">
                                                    Edit
                                                </a>
                                                <form action="{{ route('projects.products.destroy', [$project, $product]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No products found for this project. Click "Create New Product" to add one.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>