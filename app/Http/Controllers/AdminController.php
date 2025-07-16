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

        return view('admin.dashboard', compact('companyCount', 'employeeCount', 'companyTrends', 'employeeTrends'));
    }
} 