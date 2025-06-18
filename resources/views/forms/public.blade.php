<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .form-transition {
            transition: all 0.3s ease-out;
        }
        .form-hidden {
            opacity: 0;
            transform: translateX(20px);
        }
        .form-visible {
            opacity: 1;
            transform: translateX(0);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        body {
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4eaff 100%);
            min-height: 100vh;
        }
        @keyframes pulse-border {
            0% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(99, 102, 241, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
            }
        }
        .pulse-effect:focus {
            animation: pulse-border 1.5s infinite;
        }
    </style>
</head>
<body class="p-4 sm:p-0">
    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8" x-data="{ 
        currentPage: 1,
        totalPages: {{ $totalPages }},
        pages: {{ json_encode(array_keys($pageComponents)) }},
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                window.scrollTo(0, 0);
            }
        },
        
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                window.scrollTo(0, 0);
            }
        }
    }">
        <!-- Header -->
        <div class="glass-card shadow-lg rounded-2xl p-6 mb-6">
            <h1 class="text-xl font-bold text-gray-900">{{ $form->name }}</h1>
            
            @if($form->description)
                <p class="mt-2 text-sm text-gray-600">{{ $form->description }}</p>
            @endif
            
            @if($form->closing_at)
                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm text-yellow-700">
                            <p>This form will close on {{ $form->closing_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Progress indicator for multi-page forms -->
            @if($totalPages > 1)
                <div class="mt-4">
                    <div class="flex items-center">
                        <p class="text-sm text-gray-700">
                            Page <span x-text="currentPage"></span> of {{ $totalPages }}
                        </p>
                        <div class="flex-1 ml-4">
                            <div class="h-2 bg-gray-200 rounded-full">
                                <div 
                                    class="h-2 bg-indigo-600 rounded-full transition-all duration-300 ease-in-out" 
                                    x-bind:style="'width: ' + (currentPage / totalPages * 100) + '%'"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Form errors -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('forms.submit', $form->id) }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Form pages -->
            @foreach($pageComponents as $pageNumber => $components)
                <div 
                    x-show="currentPage == {{ $pageNumber }}" 
                    x-transition:enter="form-transition" 
                    x-transition:enter-start="form-hidden" 
                    x-transition:enter-end="form-visible"
                    x-transition:leave="form-transition"
                    x-transition:leave-start="form-visible"
                    x-transition:leave-end="form-hidden"
                    class="glass-card shadow-lg rounded-2xl p-6 mb-6"
                >
                    @foreach($components as $component)
                        <div class="mb-6 last:mb-0">
                            @switch($component->type)
                                @case('text')
                                @case('number')
                                @case('email')
                                @case('phone')
                                    <label for="field_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $component->label }}
                                        @if($component->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <input 
                                        type="{{ $component->type }}" 
                                        id="field_{{ $component->id }}"
                                        name="field_{{ $component->id }}"
                                        value="{{ old('field_' . $component->id) }}"
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pulse-effect"
                                        @if($component->required) required @endif
                                    >
                                    @break
                                
                                @case('textarea')
                                    <label for="field_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $component->label }}
                                        @if($component->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <textarea 
                                        id="field_{{ $component->id }}"
                                        name="field_{{ $component->id }}"
                                        rows="3"
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pulse-effect"
                                        @if($component->required) required @endif
                                    >{{ old('field_' . $component->id) }}</textarea>
                                    @break
                                
                                @case('date')
                                    <label for="field_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $component->label }}
                                        @if($component->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <input 
                                        type="date" 
                                        id="field_{{ $component->id }}"
                                        name="field_{{ $component->id }}"
                                        value="{{ old('field_' . $component->id) }}"
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pulse-effect"
                                        @if($component->required) required @endif
                                    >
                                    @break
                                
                                @case('select')
                                    <label for="field_{{ $component->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $component->label }}
                                        @if($component->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <select 
                                        id="field_{{ $component->id }}"
                                        name="field_{{ $component->id }}"
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pulse-effect"
                                        @if($component->required) required @endif
                                    >
                                        <option value="">-- Select --</option>
                                        @foreach($component->properties['options'] ?? [] as $option)
                                            <option value="{{ $option }}" {{ old('field_' . $component->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @break
                                
                                @case('radio')
                                    <fieldset>
                                        <legend class="block text-sm font-medium text-gray-700">
                                            {{ $component->label }}
                                            @if($component->required)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </legend>
                                        <div class="mt-2 space-y-3">
                                            @foreach($component->properties['options'] ?? [] as $option)
                                                <div class="flex items-center">
                                                    <input 
                                                        type="radio" 
                                                        id="field_{{ $component->id }}_{{ $loop->index }}" 
                                                        name="field_{{ $component->id }}" 
                                                        value="{{ $option }}"
                                                        {{ old('field_' . $component->id) == $option ? 'checked' : '' }}
                                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 pulse-effect"
                                                        @if($component->required && $loop->first) required @endif
                                                    >
                                                    <label for="field_{{ $component->id }}_{{ $loop->index }}" class="ml-3 block text-sm text-gray-700">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                    @break
                                
                                @case('checkbox')
                                    <fieldset>
                                        <legend class="block text-sm font-medium text-gray-700">
                                            {{ $component->label }}
                                            @if($component->required)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </legend>
                                        <div class="mt-2 space-y-3">
                                            @foreach($component->properties['options'] ?? [] as $option)
                                                <div class="flex items-center">
                                                    <input 
                                                        type="checkbox" 
                                                        id="field_{{ $component->id }}_{{ $loop->index }}" 
                                                        name="field_{{ $component->id }}[]" 
                                                        value="{{ $option }}"
                                                        {{ is_array(old('field_' . $component->id)) && in_array($option, old('field_' . $component->id)) ? 'checked' : '' }}
                                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded pulse-effect"
                                                        @if($component->required && $loop->first) required @endif
                                                    >
                                                    <label for="field_{{ $component->id }}_{{ $loop->index }}" class="ml-3 block text-sm text-gray-700">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                    @break
                                
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
                                    @break
                                
                                @case('paragraph')
                                    <p class="text-sm text-gray-700">{{ $component->label }}</p>
                                    @break
                                
                                @case('image')
                                    <div class="flex justify-{{ $component->properties['alignment'] ?? 'center' }}">
                                        <img src="{{ $component->properties['url'] }}" alt="{{ $component->label }}" 
                                            class="max-w-full rounded-xl shadow" style="max-height: 300px; width: {{ $component->properties['width'] ?? 'auto' }}">
                                    </div>
                                    @if($component->label)
                                        <p class="mt-2 text-sm text-gray-500 text-center">{{ $component->label }}</p>
                                    @endif
                                    @break
                                    
                                @case('product')
                                    <fieldset>
                                        <legend class="block text-sm font-medium text-gray-700">
                                            {{ $component->label }}
                                            @if($component->required)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </legend>
                                        
                                        @if(isset($component->properties['product_ids']) && count($component->properties['product_ids']) > 0)
                                            <div class="mt-3 space-y-4">
                                                @foreach($component->properties['product_ids'] as $index => $productId)
                                                    @php
                                                        $product = $products->firstWhere('id', $productId);
                                                    @endphp
                                                    @if($product)
                                                        <div class="border rounded-xl p-4 shadow-sm bg-white" x-data="{ 
                                                            selected: false, 
                                                            variantId: '', 
                                                            quantity: 1,
                                                            price: {{ $product->base_price }},
                                                            variants: {{ $product->variants }},
                                                            
                                                            updateSelection() {
                                                                if (this.selected) {
                                                                    document.getElementById('product_{{ $component->id }}_{{ $index }}').value = 
                                                                        '{{ $product->id }}:' + (this.variantId || '') + ':' + this.quantity;
                                                                } else {
                                                                    document.getElementById('product_{{ $component->id }}_{{ $index }}').value = '';
                                                                }
                                                            },
                                                            
                                                            updatePrice() {
                                                                if (this.variantId) {
                                                                    const variant = this.variants.find(v => v.id == this.variantId);
                                                                    if (variant) {
                                                                        this.price = {{ $product->base_price }} + parseFloat(variant.price_adjustment);
                                                                    }
                                                                } else {
                                                                    this.price = {{ $product->base_price }};
                                                                }
                                                                this.updateSelection();
                                                            }
                                                        }">
                                                            <div class="flex items-start space-x-4">
                                                                @if($component->properties['show_images'] ?? true)
                                                                    <div class="flex-shrink-0 w-20 h-20">
                                                                        @if($product->image)
                                                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                                                                class="w-full h-full object-cover rounded-lg">
                                                                        @else
                                                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                                                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                </svg>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                
                                                                <div class="flex-grow">
                                                                    <div class="flex items-start justify-between">
                                                                        <div>
                                                                            <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                                                            @if($component->properties['show_prices'] ?? true)
                                                                                <p class="text-sm text-gray-700">
                                                                                    Rp <span x-text="price.toLocaleString('id-ID')"></span>
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                        
                                                                        <label class="inline-flex items-center">
                                                                            <input type="checkbox" x-model="selected" @change="updateSelection()"
                                                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pulse-effect">
                                                                            <span class="ml-2 text-sm text-gray-700">Select</span>
                                                                        </label>
                                                                        
                                                                        <!-- Hidden input to store the selection value -->
                                                                        <input type="hidden" id="product_{{ $component->id }}_{{ $index }}" 
                                                                            name="field_{{ $component->id }}[{{ $index }}][selection]">
                                                                        <input type="hidden" 
                                                                            name="field_{{ $component->id }}[{{ $index }}][product_id]" 
                                                                            value="{{ $product->id }}">
                                                                    </div>
                                                                    
                                                                    <div x-show="selected" class="mt-4 space-y-3" x-transition:enter="transition ease-out duration-200"
                                                                       x-transition:enter-start="opacity-0 transform scale-95"
                                                                       x-transition:enter-end="opacity-100 transform scale-100">
                                                                        @if(($component->properties['show_variants'] ?? true) && $product->has_variants && count($product->variants) > 0)
                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Variant</label>
                                                                                <select x-model="variantId" @change="updatePrice()" 
                                                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg pulse-effect">
                                                                                    <option value="">-- Select Variant --</option>
                                                                                    <template x-for="variant in variants" :key="variant.id">
                                                                                        <option :value="variant.id" x-text="
                                                                                            Object.entries(variant.attribute_values)
                                                                                                .map(([key, value]) => key + ': ' + value)
                                                                                                .join(', ') + 
                                                                                            (variant.price_adjustment != 0 ? 
                                                                                                ' (' + (variant.price_adjustment > 0 ? '+' : '') + 
                                                                                                'Rp ' + variant.price_adjustment.toLocaleString('id-ID') + ')' : '')
                                                                                        "></option>
                                                                                    </template>
                                                                                </select>
                                                                            </div>
                                                                        @endif
                                                                        
                                                                        @if($component->properties['allow_quantity'] ?? true)
                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                                                                <div class="flex items-center">
                                                                                    <button type="button" @click="quantity > 1 ? quantity-- : quantity; updateSelection()"
                                                                                        class="rounded-l-md border border-gray-300 px-3 py-1 bg-gray-50 text-gray-500 hover:bg-gray-100">
                                                                                        -
                                                                                    </button>
                                                                                    <input type="number" x-model="quantity" min="1" @change="updateSelection()"
                                                                                        class="block w-20 border-y border-gray-300 py-1 text-center focus:outline-none focus:ring-0 focus:border-gray-300 text-sm"
                                                                                        readonly>
                                                                                    <button type="button" @click="quantity++; updateSelection()"
                                                                                        class="rounded-r-md border border-gray-300 px-3 py-1 bg-gray-50 text-gray-500 hover:bg-gray-100">
                                                                                        +
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        
                                                                        @if($component->properties['show_prices'] ?? true)
                                                                            <div class="pt-2">
                                                                                <p class="text-sm font-medium text-gray-700">Subtotal: Rp <span x-text="(price * quantity).toLocaleString('id-ID')"></span></p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="mt-2 text-sm text-gray-500">No products available for selection.</p>
                                        @endif
                                    </fieldset>
                                    @break
                                
                                @default
                                    <div class="text-sm text-red-500">Unsupported component type: {{ $component->type }}</div>
                            @endswitch
                        </div>
                    @endforeach
                    
                    <!-- Navigation buttons for multi-page forms -->
                    <div class="mt-8 flex justify-between">
                        <button 
                            type="button" 
                            x-show="currentPage > 1"
                            @click="prevPage()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Previous
                        </button>
                        
                        <div>
                            <span x-show="currentPage < totalPages">
                                <button 
                                    type="button" 
                                    @click="nextPage()"
                                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                                >
                                    Next
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                            
                            <span x-show="currentPage == totalPages">
                                <button 
                                    type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                                >
                                    Submit
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
        
        <!-- Brand footer -->
        <div class="text-center mt-6 text-xs text-gray-500">
            <p>Powered by Invoice Automation System</p>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            // Fix for product variant handling to ensure proper null values
            document.querySelectorAll('input[type="hidden"][name^="field_"][name$="[selection]"]').forEach(input => {
                if (!input.value && input.value !== '0') {
                    input.value = '';
                }
            });
        });
    </script>
</body>
</html>