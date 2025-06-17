<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Invoice: {{ $invoice->invoice_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.invoices.download', [$project, $invoice]) }}" class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600" target="_blank">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
                
                @if($order->guest_phone)
                    <a href="{{ route('projects.invoices.share.whatsapp', [$project, $invoice]) }}" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" target="_blank">
                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm6.031 16.98c-.263.861-.964 1.57-1.976 1.829-1.008.258-2.02.433-3.106.334-1.086-.1-2.704-.634-3.874-1.193-1.17-.56-2.243-1.279-3.138-2.175-.895-.895-1.615-1.968-2.175-3.138-.559-1.17-1.092-2.788-1.193-3.874-.1-1.086.076-2.098.334-3.106C3.164 4.964 3.873 4.263 4.734 4c.216-.066.436-.025.606.117.17.142.84.695 1.104.912.264.217.467.593.35.934-.117.342-.497 1.118-.651 1.451-.154.333.05.648.184.833.133.184.518.734.785 1.1.267.368.71.885 1.085 1.168.375.283.817.133 1.084.034.268-.1.6-.25.85-.542.25-.292.208-.675.159-.9-.05-.225-.45-.8-.7-1.25-.25-.45.033-.675.25-.867.218-.192.568-.65.935-.867.367-.217.717-.275.867-.075.15.2.758 1 .867 1.325.108.325.158.683.05.958-.108.275-.525.592-.775.825-.25.233-1.534 1.042-2.159 1.117-.624.075-1.259-.042-1.792-.233-.534-.192-1.35-.634-2.142-1.208-.793-.575-2.025-1.775-2.9-2.895-.875-1.12-1.692-2.535-1.983-3.485-.292-.95-.267-2.184-.083-2.95.183-.767.7-1.667 1.167-1.983.466-.317 1.05-.517 1.4-.517.35 0 .566.05.766.317.2.267.483.5.516.867.033.367.025.642-.042.925-.066.283-.342.805-.466 1.158-.125.354.092.688.208.884.117.195.383.667.625.992.242.325.667.883 1.042 1.215.375.333.85.5 1.1.592.25.093.65.1.884-.117.233-.217.567-.642.767-1.025.2-.383.217-.917-.033-1.2-.25-.283-.625-.917-.917-1.4-.292-.483-.208-.917.025-1.175.233-.258.683-.758 1-1.142.317-.383.7-.733.967-1.033.267-.3.517-.267.8-.067.283.2.784.475 1.2.767.417.292.917.492 1.075.7.158.208.208.458.1.792-.108.333-.475 1.05-.883 1.5-.408.45-.95.633-1.433.75-.483.117-1.167.017-1.567-.1-.4-.117-.792-.292-1.042-.492-.25-.2-.425-.358-.625-.625-.2-.267-.371-.567-.517-.883-.146-.317-.425-.692-.692-1.05-.267-.358-.233-.542-.133-.792.1-.25.292-.433.583-.433.292 0 .583-.1.792-.292.208-.192.275-.425.35-.7.075-.275.008-.483-.058-.683-.067-.2-.733-1.767-1.025-2.433-.292-.667-.608-.6-.833-.6-.225 0-.483-.033-.75-.033s-.7.1-.95.333c-.25.233-.958.933-1.042 2.383-.083 1.45.75 2.867 1 3.333.25.467 2.917 5.275 7.533 7.583 4.617 2.308 4.617 1.608 5.45 1.542.833-.067 2.75-1.117 3-2.2.25-1.083.25-2 .167-2.2-.084-.2-.334-.333-.668-.467z"/>
                        </svg>
                        Share via WhatsApp
                    </a>
                @endif
                
                <form action="{{ route('projects.invoices.regenerate', [$project, $invoice]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Regenerate PDF
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.orders.show', [$project, $order]) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Order
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">INVOICE</h2>
                            <p class="text-gray-500">{{ strtoupper($invoice->type . ' INVOICE') }}</p>
                        </div>
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($invoice->status === 'cancelled' || $invoice->status === 'void' ? 'bg-red-100 text-red-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>
                    
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
                            
                            @if($order->payments->where('status', 'verified')->count() > 0)
                                <div class="mt-4">
                                    <p class="font-medium">Payment History:</p>
                                    <ul class="list-disc list-inside mt-2">
                                        @foreach($order->payments->where('status', 'verified') as $payment)
                                            <li>
                                                {{ $payment->created_at->format('d M Y') }} - 
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }} via {{ $payment->payment_method_name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
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
    </div>
</x-app-layout>