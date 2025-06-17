<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $form->name }} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $form->name }}
                </h2>
            </div>
        </header>

        <main class="flex-grow">
            <div class="py-12">
                <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @if($form->description)
                                <div class="mb-6 text-gray-600">
                                    {{ $form->description }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('forms.response.store', $form->id) }}" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Guest Information -->
                                <div class="mb-8 border-b pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Information</h3>
                                    
                                    <div class="mb-4">
                                        <label for="guest_name" class="block text-sm font-medium text-gray-700">Name *</label>
                                        <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('guest_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="guest_email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('guest_email')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="guest_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="text" name="guest_phone" id="guest_phone" value="{{ old('guest_phone') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('guest_phone')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Form Components -->
                                <div class="space-y-6">
                                    @foreach($form->components as $component)
                                        <div class="form-component">
                                            @switch($component->type)
                                                @case('heading')
                                                    <{{ $component->properties['level'] ?? 'h2' }} class="text-lg font-bold mb-2">
                                                        {{ $component->label }}
                                                    </{{ $component->properties['level'] ?? 'h2' }}>
                                                    @break
                                                    
                                                @case('paragraph')
                                                    <p class="text-gray-700 mb-2">{{ $component->label }}</p>
                                                    @break
                                                    
                                                @case('image')
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $component->label }}</label>
                                                        @if(isset($component->properties['url']) && $component->properties['url'])
                                                            <img src="{{ $component->properties['url'] }}" alt="{{ $component->label }}" 
                                                                class="max-w-full h-auto rounded">
                                                        @endif
                                                    </div>
                                                    @break
                                                    
                                                @case('text')
                                                @case('email')
                                                @case('number')
                                                @case('phone')
                                                    <div class="mb-4">
                                                        <label for="component_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $component->label }}
                                                            @if($component->required)
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>
                                                        <input type="{{ $component->type }}" 
                                                            name="responses[{{ $component->id }}]" 
                                                            id="component_{{ $component->id }}" 
                                                            value="{{ old('responses.' . $component->id) }}"
                                                            @if($component->required) required @endif
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        @error('responses.' . $component->id)
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @break
                                                    
                                                @case('textarea')
                                                    <div class="mb-4">
                                                        <label for="component_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $component->label }}
                                                            @if($component->required)
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>
                                                        <textarea name="responses[{{ $component->id }}]" 
                                                            id="component_{{ $component->id }}" 
                                                            @if($component->required) required @endif
                                                            rows="3"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('responses.' . $component->id) }}</textarea>
                                                        @error('responses.' . $component->id)
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @break
                                                    
                                                @case('select')
                                                    <div class="mb-4">
                                                        <label for="component_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $component->label }}
                                                            @if($component->required)
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>
                                                        <select name="responses[{{ $component->id }}]" 
                                                            id="component_{{ $component->id }}" 
                                                            @if($component->required) required @endif
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            <option value="">-- Select --</option>
                                                            @foreach($component->properties['options'] ?? [] as $option)
                                                                <option value="{{ $option }}" {{ old('responses.' . $component->id) == $option ? 'selected' : '' }}>
                                                                    {{ $option }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('responses.' . $component->id)
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @break
                                                    
                                                @case('radio')
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $component->label }}
                                                            @if($component->required)
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>
                                                        <div class="mt-2 space-y-2">
                                                            @foreach($component->properties['options'] ?? [] as $option)
                                                                <div class="flex items-center">
                                                                    <input type="radio" 
                                                                        name="responses[{{ $component->id }}]" 
                                                                        id="component_{{ $component->id }}_{{ $loop->index }}" 
                                                                        value="{{ $option }}" 
                                                                        {{ old('responses.' . $component->id) == $option ? 'checked' : '' }}
                                                                        @if($component->required && $loop->first) required @endif
                                                                        class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                    <label for="component_{{ $component->id }}_{{ $loop->index }}" class="ml-2 text-sm text-gray-700">
                                                                        {{ $option }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @error('responses.' . $component->id)
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @break
                                                    
                                                @case('checkbox')
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $component->label }}
                                                            @if($component->required)
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>
                                                        <div class="mt-2 space-y-2">
                                                            @foreach($component->properties['options'] ?? [] as $option)
                                                                <div class="flex items-center">
                                                                    <input type="checkbox" 
                                                                        name="responses[{{ $component->id }}][]" 
                                                                        id="component_{{ $component->id }}_{{ $loop->index }}" 
                                                                        value="{{ $option }}" 
                                                                        @if(is_array(old('responses.' . $component->id)) && in_array($option, old('responses.' . $component->id))) checked @endif
                                                                        @if($component->required && $loop->first) required @endif
                                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                    <label for="component_{{ $component->id }}_{{ $loop->index }}" class="ml-2 text-sm text-gray-700">
                                                                        {{ $option }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @error('responses.' . $component->id)
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @break
                                                    
                                                @case('date')
                                                    <div class="mb-4">
                                                        <label for="component_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $component->label }}
                                                            @if($component->required)
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>
                                                        <input type="date" 
                                                            name="responses[{{ $component->id }}]" 
                                                            id="component_{{ $component->id }}" 
                                                            value="{{ old('responses.' . $component->id) }}"
                                                            @if($component->required) required @endif
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        @error('responses.' . $component->id)
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @break
                                                    
                                                @default
                                                    <div class="mb-4">
                                                        <p class="text-sm text-gray-500">Unknown component type: {{ $component->type }}</p>
                                                    </div>
                                            @endswitch
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-8 text-right">
                                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="bg-white border-t border-gray-200 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>