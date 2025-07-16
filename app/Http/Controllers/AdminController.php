<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        $companyCount = \App\Models\Company::count();
        $employeeCount = \App\Models\Employee::count();
        return view('admin.dashboard', compact('companyCount', 'employeeCount'));
    }
} 