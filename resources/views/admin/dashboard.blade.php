@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <img src="{{ asset('images/admin-genie.png') }}" alt="Admin Genie" style="height:80px;">
            <p class="text-muted mt-3">
                <strong>Dashboard Analytics:</strong><br>
                Company growth and employee hire trends, admin activity over time, most and least recently updated companies, company size comparison, login frequency, and a log of recent actions are visualized below for actionable insights.
            </p>
            <nav class="my-3">
                <ul class="nav justify-content-center flex-wrap">
                    <li class="nav-item"><a class="nav-link btn btn-primary rounded-pill mx-1 mb-2 text-white" style="min-width: 150px;" href="#company-growth">Company Growth</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-success rounded-pill mx-1 mb-2 text-white" style="min-width: 150px;" href="#employee-hires">Employee Hires</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-info rounded-pill mx-1 mb-2 text-white" style="min-width: 150px;" href="#admin-activity">Admin Activity</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-warning rounded-pill mx-1 mb-2 text-dark" style="min-width: 150px;" href="#recently-updated">Recently Updated</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary rounded-pill mx-1 mb-2 text-white" style="min-width: 150px;" href="#company-size">Company Size</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-success rounded-pill mx-1 mb-2 text-white" style="min-width: 150px;" href="#login-frequency">Login Frequency</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-secondary rounded-pill mx-1 mb-2 text-white" style="min-width: 150px;" href="#recent-actions">Recent Actions</a></li>
                </ul>
            </nav>
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
    <div class="row mt-5" id="company-growth">
        <div class="col-12 col-xl-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Company Growth (Last 12 Months)</div>
                <div class="card-body">
                    <canvas id="companyTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 mb-4" id="employee-hires">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Employee Hires (Last 12 Months)</div>
                <div class="card-body">
                    <canvas id="employeeTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4" id="admin-activity">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Admin Activity (Last 12 Months)</div>
                <div class="card-body">
                    <canvas id="activityTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4" id="recently-updated">
        <div class="col-12 col-xl-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">Most Recently Updated Companies</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Name</th><th>Last Updated</th></tr></thead>
                        <tbody>
                        @foreach($mostRecentCompanies as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Least Recently Updated Companies</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Name</th><th>Last Updated</th></tr></thead>
                        <tbody>
                        @foreach($leastRecentCompanies as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4" id="company-size">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Top 5 Largest Companies (by Employees)</div>
                <div class="card-body">
                    <canvas id="largestCompaniesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4" id="login-frequency">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Login Frequency (Last 30 Days)</div>
                <div class="card-body">
                    <canvas id="loginFrequencyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
<script src="{{ secure_asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>
    const companyTrendCtx = document.getElementById('companyTrendChart').getContext('2d');
    const employeeTrendCtx = document.getElementById('employeeTrendChart').getContext('2d');
    const companyTrendData = {
        labels: {!! json_encode(array_keys($companyTrends->toArray())) !!},
        datasets: [{
            label: 'Companies Created',
            data: {!! json_encode(array_values($companyTrends->toArray())) !!},
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            fill: true,
            tension: 0.4
        }]
    };
    const employeeTrendData = {
        labels: {!! json_encode(array_keys($employeeTrends->toArray())) !!},
        datasets: [{
            label: 'Employees Hired',
            data: {!! json_encode(array_values($employeeTrends->toArray())) !!},
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
            fill: true,
            tension: 0.4
        }]
    };
    new Chart(companyTrendCtx, {
        type: 'line',
        data: companyTrendData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    new Chart(employeeTrendCtx, {
        type: 'line',
        data: employeeTrendData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    const activityTrendCtx = document.getElementById('activityTrendChart').getContext('2d');
    const activityTrendData = {
        labels: {!! json_encode(array_keys($activityTrends->toArray())) !!},
        datasets: [{
            label: 'Admin Actions',
            data: {!! json_encode(array_values($activityTrends->toArray())) !!},
            borderColor: '#36b9cc',
            backgroundColor: 'rgba(54, 185, 204, 0.1)',
            fill: true,
            tension: 0.4
        }]
    };
    new Chart(activityTrendCtx, {
        type: 'line',
        data: activityTrendData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    const largestCompaniesCtx = document.getElementById('largestCompaniesChart').getContext('2d');
    const largestCompaniesData = {
        labels: {!! json_encode($largestCompanies->pluck('name')) !!},
        datasets: [{
            label: 'Employees',
            data: {!! json_encode($largestCompanies->pluck('employees_count')) !!},
            backgroundColor: '#4e73df',
        }]
    };
    new Chart(largestCompaniesCtx, {
        type: 'bar',
        data: largestCompaniesData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    const loginFrequencyCtx = document.getElementById('loginFrequencyChart').getContext('2d');
    const loginFrequencyData = {
        labels: {!! json_encode(array_keys($loginFrequency->toArray())) !!},
        datasets: [{
            label: 'Logins',
            data: {!! json_encode(array_values($loginFrequency->toArray())) !!},
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
            fill: true,
            tension: 0.4
        }]
    };
    new Chart(loginFrequencyCtx, {
        type: 'line',
        data: loginFrequencyData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush 