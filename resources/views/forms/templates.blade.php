<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Form Templates
            </h2>
            <a href="{{ route('projects.forms.index', $project) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                Back to Forms
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Your Templates -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Templates</h3>
                    
                    @if($templates->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($templates as $template)
                                <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                    <div class="bg-indigo-50 py-4 px-5 border-b">
                                        <h4 class="font-medium text-indigo-900">{{ $template->name }}</h4>
                                    </div>
                                    <div class="p-5">
                                        <p class="text-sm text-gray-600 mb-4">
                                            {{ Str::limit($template->description, 100) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mb-4">
                                            Components: {{ $template->components->count() }} • 
                                            Pages: {{ $template->components->pluck('page')->unique()->count() }}
                                        </p>
                                        <div class="flex justify-end">
                                            <a href="{{ route('projects.forms.create-from-template', [$project, $template]) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Use Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No templates created yet</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                When creating a form, check "Save as Template" to create reusable templates.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Public Templates -->
            @if($publicTemplates->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Public Templates</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($publicTemplates as $template)
                                <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                    <div class="bg-green-50 py-4 px-5 border-b">
                                        <h4 class="font-medium text-green-900">{{ $template->name }}</h4>
                                        <p class="text-xs text-green-800">By {{ $template->project->name }}</p>
                                    </div>
                                    <div class="p-5">
                                        <p class="text-sm text-gray-600 mb-4">
                                            {{ Str::limit($template->description, 100) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mb-4">
                                            Components: {{ $template->components->count() }} • 
                                            Pages: {{ $template->components->pluck('page')->unique()->count() }}
                                        </p>
                                        <div class="flex justify-end">
                                            <a href="{{ route('projects.forms.create-from-template', [$project, $template]) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                Use Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>