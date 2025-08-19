@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb my-3">
    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
    <li class="breadcrumb-item active" aria-current="page">Show</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $company->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <x-company-logo :company="$company" size="150px" class="mb-3" />
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Email:</strong> {{ $company->email }}</li>
                        <li class="list-group-item"><strong>Address:</strong> {{ $company->address }}</li>
                        <li class="list-group-item"><strong>Website:</strong>
                            @if($company->website)
                                <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                            @else
                                <span class="text-muted">No website</span>
                            @endif
                        </li>
                    </ul>
                    <div class="mt-3">
                        <a href="{{ route('companies.edit', $company) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>

            <!-- Employees List -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Employees</h5>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">Add Employee</a>
                </div>
                <div class="card-body">
                    @if($employees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Hire Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->full_name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->phone }}</td>
                                            <td>{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $employees->links() }}
                        </div>
                    @else
                        <p class="text-muted mb-0">No employees found for this company.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 