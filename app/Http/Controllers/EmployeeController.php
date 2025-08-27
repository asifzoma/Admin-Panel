<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $employees = Employee::with('company')
            ->latest()
            ->paginate(10); // Changed from get() to paginate()

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('employees.create', compact('companies'));
    }

    public function store(EmployeeStoreRequest $request)
    {
        $employee = Employee::create($request->validated());
        
        $this->logActivity('Created employee: ' . $employee->first_name . ' ' . $employee->last_name);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $companies = Company::all();
        return view('employees.edit', compact('employee', 'companies'));
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
        
        $this->logActivity('Updated employee: ' . $employee->first_name . ' ' . $employee->last_name);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            $name = $employee->first_name . ' ' . $employee->last_name;
            $employee->delete();
            $this->logActivity('Deleted employee: ' . $name);
        });

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
