@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Buying process</h2>

    <div class="card p-3 mt-3 bg-light">
        <div class="row">
            <!-- Chemical Image -->
            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <img src="{{ $markets->chemical->image() }}" class="img-fluid" alt="Chemical Image">
            </div>
            <!-- Chemical Details -->
            <div class="col-md">
                <h4><strong>Chemical Name: </strong>{{ $markets->chemical->chemical_name }}</h4>
                <p><strong>CAS Number:</strong> {{ $markets->chemical->CAS_number }}</p>
                <p><strong>Serial Number:</strong> {{ $markets->chemical->serial_number }}</p>
                {{-- <p><strong>Location:</strong> {{ $location }}</p>
                <p><strong>Quantity:</strong> {{ $quantity }}</p> --}}
                <p><strong>SKU:</strong> {{ $markets->chemical->SKU }}</p>
                <p><strong>Description:</strong> {{ $markets->description }}</p>
                <p><strong>Price:</strong> {{ $markets->price }}</p>
                <p><strong>Currency:</strong> {{ $markets->currency }}</p>
                {{-- <p><strong>Acquired Date:</strong> {{ $acq_at }}</p>
                <p><strong>Expiry Date:</strong> {{ $exp_at }}</p> --}}
                @cannot('create', App\Models\Market::class)
                    <a href="/m/{{$markets->id}}/bill" class="btn btn-dark btn-success">Buy Offer</a>
                @endcannot
                @can('update', $markets)
                    <a href="/m/{{$markets->id}}/edit" class="btn btn-dark btn-success">Edit Offer</a>
                @endcan
                @can('delete', $markets)
                    <form action="{{ url('m/'.$markets->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete Offer</button>
                    </form>
                @endcan

            </div>

            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <p><strong>Molecular Structure:</strong></p>
                <img src="{{ $markets->chemical->structure() }}" class="img-fluid" alt="Chemical Image">
            </div>
        </div>
    </div>

</div>
@endsection
