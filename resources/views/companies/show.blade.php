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
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="img-thumbnail mb-3" style="width:100px; height:100px; object-fit:cover;">
                    @endif
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
        </div>
    </div>
</div>
@endsection 