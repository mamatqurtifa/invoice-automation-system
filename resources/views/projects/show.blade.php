<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd" />
                        <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                    </svg>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->name }}
                </h2>
                <span class="px-3 py-1 text-xs rounded-full {{ $project->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $project->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                    </svg>
                    Edit Project
                </a>
                <a href="{{ route('projects.reports.financial', $project) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-full text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <svg class="-ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    View Reports
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.index') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back to Projects
                </a>
            </div>
            
            <!-- Project Overview Card -->
            <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl mb-6 border border-gray-100">
                <div class="p-6">
                    <h3 class="font-medium text-lg text-gray-900 mb-4 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Project Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="col-span-2 space-y-6">
                            <div class="flex flex-col space-y-1">
                                <span class="text-sm text-gray-500">Project Type</span>
                                <span class="font-medium">{{ ucfirst($project->type) }}</span>
                            </div>
                            
                            <div class="flex flex-col space-y-1">
                                <span class="text-sm text-gray-500">Description</span>
                                <p class="text-gray-700">{{ $project->description ?? 'No description provided.' }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex flex-col space-y-1">
                                <span class="text-sm text-gray-500">Start Date</span>
                                <span class="font-medium">{{ $project->start_date->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex flex-col space-y-1">
                                <span class="text-sm text-gray-500">End Date</span>
                                <span class="font-medium">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not specified' }}</span>
                            </div>
                            
                            <div class="flex flex-col space-y-1">
                                <span class="text-sm text-gray-500">Created</span>
                                <span class="font-medium">{{ $project->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 transition-all duration-300 hover:shadow-md hover:translate-y-[-2px]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Forms</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $project->forms ? $project->forms->count() : 0 }}</p>
                        </div>
                        <div class="bg-indigo-100 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 transition-all duration-300 hover:shadow-md hover:translate-y-[-2px]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Products</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $project->products ? $project->products->count() : 0 }}</p>
                        </div>
                        <div class="bg-green-100 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 transition-all duration-300 hover:shadow-md hover:translate-y-[-2px]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Orders</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $project->orders ? $project->orders->count() : 0 }}</p>
                        </div>
                        <div class="bg-yellow-100 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 transition-all duration-300 hover:shadow-md hover:translate-y-[-2px]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Payment Methods</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $project->paymentMethods ? $project->paymentMethods->count() : 0 }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Forms Section -->
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium text-lg text-gray-900 flex items-center">
                                <svg class="mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                                Forms
                            </h3>
                            <a href="{{ route('projects.forms.index', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="{{ route('projects.forms.create', $project) }}" class="flex items-center p-3 border border-dashed border-indigo-300 rounded-xl hover:bg-indigo-50 transition-all duration-200 group">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 bg-indigo-100 group-hover:bg-indigo-200 rounded-lg text-indigo-600 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-700 transition-all duration-200">Create New Form</p>
                                    <p class="text-xs text-gray-500 group-hover:text-indigo-600 transition-all duration-200">Start building a custom form</p>
                                </div>
                            </a>
                            
                            @if($project->forms && $project->forms->count() > 0)
                                @foreach($project->forms()->take(3)->get() as $form)
                                    <a href="{{ route('projects.forms.show', [$project, $form]) }}" class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-xl hover:border-indigo-200 hover:shadow-sm transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg 
                                                {{ $form->is_template ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                                @if($form->is_template)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" />
                                                        <path d="M3 8a2 2 0 012-2h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">{{ $form->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $form->responses ? $form->responses->count() : 0 }} responses</p>
                                            </div>
                                        </div>
                                        <span class="ml-2 flex-shrink-0 inline-block px-2 py-0.5 text-xs rounded-full 
                                            {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $form->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </a>
                                @endforeach
                            @else
                                <div class="text-center py-6 bg-gray-50 rounded-xl border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-900">No forms created yet</p>
                                    <p class="mt-1 text-xs text-gray-500">Create your first form to start collecting orders</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Products Section -->
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium text-lg text-gray-900 flex items-center">
                                <svg class="mr-2 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                </svg>
                                Products
                            </h3>
                            <a href="{{ route('projects.products.index', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="{{ route('projects.products.create', $project) }}" class="flex items-center p-3 border border-dashed border-green-300 rounded-xl hover:bg-green-50 transition-all duration-200 group">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 bg-green-100 group-hover:bg-green-200 rounded-lg text-green-600 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-green-700 transition-all duration-200">Add New Product</p>
                                    <p class="text-xs text-gray-500 group-hover:text-green-600 transition-all duration-200">Create product with or without variants</p>
                                </div>
                            </a>
                            
                            @if($project->products && $project->products->count() > 0)
                                @foreach($project->products()->take(3)->get() as $product)
                                    <a href="{{ route('projects.products.show', [$project, $product]) }}" class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-xl hover:border-green-200 hover:shadow-sm transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-lg overflow-hidden">
                                                @if($product->image)
                                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex items-center justify-center h-full w-full bg-gray-100 text-gray-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        @if($product->has_variants)
                                            <span class="ml-2 flex-shrink-0 inline-block px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-800">
                                                Variants
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            @else
                                <div class="text-center py-6 bg-gray-50 rounded-xl border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-900">No products added yet</p>
                                    <p class="mt-1 text-xs text-gray-500">Add products to start selling</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Payment Methods Section -->
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium text-lg text-gray-900 flex items-center">
                                <svg class="mr-2 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                </svg>
                                Payment Methods
                            </h3>
                            <a href="{{ route('projects.payment-methods.index', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="{{ route('projects.payment-methods.create', $project) }}" class="flex items-center p-3 border border-dashed border-blue-300 rounded-xl hover:bg-blue-50 transition-all duration-200 group">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg text-blue-600 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-blue-700 transition-all duration-200">Add Payment Method</p>
                                    <p class="text-xs text-gray-500 group-hover:text-blue-600 transition-all duration-200">Set up payment options for customers</p>
                                </div>
                            </a>
                            
                            @if($project->paymentMethods && $project->paymentMethods->count() > 0)
                                @foreach($project->paymentMethods()->take(3)->get() as $paymentMethod)
                                    <a href="{{ route('projects.payment-methods.edit', [$project, $paymentMethod]) }}" class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-xl hover:border-blue-200 hover:shadow-sm transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg 
                                                @switch($paymentMethod->type)
                                                    @case('bank_transfer')
                                                        bg-blue-100 text-blue-600
                                                        @break
                                                    @case('e_wallet')
                                                        bg-purple-100 text-purple-600
                                                        @break
                                                    @case('cash')
                                                        bg-green-100 text-green-600
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-600
                                                @endswitch
                                            ">
                                                @switch($paymentMethod->type)
                                                    @case('bank_transfer')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                                        </svg>
                                                        @break
                                                    @case('e_wallet')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M13 7H7v6h6V7z" />
                                                            <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd" />
                                                        </svg>
                                                        @break
                                                    @case('cash')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                        </svg>
                                                        @break
                                                    @default
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                                        </svg>
                                                @endswitch
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">{{ $paymentMethod->name }}</p>
                                                <p class="text-xs text-gray-500">
                                                    @switch($paymentMethod->type)
                                                        @case('bank_transfer')
                                                            Bank Transfer
                                                            @break
                                                        @case('e_wallet')
                                                            E-Wallet
                                                            @break
                                                        @case('cash')
                                                            Cash
                                                            @break
                                                        @default
                                                            Other
                                                    @endswitch
                                                </p>
                                            </div>
                                        </div>
                                        <span class="ml-2 flex-shrink-0 inline-block px-2 py-0.5 text-xs rounded-full 
                                            {{ $paymentMethod->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </a>
                                @endforeach
                            @else
                                <div class="text-center py-6 bg-gray-50 rounded-xl border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-900">No payment methods added</p>
                                    <p class="mt-1 text-xs text-gray-500">Add payment methods to accept payments</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Orders Section -->
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 transition-all duration-300 hover:shadow-md lg:col-span-3">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium text-lg text-gray-900 flex items-center">
                                <svg class="mr-2 h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Recent Orders
                            </h3>
                            <a href="{{ route('projects.orders.index', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                        </div>
                        
                        @if($project->orders && $project->orders->count() > 0)
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($project->orders()->latest()->take(5)->get() as $order)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <a href="{{ route('projects.orders.show', [$project, $order]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                        {{ $order->order_number }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <div class="text-sm text-gray-900">{{ $order->guest_name }}</div>
                                                    @if($order->guest_phone)
                                                        <div class="text-xs text-gray-500">{{ $order->guest_phone }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $order->created_at->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        @if($order->isPaid())
                                                            <span class="text-green-600">Paid in full</span>
                                                        @else
                                                            <span class="text-yellow-600">{{ number_format($order->getPaymentPercentage(), 1) }}% paid</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                           'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-900">No orders received yet</p>
                                <p class="mt-1 text-xs text-gray-500">Orders will appear here when customers make purchases</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional JavaScript for interactivity here
        });
    </script>
</x-app-layout>