<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        try {
            // Get basic counts
            $companyCount = Company::count();
            $employeeCount = Employee::count();

            // Get company trends (last 12 months)
            $companyTrends = collect();
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = Company::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $companyTrends->put($date->format('M Y'), $count);
            }

            // Get employee trends (last 12 months)
            $employeeTrends = collect();
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = Employee::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $employeeTrends->put($date->format('M Y'), $count);
            }

            // Get activity trends (last 12 months)
            $activityTrends = collect();
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = ActivityLog::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $activityTrends->put($date->format('M Y'), $count);
            }

            // Get most/least recently updated companies
            $mostRecentCompanies = Company::orderBy('updated_at', 'desc')->limit(5)->get();
            $leastRecentCompanies = Company::orderBy('updated_at', 'asc')->limit(5)->get();

            // Get largest companies by employee count
            $largestCompanies = Company::withCount('employees')
                ->orderBy('employees_count', 'desc')
                ->limit(5)
                ->get();

            // Get login frequency (last 30 days)
            $loginFrequency = collect();
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = User::whereNotNull('last_login_at')
                    ->whereDate('last_login_at', $date->toDateString())
                    ->count();
                $loginFrequency->put($date->format('M d'), $count);
            }

            return view('admin.dashboard', compact(
                'companyCount',
                'employeeCount',
                'companyTrends',
                'employeeTrends',
                'activityTrends',
                'mostRecentCompanies',
                'leastRecentCompanies',
                'largestCompanies',
                'loginFrequency'
            ));

        } catch (\Throwable $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            
            // Return dashboard with safe defaults
            return view('admin.dashboard', [
                'companyCount' => 0,
                'employeeCount' => 0,
                'companyTrends' => collect(),
                'employeeTrends' => collect(),
                'activityTrends' => collect(),
                'mostRecentCompanies' => collect(),
                'leastRecentCompanies' => collect(),
                'largestCompanies' => collect(),
                'loginFrequency' => collect(),
            ]);
        }
    }
}
