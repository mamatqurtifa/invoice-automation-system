<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Financial Report: {{ $project->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.exports.orders', ['project' => $project->id, 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Orders
                </a>
                <a href="{{ route('projects.exports.payments', ['project' => $project->id, 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Payments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Project
                </a>
            </div>

            <!-- Date Range Filter -->
            <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
                <form method="GET" action="{{ route('projects.reports.financial', $project) }}" class="flex flex-wrap items-end space-x-4">
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Report Period</label>
                        <select id="period" name="period" onchange="toggleCustomDateRange()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Last Year</option>
                            <option value="all_time" {{ $period === 'all_time' ? 'selected' : '' }}>All Time</option>
                            <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                        </select>
                    </div>
                    
                    <div id="custom-date-range" class="flex space-x-4 {{ $period !== 'custom' ? 'hidden' : '' }}">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" 
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" 
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Apply Filter
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="text-sm font-medium text-gray-500 mb-1">Total Orders</div>
                    <div class="text-2xl font-bold">{{ $totalOrders }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="text-sm font-medium text-gray-500 mb-1">Total Sales Value</div>
                    <div class="text-2xl font-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-gray-500">All orders in period</div>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="text-sm font-medium text-gray-500 mb-1">Received Revenue</div>
                    <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-gray-500">Verified payments in period</div>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="text-sm font-medium text-gray-500 mb-1">Pending Revenue</div>
                    <div class="text-2xl font-bold text-red-600">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-gray-500">Unpaid amount in period</div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Revenue Trend</h3>
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                
                <!-- Orders by Status -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Orders by Status</h3>
                    
                    <div class="space-y-4">
                        @foreach($ordersByStatus as $status => $ordersInStatus)
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm font-medium mr-3">{{ $ordersInStatus->count() }}</div>
                                    <div class="w-20 bg-gray-200 rounded-full h-2.5">
                                        @php $percentage = ($ordersInStatus->count() / max(1, $totalOrders)) * 100; @endphp
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Recent Payments -->
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Payments</h3>
                    
                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payments->take(10) as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('projects.orders.show', [$project, $payment->order]) }}" class="text-blue-600 hover:underline">
                                                        {{ $payment->order->order_number }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->order->guest_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->payment_method_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ ucfirst($payment->type) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->verified_at->format('d M Y H:i') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($payments->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Showing 10 of {{ $payments->count() }} payments. Export all using the button above.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No payments found in the selected period</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleCustomDateRange() {
            const periodSelect = document.getElementById('period');
            const customDateRange = document.getElementById('custom-date-range');
            
            if (periodSelect.value === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const dates = @json($dateRange);
            const data = @json($chartData);
            
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Daily Revenue (Rp)',
                        data: data,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        pointRadius: 3,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format y-axis labels as currency
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                // Format tooltip values as currency
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>