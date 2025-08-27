<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Setting up database...\n";

try {
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

    // Create admin user
    DB::table('users')->insert([
        'name' => 'Administrator',
        'email' => 'admin@admin.com',
        'password' => bcrypt('password'),
        'is_admin' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✓ Admin user created\n";

    echo "\nDatabase setup complete!\n";
    echo "Login: admin@admin.com / password\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
