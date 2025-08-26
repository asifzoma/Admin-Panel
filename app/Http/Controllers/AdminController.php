<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $companyCount = Company::count();
        $employeeCount = Employee::count();

        // Company creation trend (last 12 months)
        $companyTrends = Company::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Employee hire trend (last 12 months)
        $employeeTrends = Employee::select(
            DB::raw('strftime("%Y-%m", hire_date) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereNotNull('hire_date')
            ->where('hire_date', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Activity log trend (last 12 months)
        $activityTrends = ActivityLog::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Most and least recently updated companies
        $mostRecentCompanies = Company::latest('updated_at')->take(5)->get();
        $leastRecentCompanies = Company::oldest('updated_at')->take(5)->get();

        // Top 5 companies with most employees
        $largestCompanies = Company::withCount('employees')
            ->orderByDesc('employees_count')
            ->take(5)
            ->get();

        // Login frequency (last 30 days)
        $loginFrequency = ActivityLog::where('action', 'login')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('date(created_at) as day'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('count', 'day');

        // Last 10 recent actions
        $recentActions = ActivityLog::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'companyCount',
            'employeeCount',
            'companyTrends',
            'employeeTrends',
            'activityTrends',
            'mostRecentCompanies',
            'leastRecentCompanies',
            'largestCompanies',
            'loginFrequency',
            'recentActions'
        ));
    }
}
