<!-- resources/views/livewire/form-builder/form-editor.blade.php -->
<div>
    <div class="mb-6">
        <h2 class="text-lg font-semibold">{{ isset($form) && !isset($templateId) ? 'Edit Form' : 'Create New Form' }}</h2>

        @if(session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                {{ session('message') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Properties Panel -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-medium mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                </svg>
                Form Properties
            </h3>

            <div class="mb-4">
                <label for="formName" class="block text-sm font-medium text-gray-700">Form Name</label>
                <input type="text" id="formName" wire:model="formName" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('formName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="formDescription" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="formDescription" wire:model="formDescription" rows="3" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                @error('formDescription') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Closing Date & Time</label>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <input type="date" wire:model="closingDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('closingDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="time" wire:model="closingTime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('closingTime') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Leave empty for no closing date</p>
            </div>
            
            <div class="mb-4 space-y-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Active Form</span>
                </label>
                <p class="text-xs text-gray-500">Inactive forms cannot be accessed by customers</p>
                
                <label class="inline-flex items-center mt-2">
                    <input type="checkbox" wire:model="isTemplate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Save as Template</span>
                </label>
                <p class="text-xs text-gray-500">Templates can be reused for creating new forms.</p>
            </div>

            <!-- Pages Manager -->
            <div class="mt-6">
                <h4 class="font-medium text-sm text-gray-700 mb-2">Form Pages</h4>
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($pages as $page)
                        <button 
                            type="button" 
                            wire:click="switchToPage({{ $page }})" 
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $currentPage == $page ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-700' }} hover:bg-indigo-50"
                        >
                            Page {{ $page }}
                            @if(count($pages) > 1)
                                <button wire:click.stop="removePage({{ $page }})" class="ml-1 text-gray-400 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </button>
                    @endforeach
                    
                    <button 
                        type="button" 
                        wire:click="addPage" 
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-50 text-indigo-600 hover:bg-indigo-100"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Page
                    </button>
                </div>
                <p class="text-xs text-gray-500">Multiple pages allow organizing form content into steps</p>
            </div>
        </div>

        <!-- Component Palette -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-medium mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Add Components
            </h3>
            
            <!-- Component categories -->
            <div class="mb-4">
                <h4 class="font-medium text-sm text-gray-700 mb-2">Basic Fields</h4>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="addComponent('text')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v1h3a1 1 0 011 1v3a1 1 0 01-1 1H6a1 1 0 00-1 1v7a1 1 0 11-2 0V9a1 1 0 011-1h3a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 110-2z" clip-rule="evenodd" />
                        </svg>
                        Text
                    </button>
                    <button type="button" wire:click="addComponent('email')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        Email
                    </button>
                    <button type="button" wire:click="addComponent('phone')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        Phone
                    </button>
                    <button type="button" wire:click="addComponent('textarea')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v1h3a1 1 0 011 1v3a1 1 0 01-1 1H6a1 1 0 00-1 1v7a1 1 0 11-2 0V9a1 1 0 011-1h3a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 110-2z" clip-rule="evenodd" />
                        </svg>
                        Textarea
                    </button>
                    <button type="button" wire:click="addComponent('number')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v1a1 1 0 102 0v-1zm4-4a1 1 0 011 1v5a1 1 0 11-2 0V9a1 1 0 011-1zm4 4a1 1 0 10-2 0v1a1 1 0 102 0v-1z" clip-rule="evenodd" />
                        </svg>
                        Number
                    </button>
                    <button type="button" wire:click="addComponent('date')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        Date
                    </button>
                </div>
            </div>
            
            <div class="mb-4">
                <h4 class="font-medium text-sm text-gray-700 mb-2">Choice Fields</h4>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="addComponent('select')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Dropdown
                    </button>
                    <button type="button" wire:click="addComponent('radio')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12zm0-8a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd" />
                        </svg>
                        Radio
                    </button>
                    <button type="button" wire:click="addComponent('checkbox')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Checkbox
                    </button>
                </div>
            </div>
            
            <div class="mb-4">
                <h4 class="font-medium text-sm text-gray-700 mb-2">Content Elements</h4>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="addComponent('heading')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        Heading
                    </button>
                    <button type="button" wire:click="addComponent('paragraph')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        Paragraph
                    </button>
                    <button type="button" wire:click="addComponent('image')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                        Image
                    </button>
                </div>
            </div>
            
            <div class="mb-4">
                <h4 class="font-medium text-sm text-gray-700 mb-2">Special Components</h4>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="addComponent('product')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        Product
                    </button>
                    <button type="button" wire:click="addComponent('page_break')"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 rounded-lg text-sm transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd" />
                        </svg>
                        Page Break
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Preview -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-medium mb-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    Page {{ $currentPage }} Preview
                </h3>
                <div class="text-sm text-gray-500">
                    Current page components: {{ count($componentsForCurrentPage) }}
                </div>
            </div>
            
            <div class="p-6 max-h-[600px] overflow-y-auto">
                <div class="text-sm text-gray-700 mb-4">
                    <strong>{{ $formName ?: 'Untitled Form' }}</strong>
                    @if($formDescription)
                        <p class="text-gray-500 mt-1">{{ $formDescription }}</p>
                    @endif
                    
                    @if($closingDate)
                        <div class="mt-2 text-xs bg-yellow-50 text-yellow-800 p-2 rounded-md inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Form closes on: {{ $closingDate }} {{ $closingTime ?? '23:59' }}
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    @forelse($componentsForCurrentPage as $index => $component)
                        @php
                            // Find the actual index in the full components array
                            $actualIndex = array_search($component, $this->components);
                        @endphp
                        <div class="border border-gray-200 rounded-md p-3 {{ $editingComponent === $actualIndex ? 'ring-2 ring-indigo-500' : '' }}">
                            <!-- Component Header -->
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">{{ $component['label'] }}</span>
                                <div class="space-x-1">
                                    <button type="button" wire:click="moveComponentUp({{ $actualIndex }})" 
                                        class="text-gray-400 hover:text-gray-600" title="Move Up">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="moveComponentDown({{ $actualIndex }})" 
                                        class="text-gray-400 hover:text-gray-600" title="Move Down">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="editComponent({{ $actualIndex }})" 
                                        class="text-blue-400 hover:text-blue-600" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="removeComponent({{ $actualIndex }})" 
                                        class="text-red-400 hover:text-red-600" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            @if($editingComponent === $actualIndex)
                                <!-- Component Editing Mode -->
                                <div class="bg-gray-50 p-3 rounded mt-2">
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-700">Label</label>
                                        <input type="text" wire:model="components.{{ $actualIndex }}.label" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    
                                    @if($component['type'] !== 'heading' && $component['type'] !== 'paragraph' && $component['type'] !== 'image' && $component['type'] !== 'page_break')
                                        <div class="mb-3">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="components.{{ $actualIndex }}.required" 
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-xs text-gray-700">Required</span>
                                            </label>
                                        </div>
                                    @endif

                                    @if(in_array($component['type'], ['select', 'radio', 'checkbox']))
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-gray-700">Options (one per line)</label>
                                            <textarea wire:model="components.{{ $actualIndex }}.properties.options" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                rows="3"></textarea>
                                        </div>
                                    @endif

                                    @if($component['type'] === 'image')
                                        <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Image URL</label>
                                                <input type="text" wire:model="components.{{ $actualIndex }}.properties.url" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Alignment</label>
                                                <select wire:model="components.{{ $actualIndex }}.properties.alignment" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <option value="left">Left</option>
                                                    <option value="center">Center</option>
                                                    <option value="right">Right</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-gray-700">Upload New Image</label>
                                            <div class="mt-1 flex items-center">
                                                <input type="file" wire:model="uploadedImage" 
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                <button type="button" wire:click="uploadImageForComponent({{ $actualIndex }})" 
                                                    class="ml-2 px-3 py-1.5 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    {{ $uploadedImage ? '' : 'disabled' }}>
                                                    Upload
                                                </button>
                                            </div>
                                            @error('uploadedImage')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif

                                    @if($component['type'] === 'heading')
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-gray-700">Heading Level</label>
                                            <select wire:model="components.{{ $actualIndex }}.properties.level" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="h1">Heading 1 (Largest)</option>
                                                <option value="h2">Heading 2</option>
                                                <option value="h3">Heading 3</option>
                                                <option value="h4">Heading 4 (Smallest)</option>
                                            </select>
                                        </div>
                                    @endif
                                    
                                    @if($component['type'] === 'product')
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-gray-700">Select Products</label>
                                            <div class="mt-1 max-h-32 overflow-y-auto border rounded-md p-2">
                                                @foreach($availableProducts as $product)
                                                    <label class="flex items-center py-1 border-b last:border-b-0">
                                                        <input type="checkbox" wire:model="components.{{ $actualIndex }}.properties.product_ids" 
                                                            value="{{ $product->id }}"
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-xs text-gray-700">{{ $product->name }} 
                                                            <span class="text-gray-500">({{ $product->has_variants ? 'With variants' : 'Simple product' }})</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                                
                                                @if(count($availableProducts) === 0)
                                                    <p class="text-xs text-gray-500 py-1">No products available. <a href="{{ route('projects.products.create', $project) }}" class="text-indigo-600 hover:underline" target="_blank">Create a product</a> first.</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="components.{{ $actualIndex }}.properties.show_images" 
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-xs text-gray-700">Show Product Images</span>
                                            </label>
                                            
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="components.{{ $actualIndex }}.properties.show_variants" 
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-xs text-gray-700">Show Product Variants</span>
                                            </label>
                                            
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="components.{{ $actualIndex }}.properties.show_prices" 
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-xs text-gray-700">Show Product Prices</span>
                                            </label>
                                            
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="components.{{ $actualIndex }}.properties.allow_quantity" 
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-xs text-gray-700">Allow Quantity Selection</span>
                                            </label>
                                        </div>
                                    @endif
                                    
                                    @if($component['type'] === 'page_break')
                                        <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Next Button Text</label>
                                                <input type="text" wire:model="components.{{ $actualIndex }}.properties.next_button_text" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Previous Button Text</label>
                                                <input type="text" wire:model="components.{{ $actualIndex }}.properties.prev_button_text" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="text-right">
                                        <button type="button" wire:click="updateComponent" 
                                            class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Done
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Component Preview Mode -->
                                <div>
                                    @switch($component['type'])
                                        @case('text')
                                        @case('email')
                                        @case('number')
                                        @case('phone')
                                            <input type="{{ $component['type'] }}" placeholder="{{ $component['label'] }}" disabled
                                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                            @break
                                        
                                        @case('textarea')
                                            <textarea placeholder="{{ $component['label'] }}" disabled rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm"></textarea>
                                            @break
                                        
                                        @case('select')
                                            <select disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                                <option value="">-- Select --</option>
                                                @foreach($component['properties']['options'] ?? [] as $option)
                                                    <option>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @break
                                        
                                        @case('radio')
                                            <div class="space-y-1 mt-1">
                                                @foreach($component['properties']['options'] ?? [] as $option)
                                                    <div class="flex items-center">
                                                        <input type="radio" disabled class="rounded-full border-gray-300 text-indigo-600 bg-gray-100 shadow-sm">
                                                        <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break
                                        
                                        @case('checkbox')
                                            <div class="space-y-1 mt-1">
                                                @foreach($component['properties']['options'] ?? [] as $option)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" disabled class="rounded border-gray-300 text-indigo-600 bg-gray-100 shadow-sm">
                                                        <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break
                                        
                                        @case('date')
                                            <input type="date" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                            @break
                                        
                                        @case('image')
                                            <div class="mt-1 text-center">
                                                @if(isset($component['properties']['url']) && $component['properties']['url'])
                                                    <div class="flex justify-{{ $component['properties']['alignment'] ?? 'center' }}">
                                                        <img src="{{ $component['properties']['url'] }}" alt="{{ $component['label'] }}" 
                                                            class="max-h-40 object-contain" style="width: {{ $component['properties']['width'] ?? '100%' }}">
                                                    </div>
                                                @else
                                                    <div class="border-2 border-dashed border-gray-300 rounded-md p-6 flex justify-center">
                                                        <div class="text-center">
                                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <p class="mt-1 text-xs text-gray-500">Image placeholder</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @break
                                        
                                        @case('heading')
                                            @php
                                                $level = $component['properties']['level'] ?? 'h2';
                                                $headingSizes = [
                                                    'h1' => 'text-xl font-bold',
                                                    'h2' => 'text-lg font-bold',
                                                    'h3' => 'text-md font-semibold',
                                                    'h4' => 'text-sm font-semibold',
                                                ];
                                                $headingClass = $headingSizes[$level] ?? $headingSizes['h2'];
                                            @endphp
                                            <div class="{{ $headingClass }} mt-1">{{ $component['label'] }}</div>
                                            @break
                                        
                                        @case('paragraph')
                                            <p class="mt-1 text-sm text-gray-700">{{ $component['label'] }}</p>
                                            @break
                                            
                                        @case('product')
                                            <div class="mt-1 border rounded-md p-3 bg-gray-50">
                                                @if(isset($component['properties']['product_ids']) && !empty($component['properties']['product_ids']))
                                                    <div class="space-y-3">
                                                        @foreach($component['properties']['product_ids'] as $productId)
                                                            @php
                                                                $product = $availableProducts->firstWhere('id', $productId);
                                                            @endphp
                                                            @if($product)
                                                                <div class="flex items-start space-x-3 pb-2 border-b">
                                                                    @if($component['properties']['show_images'] ?? true)
                                                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                                                                            @if($product->image)
                                                                                <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                                                            @else
                                                                                <div class="flex items-center justify-center h-full">
                                                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                    </svg>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    <div class="flex-grow">
                                                                        <div class="font-medium text-sm">{{ $product->name }}</div>
                                                                        
                                                                        @if($component['properties']['show_prices'] ?? true)
                                                                            <div class="text-xs text-gray-700">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                                                                        @endif
                                                                        
                                                                        @if(($component['properties']['show_variants'] ?? true) && $product->has_variants)
                                                                            <select disabled class="mt-2 block w-full text-xs rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                                                                <option>-- Select Variant --</option>
                                                                            </select>
                                                                        @endif
                                                                        
                                                                        @if($component['properties']['allow_quantity'] ?? true)
                                                                            <div class="mt-2 flex items-center">
                                                                                <label class="text-xs text-gray-700 mr-2">Qty:</label>
                                                                                <input type="number" min="1" value="1" disabled
                                                                                    class="block w-16 text-xs rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-500 text-center py-2">No products selected</p>
                                                @endif
                                            </div>
                                            @break
                                            
                                        @case('page_break')
                                            <div class="mt-3 flex items-center justify-between">
                                                <button type="button" disabled
                                                    class="px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500 text-sm">
                                                    {{ $component['properties']['prev_button_text'] ?? 'Previous' }}
                                                </button>
                                                <button type="button" disabled
                                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm opacity-50">
                                                    {{ $component['properties']['next_button_text'] ?? 'Next' }}
                                                </button>
                                            </div>
                                            @break
                                            
                                        @default
                                            <p class="text-sm text-gray-500">Component preview not available</p>
                                    @endswitch
                                    
                                    @if($component['required'] ?? false)
                                        <div class="mt-1 text-right">
                                            <span class="text-xs text-red-500">Required</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="border-2 border-dashed border-gray-300 rounded-md p-6">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No components on page {{ $currentPage }}</h3>
                                <p class="mt-1 text-sm text-gray-500">Add components from the palette on the left.</p>
                            </div>
                        </div>
                    @endforelse
                    
                    @if(count($componentsForCurrentPage) > 0 && $currentPage == max($pages))
                        <div class="mt-6 text-center">
                            <button type="button" disabled class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm opacity-50">
                                Submit
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-100 text-right">
                <div class="inline-flex items-center mr-4">
                    <span class="text-sm text-gray-600">Pages: {{ count($pages) }} | Components: {{ count($components) }}</span>
                </div>
                <a href="{{ route('projects.forms.index', $project) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="button" wire:click="saveForm" class="ml-3 px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Form
                </button>
            </div>
        </div>
    </div>
</div>