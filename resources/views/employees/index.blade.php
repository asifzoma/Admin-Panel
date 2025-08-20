@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb my-3">
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Employees</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Employees</h4>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Employee</a>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('employees.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email"
                                   value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary rounded-0 rounded-end" type="submit">Search</button>
                            @if(request('search'))
                                <a href="{{ route('employees.index') }}" class="btn btn-outline-danger">Clear</a>
                            @endif
                        </div>
                    </form>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <table class="table table-bordered table-striped mobile-table">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td data-label="First Name">{{ $employee->first_name }}</td>
                                    <td data-label="Last Name">{{ $employee->last_name }}</td>
                                    <td data-label="Company">{{ $employee->company->name ?? '-' }}</td>
                                    <td data-label="Email">{{ $employee->email }}</td>
                                    <td data-label="Phone">{{ $employee->phone }}</td>
                                    <td data-label="Actions" class="actions-cell">
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-info">Show</a>
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $employees->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 