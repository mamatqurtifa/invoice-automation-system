<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get total projects
        $totalProjects = $user->projects()->count();
        
        // Get active projects
        $activeProjects = $user->projects()
            ->where('is_active', true)
            ->count();
        
        // Get all projects for the user
        $projects = $user->projects;
        
        // Get all order IDs for all projects
        $orderIds = Order::whereIn('project_id', $projects->pluck('id'))->pluck('id');
        
        // Calculate total sales
        $totalSales = Order::whereIn('project_id', $projects->pluck('id'))
            ->sum('total_amount');
        
        // Calculate total payments received
        $totalPaymentsReceived = Order::whereIn('project_id', $projects->pluck('id'))
            ->sum('amount_paid');
        
        // Get recent orders
        $recentOrders = Order::whereIn('project_id', $projects->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get monthly sales chart data for the current year
        $monthlySales = Order::whereIn('project_id', $projects->pluck('id'))
            ->whereYear('created_at', Carbon::now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->get();
        
        // Format chart data
        $chartMonths = [];
        $chartData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $chartMonths[] = Carbon::createFromDate(null, $i, 1)->format('M');
            $monthData = $monthlySales->firstWhere('month', $i);
            $chartData[] = $monthData ? round($monthData->total, 2) : 0;
        }
        
        return view('dashboard', compact(
            'totalProjects',
            'activeProjects',
            'totalSales',
            'totalPaymentsReceived',
            'recentOrders',
            'chartMonths',
            'chartData'
        ));
    }
}