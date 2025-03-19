@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory</h2>

    <div class="card p-3 mt-3 bg-light">
        <div class="row">
            <!-- Chemical Image -->
            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <img src="{{ $chemical->image() }}" class="img-fluid" alt="Chemical Image">
            </div>
            <!-- Chemical Details -->
            <div class="col-md">
                <h4><strong>Chemical Name: </strong>{{ $chemical->chemical_name }}</h4>
                <p><strong>CAS Number:</strong> {{ $chemical->CAS_number }}</p>
                <p><strong>Serial Number:</strong> {{ $chemical->serial_number }}</p>
                {{-- <p><strong>Location:</strong> {{ $location }}</p>
                <p><strong>Quantity:</strong> {{ $quantity }}</p> --}}
                <p><strong>SKU:</strong> {{ $chemical->SKU }}</p>
                {{-- <p><strong>Acquired Date:</strong> {{ $acq_at }}</p>
                <p><strong>Expiry Date:</strong> {{ $exp_at }}</p> --}}
                <a href="{{ $chemical->SDS() }}" target="_blank" class="btn btn-dark btn-success">View SDS</a>
            </div>

            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <p><strong>Molecular Structure:</strong></p>
                <img src="{{ $chemical->structure() }}" class="img-fluid" alt="Chemical Image">
            </div>
        </div>
    </div>

    @foreach ($inventories as $i)
        <div class="card p-3 mt-3 bg-light">
            <div class="row">
                <div class="col-md">
                    <p><strong>Location:</strong> {{ $i->location }}</p>
                    <p><strong>Quantity:</strong> {{ $i->quantity }}</p>
                    <p><strong>Acquired Date:</strong> {{ $i->acq_at }}</p>
                    <p><strong>Expiry Date:</strong> {{ $i->exp_at }}</p>
                </div>
            </div>
        </div>
    @endforeach


</div>
@endsection
