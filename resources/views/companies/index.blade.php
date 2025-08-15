@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb my-3">
    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Companies</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Companies</h4>
                    <a href="{{ route('companies.create') }}" class="btn btn-primary">Add New Company</a>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('companies.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email"
                                   value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                            @if(request('search'))
                                <a href="{{ route('companies.index') }}" class="btn btn-outline-danger">Clear</a>
                            @endif
                        </div>
                    </form>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($companies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($companies as $company)
                                        <tr>
                                            <td>
                                                @if($company->logo && Storage::disk('public')->exists($company->logo))
                                                    <img src="{{ asset('storage/' . $company->logo) }}" 
                                                         alt="{{ $company->name }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         onerror="this.onerror=null; this.src='{{ asset('images/default-company.png') }}'; this.classList.remove('img-thumbnail');">
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ $company->email }}</td>
                                            <td>
                                                @if($company->website)
                                                    <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                                                @else
                                                    <span class="text-muted">No website</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('companies.show', $company) }}" 
                                                       class="btn btn-sm btn-info">View</a>
                                                    <a href="{{ route('companies.edit', $company) }}" 
                                                       class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('companies.destroy', $company) }}" 
                                                          method="POST" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this company?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $companies->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <h5 class="text-muted">No companies found</h5>
                            <p>Start by adding your first company.</p>
                            <a href="{{ route('companies.create') }}" class="btn btn-primary">Add Company</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 