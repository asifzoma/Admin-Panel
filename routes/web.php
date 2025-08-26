<?php

use App\Http\Controllers\{
    AdminController,
    Auth\LoginController,
    CompanyController,
    EmployeeController
};
use Illuminate\Support\Facades\{Auth, Route, DB, Storage};
use Illuminate\Http\Request;

// Authentication routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Lightweight environment health check (protected by key)
Route::get('/health', function (Request $request) {
    $key = $request->query('key');
    if ($key !== env('APP_HEALTH_KEY')) {
        abort(404);
    }

    $checks = [];

    // PHP version
    $checks['php_version'] = PHP_VERSION;
    $checks['php_ok'] = version_compare(PHP_VERSION, '8.1.0', '>=');

    // Extensions
    $extensions = [
        'pdo', 'pdo_mysql', 'pdo_sqlite', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json'
    ];
    $checks['extensions'] = collect($extensions)->mapWithKeys(function ($ext) {
        return [$ext => extension_loaded($ext)];
    });

    // Storage permissions
    $paths = [
        'storage' => storage_path(),
        'logs' => storage_path('logs'),
        'framework_cache' => storage_path('framework/cache'),
        'framework_sessions' => storage_path('framework/sessions'),
        'framework_views' => storage_path('framework/views'),
        'bootstrap_cache' => base_path('bootstrap/cache'),
    ];
    $checks['writable'] = collect($paths)->mapWithKeys(function ($path, $name) {
        return [$name => is_dir($path) && is_writable($path)];
    });

    // Public storage symlink
    $publicStorage = public_path('storage');
    $checks['storage_link'] = [
        'exists' => file_exists($publicStorage),
        'is_link' => is_link($publicStorage),
        'target' => storage_path('app/public'),
    ];

    // Database connectivity
    try {
        DB::connection()->getPdo();
        $checks['database'] = [
            'connection' => config('database.default'),
            'connected' => true,
        ];
    } catch (\Throwable $e) {
        $checks['database'] = [
            'connection' => config('database.default'),
            'connected' => false,
            'error' => $e->getMessage(),
        ];
    }

    return response()->json([
        'ok' => true,
        'checks' => $checks,
    ]);
});

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
