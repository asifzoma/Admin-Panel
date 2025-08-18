@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb my-3">
    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">Edit Company</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $company->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $company->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $company->address) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $company->website) }}">
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <div class="mb-2">
                                <x-company-logo :company="$company" size="100px" />
                            </div>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <small class="form-text text-muted">Leave blank to keep current logo.</small>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Update Company</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 