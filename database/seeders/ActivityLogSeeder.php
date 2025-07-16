<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $companies = Company::all();
        $employees = Employee::all();
        $actions = ['login', 'create', 'update', 'delete'];
        $subjects = [
            ['type' => 'Company', 'models' => $companies],
            ['type' => 'Employee', 'models' => $employees],
        ];
        // Seed logins
        foreach ($users as $user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'subject_type' => 'User',
                'subject_id' => $user->id,
                'description' => $user->name . ' logged in',
                'created_at' => $user->last_login_at ?? now(),
                'updated_at' => $user->last_login_at ?? now(),
            ]);
        }
        // Seed company and employee actions
        foreach ($subjects as $subject) {
            foreach ($subject['models'] as $model) {
                foreach ($actions as $action) {
                    ActivityLog::create([
                        'user_id' => $users->random()->id,
                        'action' => $action,
                        'subject_type' => $subject['type'],
                        'subject_id' => $model->id,
                        'description' => $action . ' ' . strtolower($subject['type']) . ' #' . $model->id,
                        'created_at' => Carbon::parse($model->created_at)->addDays(rand(0, 365)),
                        'updated_at' => Carbon::parse($model->created_at)->addDays(rand(0, 365)),
                    ]);
                }
            }
        }
    }
}
