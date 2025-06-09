@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Title --}}
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary"><i class="bi bi-tags me-2"></i>Brand Management</h2>
                {{-- <p class="text-muted">Add and manage chemical brands easily</p> --}}
            </div>

            {{-- Success Alert --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Brand Form --}}
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title text-secondary fw-semibold mb-3">Add a New Brand</h5>
                    <form action="{{ route('brands.store') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter brand name" required>
                            <button class="btn btn-primary px-4" type="submit">Add</button>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </form>
                </div>
            </div>

            {{-- Brand List --}}
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h5 class="card-title text-secondary fw-semibold mb-3">Existing Brands</h5>
                    @if($brands->count())
                        <ul class="list-group list-group-flush">
                            @foreach ($brands as $brand)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 border-bottom">
                                    <span class="fw-medium text-dark">{{ $brand->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No brands have been added yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
