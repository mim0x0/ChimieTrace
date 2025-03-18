@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory</h2>

    <div class="card p-3 mt-3 bg-light">
        <div class="row">
            <!-- Chemical Image -->
            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <img src="{{ $inventory->image() }}" class="img-fluid" alt="Chemical Image">
            </div>
            <!-- Chemical Details -->
            <div class="col-md">
                <h4><strong>Chemical Name: </strong>{{ $inventory->chemical_name }}</h4>
                <p><strong>CAS Number:</strong> {{ $inventory->CAS_number }}</p>
                <p><strong>Serial Number:</strong> {{ $inventory->serial_number }}</p>
                <p><strong>Location:</strong> {{ $inventory->location }}</p>
                <p><strong>Quantity:</strong> {{ $inventory->quantity }}</p>
                <p><strong>SKU:</strong> {{ $inventory->SKU }}</p>
                <p><strong>Acquired Date:</strong> {{ $inventory->acq_at }}</p>
                <p><strong>Expiry Date:</strong> {{ $inventory->exp_at }}</p>
                <a href="{{ $inventory->SDS() }}" target="_blank" class="btn btn-dark btn-success">View SDS</a>
            </div>

            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <p><strong>Molecular Structure:</strong></p>
                <img src="{{ $inventory->structure() }}" class="img-fluid" alt="Chemical Image">
            </div>
        </div>
    </div>
</div>
@endsection
