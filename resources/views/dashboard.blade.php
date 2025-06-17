<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Projects Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total Projects</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProjects }}</div>
                    <div class="mt-1 text-sm text-gray-500">{{ $activeProjects }} active projects</div>
                </div>
                
                <!-- Sales Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total Sales</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                    <div class="mt-1 text-sm text-gray-500">All time</div>
                </div>
                
                <!-- Payments Received Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Payments Received</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($totalPaymentsReceived, 0, ',', '.') }}</div>
                    <div class="mt-1 text-sm text-gray-500">
                        {{ $totalSales > 0 ? number_format(($totalPaymentsReceived / $totalSales) * 100, 1) : 0 }}% of total sales
                    </div>
                </div>
                
                <!-- Outstanding Payments Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Outstanding Payments</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($totalSales - $totalPaymentsReceived, 0, ',', '.') }}</div>
                    <div class="mt-1 text-sm text-gray-500">Pending collection</div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Sales ({{ date('Y') }})</h3>
                    <div style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Orders</h3>
                    
                    @if(count($recentOrders) > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="border-b pb-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium">{{ $order->guest_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <a href="{{ route('projects.orders.show', [$order->project_id, $order]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                            View Order
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">View all orders</a>
                        </div>
                    @else
                        <p class="text-gray-500">No recent orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const months = @json($chartMonths);
            const data = @json($chartData);
            
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Monthly Sales (Rp)',
                        data: data,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
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