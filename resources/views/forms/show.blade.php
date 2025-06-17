<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Form Details: {{ $form->name }}
            </h2>
            <div>
                <a href="{{ route('forms.public', $form->id) }}" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2">
                    View Public Form
                </a>
                <a href="{{ route('projects.forms.edit', [$project, $form]) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Edit Form
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.forms.index', $project) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Forms
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Form Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Name:</p>
                                <p class="text-gray-900">{{ $form->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Type:</p>
                                <p class="text-gray-900">{{ $form->is_template ? 'Template' : 'Regular Form' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status:</p>
                                <p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $form->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Created:</p>
                                <p class="text-gray-900">{{ $form->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($form->description)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Description:</p>
                                <p class="text-gray-900">{{ $form->description }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Form Components</h3>
                        
                        <div class="space-y-4">
                            @forelse($form->components as $component)
                                <div class="border border-gray-200 rounded-md p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium">{{ $component->label }}</span>
                                        <span class="text-sm text-gray-500">Type: {{ ucfirst($component->type) }}</span>
                                    </div>
                                    
                                    <div class="text-sm">
                                        @if($component->required)
                                            <span class="text-red-500 text-xs">Required</span>
                                        @else
                                            <span class="text-gray-500 text-xs">Optional</span>
                                        @endif
                                        
                                        @if($component->type === 'select' || $component->type === 'radio' || $component->type === 'checkbox')
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-500 mb-1">Options:</p>
                                                <ul class="list-disc list-inside text-xs text-gray-700">
                                                    @foreach($component->properties['options'] ?? [] as $option)
                                                        <li>{{ $option }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        @if($component->type === 'image' && isset($component->properties['url']))
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-500 mb-1">Image URL:</p>
                                                <a href="{{ $component->properties['url'] }}" target="_blank" class="text-blue-600 text-xs hover:underline">
                                                    {{ Str::limit($component->properties['url'], 50) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">This form has no components yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Form Responses</h3>
                        
                        @if($form->responses->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($form->responses as $response)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $response->guest_name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ $response->guest_email ?? 'N/A' }}
                                                        @if($response->guest_phone)
                                                            <br>{{ $response->guest_phone }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ $response->created_at->format('M d, Y H:i') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button type="button" onclick="showResponseDetails({{ $response->id }})"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        View Details
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">This form has not received any responses yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Response Details Modal -->
    <div id="responseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Response Details</h3>
                    <button type="button" onclick="hideResponseModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="space-y-4">
                    <!-- Content will be loaded dynamically -->
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6 mb-2"></div>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <button type="button" onclick="hideResponseModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showResponseDetails(responseId) {
            // In a real application, this would fetch the response details via AJAX
            // For simplicity, we're just showing a placeholder modal
            const responses = @json($form->responses);
            const response = responses.find(r => r.id === responseId);
            
            if (response) {
                document.getElementById('modalTitle').textContent = `Response from ${response.guest_name}`;
                
                let content = `
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Submitted on:</p>
                        <p class="text-gray-900">${new Date(response.created_at).toLocaleString()}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Guest Information:</p>
                        <p class="text-gray-900">Name: ${response.guest_name}</p>
                        <p class="text-gray-900">Email: ${response.guest_email || 'N/A'}</p>
                        <p class="text-gray-900">Phone: ${response.guest_phone || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Responses:</p>
                `;
                
                const components = @json($form->components);
                
                for (const [key, value] of Object.entries(response.responses)) {
                    const component = components.find(c => c.id == key);
                    if (component) {
                        content += `
                            <div class="mb-2 border-b pb-2">
                                <p class="font-medium">${component.label}:</p>
                                <p class="text-gray-800">${Array.isArray(value) ? value.join(', ') : value}</p>
                            </div>
                        `;
                    }
                }
                
                content += `</div>`;
                
                document.getElementById('modalContent').innerHTML = content;
            } else {
                document.getElementById('modalContent').innerHTML = '<p class="text-red-500">Response not found</p>';
            }
            
            document.getElementById('responseModal').classList.remove('hidden');
        }
        
        function hideResponseModal() {
            document.getElementById('responseModal').classList.add('hidden');
        }
    </script>
</x-app-layout>