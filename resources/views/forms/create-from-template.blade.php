<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Form from Template: {{ $template->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.forms.templates', $project) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Templates
                </a>
            </div>
            
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Template Preview</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $template->description }}</p>
                    
                    <div class="mt-4 flex justify-between text-sm text-gray-600">
                        <div>
                            Components: {{ $template->components->count() }} • 
                            Pages: {{ $template->components->pluck('page')->unique()->count() }}
                        </div>
                        <div>
                            Created by: {{ $template->project->name }}
                        </div>
                    </div>
                </div>
                
                <div class="border rounded-lg p-6 bg-gray-50 mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Template Components</h4>
                    
                    @php
                        // Group components by page
                        $pageComponents = [];
                        foreach($template->components as $component) {
                            $pageComponents[$component->page][] = $component;
                        }
                        ksort($pageComponents);
                    @endphp
                    
                    <div class="space-y-4">
                        @foreach($pageComponents as $pageNumber => $components)
                            <div class="border rounded-lg p-4 bg-white">
                                <h5 class="font-medium text-gray-900 mb-3">Page {{ $pageNumber }}</h5>
                                
                                <ul class="space-y-2 list-disc list-inside pl-4">
                                    @foreach($components as $component)
                                        <li class="text-sm">
                                            <span class="font-medium">{{ $component->label }}</span>
                                            <span class="text-gray-500">
                                                ({{ ucfirst($component->type) }}{{ $component->required ? ', Required' : '' }})
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="text-right">
                    <a href="{{ route('projects.forms.edit', ['project' => $project->id, 'templateId' => $template->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Form Using This Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>