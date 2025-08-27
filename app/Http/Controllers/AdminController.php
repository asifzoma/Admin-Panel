<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Get company trend data using SQLite compatible date function
        $companiesTrend = Company::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month');

        // Get employee trend data
        $employeesTrend = Employee::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month');

        // Get activity log trend
        $activityLogTrend = ActivityLog::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month');

        // Get recent activities
        $recentActivities = ActivityLog::with(['user', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        // Get total counts
        $totalCompanies = Company::count();
        $totalEmployees = Employee::count();
        $totalActivities = ActivityLog::count();

        return view('admin.dashboard', compact(
            'companiesTrend',
            'employeesTrend',
            'activityLogTrend',
            'recentActivities',
            'totalCompanies',
            'totalEmployees',
            'totalActivities'
        ));
    }
}
