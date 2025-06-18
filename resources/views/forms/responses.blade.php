<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Form Responses: {{ $form->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.forms.export-csv', [$project, $form]) }}" class="px-3 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors duration-150 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('projects.forms.export-pdf', [$project, $form]) }}" class="px-3 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors duration-150 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.forms.show', [$project, $form]) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Form
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Form Responses ({{ $responses->total() }})</h3>
                    
                    @if($responses->count() > 0)
                        <!-- Response Overview Table -->
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Respondent</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($responses as $response)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $response->id }}</td>
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
                                                <button type="button" 
                                                        onclick="toggleResponseDetails({{ $response->id }})" 
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                    View Details
                                                </button>
                                                @if($response->order)
                                                    <a href="{{ route('projects.orders.show', [$project, $response->order]) }}" class="ml-3 text-green-600 hover:text-green-900">
                                                        View Order
                                                    </a>
                                                @else
                                                    <a href="{{ route('projects.forms.create-order', [$project, $form, $response]) }}" class="ml-3 text-blue-600 hover:text-blue-900">
                                                        Create Order
                                                    </a>
                                                @endif
                                                <form method="POST" action="{{ route('projects.forms.delete-response', [$project, $form, $response]) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-3 text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this response?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <!-- Response Details Row - Initially Hidden -->
                                        <tr id="response-details-{{ $response->id }}" class="bg-gray-50 hidden">
                                            <td colspan="4" class="px-6 py-4">
                                                <div class="border rounded-lg p-4 bg-white space-y-4">
                                                    <h4 class="font-medium text-gray-900">Response Details</h4>
                                                    
                                                    <!-- Response Data Table -->
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($components as $component)
                                                                <tr>
                                                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        {{ $component->label }}
                                                                        @if($component->required)
                                                                            <span class="text-red-500 ml-1">*</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-4 py-2 text-sm text-gray-900">
                                                                        @if(isset($response->responses[$component->id]))
                                                                            @if(is_array($response->responses[$component->id]))
                                                                                {{ implode(', ', $response->responses[$component->id]) }}
                                                                            @else
                                                                                {{ $response->responses[$component->id] }}
                                                                            @endif
                                                                        @else
                                                                            <span class="text-gray-400 italic">No response</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    
                                                    <!-- Response Meta -->
                                                    <div class="text-xs text-gray-500 pt-2 border-t">
                                                        <div class="flex justify-between">
                                                            <div>
                                                                Submitted: {{ $response->created_at->format('M j, Y H:i:s') }}
                                                            </div>
                                                            <button type="button" onclick="toggleResponseDetails({{ $response->id }})" class="text-indigo-600 hover:text-indigo-900">
                                                                Close Details
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
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
                        <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No responses yet</p>
                            <p class="mt-1 text-sm">This form hasn't received any submissions.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleResponseDetails(responseId) {
            const detailsRow = document.getElementById(`response-details-${responseId}`);
            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
            } else {
                detailsRow.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>