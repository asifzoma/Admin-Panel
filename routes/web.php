<?php

use App\Http\Controllers\{
    AdminController,
    Auth\LoginController,
    CompanyController,
    EmployeeController
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes (protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);
});

// Redirect root to admin dashboard if authenticated, otherwise to login
Route::get('/', function () {
    if (Auth::check() && Auth::user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});

Route::get('/home', function () {
    return view('home');
})->name('home');
