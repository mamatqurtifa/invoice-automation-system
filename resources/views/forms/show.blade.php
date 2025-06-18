<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Form: {{ $form->name }}
                </h2>
                <span class="px-3 py-1 text-xs rounded-full {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $form->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($form->is_template)
                    <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                        Template
                    </span>
                @endif
            </div>
            <div class="flex flex-wrap gap-2 mt-2 sm:mt-0">
                <a href="{{ route('forms.public', $form) }}" target="_blank" class="px-3 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors duration-150 inline-flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                    </svg>
                    View Form
                </a>
                <a href="{{ route('projects.forms.edit', [$project, $form]) }}" class="px-3 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors duration-150 inline-flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit Form
                </a>
                <button type="button" onclick="document.getElementById('toggle-active-modal').classList.remove('hidden')" class="px-3 py-2 {{ $form->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-full transition-colors duration-150 inline-flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                    </svg>
                    {{ $form->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.forms.index', $project) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Forms
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Overview Card -->
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden rounded-2xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="font-medium text-lg text-gray-900 mb-4">Form Overview</h3>
                        
                        <div class="space-y-4">
                            @if($form->description)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Description</h4>
                                    <p class="mt-1 text-sm text-gray-600">{{ $form->description }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Form Status</h4>
                                <p class="mt-1 text-sm {{ $form->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $form->is_active ? 'Active - Currently accepting responses' : 'Inactive - Not accepting responses' }}
                                </p>
                            </div>
                            
                            @if($form->closing_at)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Closing Date</h4>
                                    <p class="mt-1 text-sm {{ $form->isClosed() ? 'text-red-600' : ($form->closing_at->diffInDays(now()) < 3 ? 'text-yellow-600' : 'text-gray-600') }}">
                                        {{ $form->closing_at->format('F j, Y \a\t g:i A') }}
                                        @if($form->isClosed())
                                            <span class="block text-red-600 font-medium">Form is closed</span>
                                        @else
                                            <span class="block text-gray-500">{{ $form->closing_at->diffForHumans() }}</span>
                                        @endif
                                    </p>
                                    
                                    <form action="{{ route('projects.forms.remove-closing-date', [$project, $form]) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800">
                                            Remove closing date
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Set Closing Date</h4>
                                    <form action="{{ route('projects.forms.update-closing-date', [$project, $form]) }}" method="POST" class="mt-1">
                                        @csrf
                                        <div class="flex space-x-2">
                                            <div>
                                                <input type="date" name="closing_date" 
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs"
                                                       min="{{ date('Y-m-d') }}">
                                            </div>
                                            <div>
                                                <input type="time" name="closing_time" 
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs">
                                            </div>
                                            <div>
                                                <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Set
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Form Statistics</h4>
                                <div class="mt-1 grid grid-cols-2 gap-3">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="text-xs text-gray-500">Pages</div>
                                        <div class="font-medium text-lg">{{ $totalPages }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="text-xs text-gray-500">Components</div>
                                        <div class="font-medium text-lg">{{ $form->components->count() }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="text-xs text-gray-500">Responses</div>
                                        <div class="font-medium text-lg">{{ $responses->total() }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="text-xs text-gray-500">Created</div>
                                        <div class="text-sm font-medium">{{ $form->created_at->format('M j, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Form URL</h4>
                                <div class="mt-1 flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-600 truncate mr-2">
                                        {{ route('forms.public', $form) }}
                                    </div>
                                    <button onclick="copyToClipboard('{{ route('forms.public', $form) }}')" class="text-indigo-600 hover:text-indigo-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                            <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions Card -->
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden rounded-2xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="font-medium text-lg text-gray-900 mb-4">Form Actions</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Embed Form</h4>
                                <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-2">Copy this code to embed the form on your website:</div>
                                    <div class="flex items-center justify-between">
                                        <code class="text-xs bg-gray-100 p-2 rounded block overflow-x-auto whitespace-nowrap max-w-xs">&lt;iframe src="{{ route('forms.public', $form) }}" width="100%" height="600" frameborder="0"&gt;&lt;/iframe&gt;</code>
                                        <button onclick="copyToClipboard('<iframe src=\'{{ route('forms.public', $form) }}\' width=\'100%\' height=\'600\' frameborder=\'0\'></iframe>')" class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                                <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Export Options</h4>
                                <div class="mt-1 grid grid-cols-2 gap-3">
                                    <a href="{{ route('projects.forms.export-csv', [$project, $form]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-all duration-150 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        CSV Export
                                    </a>
                                    <a href="{{ route('projects.forms.export-pdf', [$project, $form]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-all duration-150 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        PDF Export
                                    </a>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Form Management</h4>
                                <div class="mt-1 grid grid-cols-1 gap-3">
                                    <a href="{{ route('projects.forms.responses', [$project, $form]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-100 text-indigo-800 rounded-lg hover:bg-indigo-200 transition-all duration-150 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                        </svg>
                                        View Detailed Responses
                                    </a>
                                    <a href="{{ route('projects.forms.edit', [$project, $form]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-all duration-150 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Edit Form
                                    </a>
                                    <button type="button" onclick="document.getElementById('clone-form-modal').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2 bg-purple-100 text-purple-800 rounded-lg hover:bg-purple-200 transition-all duration-150 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" />
                                            <path d="M3 8a2 2 0 012-2h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                        </svg>
                                        Clone Form
                                    </button>
                                    @if(!$form->is_template)
                                        <button type="button" onclick="document.getElementById('save-as-template-modal').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-all duration-150 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                            </svg>
                                            Save as Template
                                        </button>
                                    @endif
                                    <button type="button" onclick="document.getElementById('delete-form-modal').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-all duration-150 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Delete Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form QR Code -->
                <div class="bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden rounded-2xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="font-medium text-lg text-gray-900 mb-4">Form QR Code</h3>
                        
                        <div class="flex flex-col items-center justify-center space-y-4">
                            <div class="bg-white p-2 rounded-lg shadow-sm">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('forms.public', $form)) }}" 
                                     alt="Form QR Code" class="w-48 h-48 object-contain">
                            </div>
                            
                            <div class="text-center">
                                <p class="text-sm text-gray-500">Scan this QR code to access the form on mobile devices.</p>
                                <a href="https://api.qrserver.com/v1/create-qr-code/?size=600x600&data={{ urlencode(route('forms.public', $form)) }}&download=1" 
                                   class="text-sm text-indigo-600 hover:text-indigo-900 inline-flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Download QR Code
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Preview Section -->
            <div class="mt-6 bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden rounded-2xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-medium text-lg text-gray-900">Form Preview</h3>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-2">{{ $totalPages }} pages</span>
                            <a href="{{ route('forms.public', $form) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                                </svg>
                                Open Full Form
                            </a>
                        </div>
                    </div>
                    
                    @if(count($pageComponents) > 0)
                        <div x-data="{ currentPage: 1 }">
                            <!-- Page selector -->
                            @if($totalPages > 1)
                                <div class="mb-4 flex space-x-1 overflow-x-auto pb-2">
                                    @foreach($pageComponents as $pageNumber => $components)
                                        <button @click="currentPage = {{ $pageNumber }}"
                                                :class="{ 'bg-indigo-100 text-indigo-800 font-medium': currentPage === {{ $pageNumber }}, 'bg-gray-100 text-gray-600 hover:bg-gray-200': currentPage !== {{ $pageNumber }} }"
                                                class="px-3 py-1 rounded-md text-sm transition-colors duration-150">
                                            Page {{ $pageNumber }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Page content previews -->
                            @foreach($pageComponents as $pageNumber => $components)
                                <div x-show="currentPage === {{ $pageNumber }}" class="border rounded-lg p-4 bg-gray-50">
                                    @if(count($components) > 0)
                                        <div class="space-y-4">
                                            @foreach($components as $component)
                                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                                    @switch($component->type)
                                                        @case('heading')
                                                            @php
                                                                $level = $component->properties['level'] ?? 'h2';
                                                                $headingSizes = [
                                                                    'h1' => 'text-xl font-bold',
                                                                    'h2' => 'text-lg font-bold',
                                                                    'h3' => 'text-base font-semibold',
                                                                    'h4' => 'text-sm font-semibold',
                                                                ];
                                                                $headingClass = $headingSizes[$level] ?? $headingSizes['h2'];
                                                            @endphp
                                                            <div class="{{ $headingClass }} text-gray-900">{{ $component->label }}</div>
                                                            <div class="mt-1 text-xs text-gray-500">Heading ({{ strtoupper($level) }})</div>
                                                            @break
                                                            
                                                        @case('paragraph')
                                                            <p class="text-sm text-gray-700">{{ $component->label }}</p>
                                                            <div class="mt-1 text-xs text-gray-500">Paragraph</div>
                                                            @break
                                                            
                                                        @case('image')
                                                            <div class="flex justify-{{ $component->properties['alignment'] ?? 'center' }}">
                                                                @if(isset($component->properties['url']) && $component->properties['url'])
                                                                    <img src="{{ $component->properties['url'] }}" alt="{{ $component->label }}" class="max-h-40 object-contain">
                                                                @else
                                                                    <div class="bg-gray-100 p-4 rounded-md text-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                        <p class="mt-2 text-xs text-gray-500">Image placeholder</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="mt-1 text-xs text-gray-500">
                                                                Image ({{ $component->properties['alignment'] ?? 'center' }} aligned)
                                                                @if($component->label)
                                                                    - {{ $component->label }}
                                                                @endif
                                                            </div>
                                                            @break
                                                            
                                                        @case('text')
                                                        @case('email')
                                                        @case('phone')
                                                        @case('number')
                                                            <label class="block text-sm font-medium text-gray-700">
                                                                {{ $component->label }}
                                                                @if($component->required)
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </label>
                                                            <input type="{{ $component->type }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" disabled>
                                                            <div class="mt-1 text-xs text-gray-500">{{ ucfirst($component->type) }} field</div>
                                                            @break
                                                            
                                                        @case('textarea')
                                                            <label class="block text-sm font-medium text-gray-700">
                                                                {{ $component->label }}
                                                                @if($component->required)
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </label>
                                                            <textarea rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" disabled></textarea>
                                                            <div class="mt-1 text-xs text-gray-500">Textarea field</div>
                                                            @break
                                                            
                                                        @case('select')
                                                            <label class="block text-sm font-medium text-gray-700">
                                                                {{ $component->label }}
                                                                @if($component->required)
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </label>
                                                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" disabled>
                                                                <option>-- Select an option --</option>
                                                                @foreach($component->properties['options'] ?? [] as $option)
                                                                    <option>{{ $option }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="mt-1 text-xs text-gray-500">Dropdown with {{ count($component->properties['options'] ?? []) }} options</div>
                                                            @break
                                                            
                                                        @case('radio')
                                                            <fieldset>
                                                                <legend class="text-sm font-medium text-gray-700">
                                                                    {{ $component->label }}
                                                                    @if($component->required)
                                                                        <span class="text-red-500">*</span>
                                                                    @endif
                                                                </legend>
                                                                <div class="mt-1 space-y-1">
                                                                    @foreach($component->properties['options'] ?? [] as $option)
                                                                        <div class="flex items-center">
                                                                            <input type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" disabled>
                                                                            <label class="ml-3 block text-sm text-gray-700">
                                                                                {{ $option }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div class="mt-1 text-xs text-gray-500">Radio buttons with {{ count($component->properties['options'] ?? []) }} options</div>
                                                            </fieldset>
                                                            @break
                                                            
                                                        @case('checkbox')
                                                            <fieldset>
                                                                <legend class="text-sm font-medium text-gray-700">
                                                                    {{ $component->label }}
                                                                    @if($component->required)
                                                                        <span class="text-red-500">*</span>
                                                                    @endif
                                                                </legend>
                                                                <div class="mt-1 space-y-1">
                                                                    @foreach($component->properties['options'] ?? [] as $option)
                                                                        <div class="flex items-center">
                                                                            <input type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" disabled>
                                                                            <label class="ml-3 block text-sm text-gray-700">
                                                                                {{ $option }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div class="mt-1 text-xs text-gray-500">Checkboxes with {{ count($component->properties['options'] ?? []) }} options</div>
                                                            </fieldset>
                                                            @break
                                                            
                                                        @case('date')
                                                            <label class="block text-sm font-medium text-gray-700">
                                                                {{ $component->label }}
                                                                @if($component->required)
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </label>
                                                            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" disabled>
                                                            <div class="mt-1 text-xs text-gray-500">Date field</div>
                                                            @break
                                                            
                                                        @case('product')
                                                            <fieldset>
                                                                <legend class="text-sm font-medium text-gray-700">
                                                                    {{ $component->label }}
                                                                    @if($component->required)
                                                                        <span class="text-red-500">*</span>
                                                                    @endif
                                                                </legend>
                                                                <div class="mt-2 border rounded-md p-3">
                                                                    @php
                                                                        $productCount = count($component->properties['product_ids'] ?? []);
                                                                    @endphp
                                                                    
                                                                    @if($productCount > 0)
                                                                        <div class="bg-gray-50 p-3 rounded-md">
                                                                            <div class="flex justify-between items-center">
                                                                                <div>
                                                                                    <div class="text-sm font-medium">Product Selection</div>
                                                                                    <div class="text-xs text-gray-500">{{ $productCount }} product(s) available</div>
                                                                                </div>
                                                                                <div>
                                                                                    <div class="flex items-center space-x-1 text-xs">
                                                                                        <div class="px-2 py-1 bg-gray-100 rounded-md {{ isset($component->properties['show_images']) && $component->properties['show_images'] ? 'text-green-600' : 'text-gray-400' }}">
                                                                                            Images {{ isset($component->properties['show_images']) && $component->properties['show_images'] ? '✓' : '✗' }}
                                                                                        </div>
                                                                                        <div class="px-2 py-1 bg-gray-100 rounded-md {{ isset($component->properties['show_variants']) && $component->properties['show_variants'] ? 'text-green-600' : 'text-gray-400' }}">
                                                                                            Variants {{ isset($component->properties['show_variants']) && $component->properties['show_variants'] ? '✓' : '✗' }}
                                                                                        </div>
                                                                                        <div class="px-2 py-1 bg-gray-100 rounded-md {{ isset($component->properties['show_prices']) && $component->properties['show_prices'] ? 'text-green-600' : 'text-gray-400' }}">
                                                                                            Prices {{ isset($component->properties['show_prices']) && $component->properties['show_prices'] ? '✓' : '✗' }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="text-center py-4 text-xs text-gray-500">
                                                                            No products assigned to this component
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="mt-1 text-xs text-gray-500">
                                                                    Product selection component with {{ $productCount }} product(s)
                                                                </div>
                                                            </fieldset>
                                                            @break
                                                            
                                                        @case('page_break')
                                                            <div class="flex justify-between items-center">
                                                                <button type="button" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white opacity-50 cursor-not-allowed">
                                                                    {{ $component->properties['prev_button_text'] ?? 'Previous' }}
                                                                </button>
                                                                
                                                                <button type="button" class="px-3 py-1 bg-indigo-600 text-white rounded-md text-sm opacity-50 cursor-not-allowed">
                                                                    {{ $component->properties['next_button_text'] ?? 'Next' }}
                                                                </button>
                                                            </div>
                                                            <div class="mt-1 text-xs text-gray-500">Page break with navigation controls</div>
                                                            @break
                                                            
                                                        @default
                                                            <div class="text-sm text-gray-700">
                                                                {{ $component->label }}
                                                                @if($component->required)
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </div>
                                                            <div class="mt-1 text-xs text-gray-500">Unknown component type: {{ $component->type }}</div>
                                                    @endswitch
                                                </div>
                                            @endforeach
                                            
                                            <!-- Submit button at end of last page -->
                                            @if($pageNumber === $totalPages)
                                                <div class="mt-4 text-center">
                                                    <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium opacity-50 cursor-not-allowed">
                                                        Submit
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center py-12 text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p>No components added to page {{ $pageNumber }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">This form has no components</p>
                            <p class="mt-2 text-sm">Click the Edit button to add components to your form.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Form Responses Section -->
            <div class="mt-6 bg-white backdrop-blur-lg bg-opacity-90 overflow-hidden rounded-2xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-medium text-lg text-gray-900">Recent Responses</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('projects.forms.responses', [$project, $form]) }}" class="text-sm text-indigo-600 hover:text-indigo-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                                View All Responses
                            </a>
                        </div>
                    </div>
                    
                    @if($responses->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Respondent</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($responses as $response)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $response->guest_name ?: 'Anonymous' }}</div>
                                                <div class="text-sm text-gray-500">
                                                    @if($response->guest_email)
                                                        {{ $response->guest_email }}<br>
                                                    @endif
                                                    @if($response->guest_phone)
                                                        {{ $response->guest_phone }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $response->created_at->format('M j, Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $response->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('projects.forms.view-response', [$project, $form, $response]) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                                @if($response->order)
                                                    <a href="{{ route('projects.orders.show', [$project, $response->order]) }}" class="ml-3 text-green-600 hover:text-green-900">View Order</a>
                                                @else
                                                    <a href="{{ route('projects.forms.create-order', [$project, $form, $response]) }}" class="ml-3 text-blue-600 hover:text-blue-900">Create Order</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $responses->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">No responses received yet.</p>
                            <p class="text-sm text-gray-500">Share your form link to start collecting responses.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toggle Active Status Modal -->
    <div id="toggle-active-modal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('projects.forms.toggle-active', [$project, $form]) }}">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-{{ $form->is_active ? 'yellow' : 'green' }}-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-{{ $form->is_active ? 'yellow' : 'green' }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ $form->is_active ? 'Deactivate Form' : 'Activate Form' }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        @if($form->is_active)
                                            Are you sure you want to deactivate this form? Once deactivated, it will no longer be accessible to guests and will not accept new submissions.
                                        @else
                                            Are you sure you want to activate this form? Once activated, it will be accessible to guests and will start accepting submissions.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-{{ $form->is_active ? 'yellow' : 'green' }}-600 text-base font-medium text-white hover:bg-{{ $form->is_active ? 'yellow' : 'green' }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $form->is_active ? 'yellow' : 'green' }}-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $form->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('toggle-active-modal').classList.add('hidden')">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Form Modal -->
    <div id="delete-form-modal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('projects.forms.destroy', [$project, $form]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete Form
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete this form? All form components will be deleted. This action cannot be undone.
                                        
                                        @if($responses->count() > 0)
                                            <span class="block mt-2 text-red-600 font-medium">Warning: This form has {{ $responses->count() }} responses. Deleting it will make these responses inaccessible.</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('delete-form-modal').classList.add('hidden')">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
        <!-- Clone Form Modal -->
    <div id="clone-form-modal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('projects.forms.clone', [$project, $form]) }}">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Clone Form
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        This will create a duplicate copy of the form with all its components. The cloned form will be inactive by default and will have "(Copy)" added to its name.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Clone Form
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('clone-form-modal').classList.add('hidden')">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Save as Template Modal -->
    <div id="save-as-template-modal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('projects.forms.save-as-template', [$project, $form]) }}">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Save as Template
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Save this form as a template so you can use it as a starting point for future forms. Templates can be reused across different projects.
                                    </p>
                                    
                                    <div class="mt-3">
                                        <label for="template_name" class="block text-sm font-medium text-gray-700">Template Name</label>
                                        <input type="text" name="template_name" id="template_name" value="{{ $form->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="make_public" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">Make this template available to other projects</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500">Public templates can be used by any user in any project.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Template
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('save-as-template-modal').classList.add('hidden')">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function copyToClipboard(text) {
            // Create a temporary input element
            const input = document.createElement('input');
            input.setAttribute('value', text);
            document.body.appendChild(input);
            
            // Select the text and copy it
            input.select();
            document.execCommand('copy');
            
            // Remove the temporary element
            document.body.removeChild(input);
            
            // Show a notification
            const notification = document.createElement('div');
            notification.classList.add('fixed', 'bottom-4', 'right-4', 'bg-gray-900', 'text-white', 'px-4', 'py-2', 'rounded-md', 'text-sm', 'shadow-lg', 'z-50');
            notification.innerText = 'Copied to clipboard!';
            document.body.appendChild(notification);
            
            // Remove notification after 2 seconds
            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 2000);
        }
    </script>
</x-app-layout>