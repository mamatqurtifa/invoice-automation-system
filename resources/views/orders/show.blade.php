<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order: {{ $order->order_number }}
            </h2>
            <div class="flex space-x-2">
                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                    <form action="{{ route('projects.orders.update', [$project, $order]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            Mark as Completed
                        </button>
                    </form>
                @endif
                
                @if($order->status !== 'cancelled')
                    <form action="{{ route('projects.orders.update', [$project, $order]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" 
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>
                @endif
                
                @if($order->invoices->count() === 0)
                    <form action="{{ route('projects.orders.invoices.generate', [$project, $order]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="commercial">
                        <button type="submit" class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Generate Invoice
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.orders.index', $project) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Order Information</h3>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Order Number:</p>
                                    <p class="text-gray-900">{{ $order->order_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Order Date:</p>
                                    <p class="text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Customer Name:</p>
                                    <p class="text-gray-900">{{ $order->guest_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Contact:</p>
                                    <p class="text-gray-900">
                                        @if($order->guest_email)
                                            {{ $order->guest_email }}<br>
                                        @endif
                                        @if($order->guest_phone)
                                            {{ $order->guest_phone }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($order->notes)
                                <div class="mb-6">
                                    <p class="text-sm font-medium text-gray-500">Notes:</p>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-md mt-1">{{ $order->notes }}</p>
                                </div>
                            @endif
                            
                            <div class="mt-8">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Order Items</h4>
                                
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
                                                        <div class="text-sm font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-gray-50">
                                                <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                                <td class="px-6 py-4 font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Invoices -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Invoices</h3>
                                
                                <div class="flex space-x-2">
                                    @if($order->status !== 'cancelled')
                                        <div class="relative">
                                            <button id="invoiceDropdownButton" class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 inline-flex items-center" onclick="toggleDropdown()">
                                                Generate Invoice
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div id="invoiceDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                                <div class="py-1">
                                                    <form action="{{ route('projects.orders.invoices.generate', [$project, $order]) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="type" value="commercial">
                                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Commercial Invoice</button>
                                                    </form>
                                                    <form action="{{ route('projects.orders.invoices.generate', [$project, $order]) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="type" value="proforma">
                                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Proforma Invoice</button>
                                                    </form>
                                                    <form action="{{ route('projects.orders.invoices.generate', [$project, $order]) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="type" value="receipt">
                                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Receipt</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($order->invoices->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Number</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($order->invoices as $invoice)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ ucfirst($invoice->type) }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $invoice->created_at->format('d M Y') }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                               ($invoice->status === 'cancelled' || $invoice->status === 'void' ? 'bg-red-100 text-red-800' : 
                                                               'bg-yellow-100 text-yellow-800') }}">
                                                            {{ ucfirst($invoice->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('projects.invoices.show', [$project, $invoice]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                        <a href="{{ route('projects.invoices.download', [$project, $invoice]) }}" class="text-green-600 hover:text-green-900" target="_blank">Download</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No invoices generated yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Summary</h3>
                            
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium">Total Amount:</span>
                                        <span class="font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium">Amount Paid:</span>
                                        <span class="text-green-600 font-medium">Rp {{ number_format($order->amount_paid, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium">Remaining:</span>
                                        <span class="text-red-600 font-medium">Rp {{ number_format($order->getRemainingAmount(), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $order->getPaymentPercentage() }}%"></div>
                                        </div>
                                        <div class="mt-1 text-right text-xs text-gray-500">
                                            {{ number_format($order->getPaymentPercentage(), 1) }}% paid
                                        </div>
                                    </div>
                                </div>
                                
                                @if($order->status !== 'cancelled')
                                    <div class="border border-dashed border-gray-300 p-4 rounded-md">
                                        <h4 class="font-medium mb-3">Record a Payment</h4>
                                        <form action="{{ route('projects.orders.payments.store', [$project, $order]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                                <select name="payment_method_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    @foreach($paymentMethods as $method)
                                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                                    </div>
                                                    <input type="number" name="amount" min="0.01" step="0.01" value="{{ $order->getRemainingAmount() }}" required
                                                        class="block w-full pl-10 pr-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                                                <select name="type" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    @if($order->amount_paid > 0)
                                                        <option value="installment">Installment</option>
                                                    @else
                                                        <option value="{{ $order->total_amount > $order->getRemainingAmount() ? 'full_payment' : 'down_payment' }}">
                                                            {{ $order->getRemainingAmount() < $order->total_amount ? 'Full Payment' : 'Down Payment' }}
                                                        </option>
                                                    @endif
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Proof Image</label>
                                                <input type="file" name="proof_image" accept="image/*" 
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Paid Date</label>
                                                <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                                <select name="status" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <option value="pending">Pending (Needs Verification)</option>
                                                    <option value="verified">Verified</option>
                                                </select>
                                            </div>
                                            
                                            <div class="text-right">
                                                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                    Record Payment
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment History -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
                            
                            @if($order->payments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($order->payments as $payment)
                                        <div class="border rounded-md p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="text-sm font-medium mb-1">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $payment->payment_method_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $payment->paid_at ? $payment->paid_at->format('d M Y') : 'N/A' }}</div>
                                                </div>
                                                <div>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $payment->status === 'verified' ? 'bg-green-100 text-green-800' : 
                                                           ($payment->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                           'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ ucfirst($payment->type) }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($payment->proof_image)
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($payment->proof_image) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs">
                                                        View Payment Proof
                                                    </a>
                                                </div>
                                            @endif
                                            
                                            @if($payment->status === 'pending')
                                                <div class="mt-3 flex space-x-2">
                                                    <form action="{{ route('projects.orders.payments.verify', [$project, $order, $payment]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="px-2 py-1 bg-green-500 text-white rounded text-xs">
                                                            Verify
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('projects.orders.payments.reject', [$project, $order, $payment]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded text-xs">
                                                            Reject
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('projects.orders.payments.destroy', [$project, $order, $payment]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-2 py-1 bg-gray-500 text-white rounded text-xs" 
                                                                onclick="return confirm('Are you sure you want to delete this payment?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No payment records found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleDropdown() {
            document.getElementById('invoiceDropdown').classList.toggle('hidden');
        }
        
        // Close the dropdown when clicking outside of it
        window.addEventListener('click', function(e) {
            if (!document.getElementById('invoiceDropdownButton').contains(e.target)) {
                document.getElementById('invoiceDropdown').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>