@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Employee Details</span>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-primary">Edit</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>First Name:</strong> {{ $employee->first_name }}</li>
                        <li class="list-group-item"><strong>Last Name:</strong> {{ $employee->last_name }}</li>
                        <li class="list-group-item"><strong>Company:</strong> {{ $employee->company->name ?? '-' }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $employee->email }}</li>
                        <li class="list-group-item"><strong>Phone:</strong> {{ $employee->phone }}</li>
                    </ul>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 