<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // Create 10 companies
        Company::factory(10)->create()->each(function ($company) {
            // Create 3-8 employees for each company
            Employee::factory(rand(3, 8))->create([
                'company_id' => $company->id
            ]);
        });

        // Create some additional employees for random companies
        $companies = Company::all();
        for ($i = 0; $i < 15; $i++) {
            Employee::factory()->create([
                'company_id' => $companies->random()->id
            ]);
        }
    }
}
