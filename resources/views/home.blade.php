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