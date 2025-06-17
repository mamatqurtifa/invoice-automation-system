<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Invoice #{{ $invoice->invoice_number }} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
                        <p class="text-sm text-gray-600">{{ $invoice->invoice_number }}</p>
                    </div>
                    
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                           ($invoice->status === 'cancelled' || $invoice->status === 'void' ? 'bg-red-100 text-red-800' : 
                           'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
            </div>
        </header>

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="text-gray-600 font-medium mb-2">Invoice Details</h3>
                                <table class="min-w-full text-sm">
                                    <tr>
                                        <td class="py-1 pr-4 font-medium">Invoice Number:</td>
                                        <td>{{ $invoice->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-4 font-medium">Order Number:</td>
                                        <td>{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-4 font-medium">Date:</td>
                                        <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-4 font-medium">Due Date:</td>
                                        <td>{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div>
                                <h3 class="text-gray-600 font-medium mb-2">Customer Information</h3>
                                <table class="min-w-full text-sm">
                                    <tr>
                                        <td class="py-1 pr-4 font-medium">Name:</td>
                                        <td>{{ $order->guest_name }}</td>
                                    </tr>
                                    @if($order->guest_email)
                                        <tr>
                                            <td class="py-1 pr-4 font-medium">Email:</td>
                                            <td>{{ $order->guest_email }}</td>
                                        </tr>
                                    @endif
                                    @if($order->guest_phone)
                                        <tr>
                                            <td class="py-1 pr-4 font-medium">Phone:</td>
                                            <td>{{ $order->guest_phone }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-8 mb-6">
                            <h3 class="text-gray-600 font-medium mb-4">Order Items</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                                    @if($item->variant_details)
                                                        <div class="text-xs text-gray-500">
                                                            @foreach($item->variant_details as $attribute => $value)
                                                                {{ $attribute }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50">
                                            <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                            <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h3 class="text-gray-600 font-medium mb-4">Payment Information</h3>
                            
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="font-medium">Amount Paid:</p>
                                        <p>Rp {{ number_format($order->amount_paid, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium">Remaining Amount:</p>
                                        <p>Rp {{ number_format($order->getRemainingAmount(), 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium">Payment Status:</p>
                                        <p>
                                            @if($order->isPaid())
                                                <span class="text-green-600 font-medium">Fully Paid</span>
                                            @else
                                                <span class="text-red-600 font-medium">Partially Paid ({{ number_format($order->getPaymentPercentage(), 1) }}%)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-center">
                            @if($invoice->pdf_path)
                                <a href="{{ Storage::url($invoice->pdf_path) }}" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" download>
                                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Invoice PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($invoice->pdf_path)
                    <div class="mt-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Preview</h3>
                                <div class="mt-4 border rounded-md h-[800px]">
                                    <embed src="{{ Storage::url($invoice->pdf_path) }}" type="application/pdf" width="100%" height="100%">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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