<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        $companyCount = \App\Models\Company::count();
        $employeeCount = \App\Models\Employee::count();

        // Company creation trend (last 12 months)
        $companyTrends = \App\Models\Company::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Employee hire trend (last 12 months)
        $employeeTrends = \App\Models\Employee::selectRaw('DATE_FORMAT(hire_date, "%Y-%m") as month, COUNT(*) as count')
            ->whereNotNull('hire_date')
            ->where('hire_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Activity log trend (last 12 months)
        $activityTrends = \App\Models\ActivityLog::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Most and least recently updated companies
        $mostRecentCompanies = \App\Models\Company::orderBy('updated_at', 'desc')->take(5)->get();
        $leastRecentCompanies = \App\Models\Company::orderBy('updated_at', 'asc')->take(5)->get();

        // Top 5 companies with most employees
        $largestCompanies = \App\Models\Company::withCount('employees')
            ->orderBy('employees_count', 'desc')
            ->take(5)
            ->get();

        // Login frequency (last 30 days)
        $loginFrequency = \App\Models\ActivityLog::where('action', 'login')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('count', 'day');

        // Last 10 recent actions
        $recentActions = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('companyCount', 'employeeCount', 'companyTrends', 'employeeTrends', 'activityTrends', 'mostRecentCompanies', 'leastRecentCompanies', 'largestCompanies', 'loginFrequency', 'recentActions'));
    }
} 