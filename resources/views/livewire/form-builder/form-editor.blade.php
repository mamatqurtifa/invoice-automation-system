<div>
    <div class="mb-6">
        <h2 class="text-lg font-semibold">{{ isset($form) ? 'Edit Form' : 'Create New Form' }}</h2>

        @if(session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Properties Panel -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium mb-4">Form Properties</h3>

            <div class="mb-4">
                <label for="formName" class="block text-sm font-medium text-gray-700">Form Name</label>
                <input type="text" id="formName" wire:model="formName" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('formName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="formDescription" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="formDescription" wire:model="formDescription" rows="3" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                @error('formDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="isTemplate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Save as Template</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Templates can be reused for creating new forms.</p>
            </div>
        </div>

        <!-- Component Palette -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium mb-4">Add Components</h3>
            <div class="grid grid-cols-2 gap-2">
                @foreach($componentTypes as $type => $label)
                    <button type="button" wire:click="addComponent('{{ $type }}')"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-4 rounded text-sm">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Form Preview -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium mb-4">Form Preview</h3>
            <div class="text-sm text-gray-700 mb-4">
                <strong>{{ $formName ?: 'Untitled Form' }}</strong>
                @if($formDescription)
                    <p class="text-gray-500 mt-1">{{ $formDescription }}</p>
                @endif
            </div>

            <div class="space-y-4">
                @foreach($components as $index => $component)
                    <div class="border border-gray-200 rounded-md p-3 {{ $editingComponent === $index ? 'ring-2 ring-indigo-500' : '' }}">
                        <!-- Component Header -->
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium">{{ $component['label'] }}</span>
                            <div class="space-x-1">
                                <button type="button" wire:click="moveComponentUp({{ $index }})" 
                                    class="text-gray-400 hover:text-gray-600" title="Move Up">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="moveComponentDown({{ $index }})" 
                                    class="text-gray-400 hover:text-gray-600" title="Move Down">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="editComponent({{ $index }})" 
                                    class="text-blue-400 hover:text-blue-600" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="removeComponent({{ $index }})" 
                                    class="text-red-400 hover:text-red-600" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if($editingComponent === $index)
                            <!-- Component Editing Mode -->
                            <div class="bg-gray-50 p-3 rounded mt-2">
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700">Label</label>
                                    <input type="text" wire:model="components.{{ $index }}.label" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="components.{{ $index }}.required" 
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-xs text-gray-700">Required</span>
                                    </label>
                                </div>

                                @if(in_array($component['type'], ['select', 'radio', 'checkbox']))
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-700">Options (one per line)</label>
                                        <textarea wire:model="components.{{ $index }}.properties.options" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            rows="3"></textarea>
                                    </div>
                                @endif

                                @if($component['type'] === 'image')
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-700">Image URL</label>
                                        <input type="text" wire:model="components.{{ $index }}.properties.url" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                @endif

                                <div class="text-right">
                                    <button type="button" wire:click="updateComponent" 
                                        class="px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                                        <div class="mt-1">
                                            @if(isset($component['properties']['url']) && $component['properties']['url'])
                                                <img src="{{ $component['properties']['url'] }}" alt="{{ $component['label'] }}" 
                                                    class="max-w-full h-auto rounded">
                                            @else
                                                <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center">
                                                    <p class="text-xs text-gray-500">Image placeholder</p>
                                                </div>
                                            @endif
                                        </div>
                                        @break
                                    
                                    @case('heading')
                                        <div class="mt-1">
                                            <{{ $component['properties']['level'] ?? 'h2' }} class="text-lg font-bold">
                                                {{ $component['label'] }}
                                            </{{ $component['properties']['level'] ?? 'h2' }}>
                                        </div>
                                        @break
                                    
                                    @case('paragraph')
                                        <div class="mt-1">
                                            <p class="text-sm text-gray-700">{{ $component['label'] }}</p>
                                        </div>
                                        @break
                                    
                                    @default
                                        <div class="mt-1 text-sm text-gray-500">Unknown component type</div>
                                @endswitch

                                @if($component['required'] ?? false)
                                    <span class="text-xs text-red-500 mt-1">* Required</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach

                @if(count($components) === 0)
                    <div class="border border-dashed border-gray-300 rounded-md p-6 text-center">
                        <p class="text-gray-500">Add form components from the left panel</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-6 text-right">
        <button type="button" wire:click="saveForm" 
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Save Form
        </button>
    </div>
</div>