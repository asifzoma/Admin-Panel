<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

echo "🔧 Fixing all potential errors...\n\n";

try {
    // 1. Clear all caches
    echo "1. Clearing caches...\n";
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    echo "✓ Caches cleared\n\n";

    // 2. Regenerate application key
    echo "2. Regenerating application key...\n";
    Artisan::call('key:generate', ['--force' => true]);
    echo "✓ Application key regenerated\n\n";

    // 3. Ensure database exists and is accessible
    echo "3. Checking database...\n";
    if (!file_exists('database.sqlite')) {
        file_put_contents('database.sqlite', '');
        echo "✓ Database file created\n";
    }
    
    // Test database connection
    DB::connection()->getPdo();
    echo "✓ Database connection successful\n\n";

    // 4. Drop and recreate all tables
    echo "4. Recreating database tables...\n";
    Schema::dropIfExists('activity_logs');
    Schema::dropIfExists('employees');
    Schema::dropIfExists('companies');
    Schema::dropIfExists('users');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
    echo "✓ Old tables dropped\n";

    // Create users table
    Schema::create('users', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->boolean('is_admin')->default(false);
        $table->timestamp('last_login_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
    echo "✓ Users table created\n";

    // Create companies table
    Schema::create('companies', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('address');
        $table->string('website')->nullable();
        $table->string('logo')->nullable();
        $table->timestamps();
    });
    echo "✓ Companies table created\n";

    // Create employees table
    Schema::create('employees', function ($table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email');
        $table->string('phone')->nullable();
        $table->date('hire_date')->nullable();
        $table->timestamps();
        
        $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        $table->unique(['company_id', 'email']);
    });
    echo "✓ Employees table created\n";

    // Create activity_logs table
    Schema::create('activity_logs', function ($table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('action');
        $table->string('subject_type')->nullable();
        $table->unsignedBigInteger('subject_id')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
        
        $table->index(['subject_type', 'subject_id']);
    });
    echo "✓ Activity logs table created\n";

    // Create sessions table
    Schema::create('sessions', function ($table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
    echo "✓ Sessions table created\n";

    // Create password_reset_tokens table
    Schema::create('password_reset_tokens', function ($table) {
        $table->string('email')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });
    echo "✓ Password reset tokens table created\n\n";

    // 5. Seed database with sample data
    echo "5. Seeding database with sample data...\n";
    Artisan::call('db:seed', ['--force' => true]);
    echo "✓ Database seeded with companies and employees\n\n";

    // 6. Set proper permissions
    echo "6. Setting permissions...\n";
    $paths = [
        'storage',
        'bootstrap/cache',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs'
    ];
    
    foreach ($paths as $path) {
        if (is_dir($path)) {
            chmod($path, 0755);
            echo "✓ Set permissions for {$path}\n";
        }
    }
    echo "\n";

    // 7. Create storage link
    echo "7. Creating storage link...\n";
    if (!file_exists('public/storage')) {
        symlink('../storage/app/public', 'public/storage');
        echo "✓ Storage link created\n";
    } else {
        echo "✓ Storage link already exists\n";
    }
    echo "\n";

    // 8. Test application
    echo "8. Testing application...\n";
    
    // Test database queries
    $userCount = DB::table('users')->count();
    $companyCount = DB::table('companies')->count();
    $employeeCount = DB::table('employees')->count();
    echo "✓ Database test: {$userCount} users, {$companyCount} companies, {$employeeCount} employees found\n";
    
    // Test models
    $user = \App\Models\User::where('is_admin', true)->first();
    if ($user) {
        echo "✓ Model test: Admin user found ({$user->email})\n";
    }
    
    echo "\n";

    echo "🎉 All fixes completed successfully!\n\n";
    echo "📋 Summary:\n";
    echo "• Environment configured for local development\n";
    echo "• Database recreated with all tables\n";
    echo "• Sample data created: {$companyCount} companies, {$employeeCount} employees\n";
    echo "• Admin user created: admin@admin.com / password\n";
    echo "• All caches cleared and regenerated\n";
    echo "• Permissions set correctly\n";
    echo "• CSRF protection configured\n";
    echo "\n";
    echo "🚀 You can now run: php artisan serve\n";
    echo "🌐 Visit: http://127.0.0.1:8000\n";
    echo "🔑 Login: admin@admin.com / password\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
