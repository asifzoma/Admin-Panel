@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <img src="/images/admin-genie.png" alt="Admin Genie" style="height:80px;">
            <h1 class="display-5 fw-bold mt-3">Welcome to Admin Genie!</h1>
            <p class="lead">Manage companies and employees with ease. Add, edit, and organize your business data in one magical place.</p>
        </div>
    </div>
    <div class="row g-4 mb-4 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 text-center bg-light">
                <div class="card-body">
                    <h2 class="display-4 text-primary mb-2">{{ $companyCount }}</h2>
                    <p class="h5">Companies</p>
                    <a href="{{ route('companies.index') }}" class="btn btn-outline-primary mt-2">View Companies</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 text-center bg-light">
                <div class="card-body">
                    <h2 class="display-4 text-success mb-2">{{ $employeeCount }}</h2>
                    <p class="h5">Employees</p>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-success mt-2">View Employees</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-info text-center shadow-sm">
                <strong>What can you do here?</strong><br>
                <ul class="list-unstyled mt-2 mb-0">
                    <li>✨ Add, edit, and delete companies</li>
                    <li>✨ Manage employees for each company</li>
                    <li>✨ Upload company logos</li>
                    <li>✨ Search, paginate, and organize your data</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 