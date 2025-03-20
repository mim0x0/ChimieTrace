@extends('layouts.app')

@section('content')
<div class="container">

    <form action={{ url('/i/'. $inventory->id . '/reduce') }} enctype="multipart/form-data" method="post">
        @csrf

        <div class="row">
            <div class="col-8 offset-2">

                <p>Quantity Available: {{ $inventory->quantity }}</p>

                <div class="row mb-3">
                    <label for="quantity_used">Quantity to Use:</label>
                    <input type="number" name="quantity_used" min="0.01" step="0.01" max="{{ $inventory->quantity }}" required>


                </div>

                <div class="row mb-3">
                    <label for="reason">Reason for Use:</label>
                    <textarea name="reason" required></textarea>
                </div>

                {{-- <div class="row mb-3">
                    <label for="quantity" class="col-md-4 col-form-label">Quantity</label>
                    <input id="quantity"
                            type="number"
                            step="0.01"
                            class="form-control @error('quantity') is-invalid @enderror"
                            name="quantity"
                            value="{{ old('quantity') }}"
                            required autocomplete="quantity" autofocus>
                    @error('quantity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="acq_at" class="col-md-4 col-form-label">Acquired At</label>
                    <input id="acq_at"
                            type="date"
                            class="form-control @error('acq_at') is-invalid @enderror"
                            name="acq_at"
                            value="{{ old('acq_at', now()->toDateString()) }}"
                            required autocomplete="acq_at" autofocus>
                    @error('acq_at')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="exp_at" class="col-md-4 col-form-label">Expired At</label>
                    <input id="exp_at"
                            type="date"
                            class="form-control @error('exp_at') is-invalid @enderror"
                            name="exp_at"
                            value="{{ old('exp_at') }}"
                            required autocomplete="exp_at" autofocus>
                    @error('exp_at')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}

                <div class="row pt-4 col-2">
                    <button class="btn btn-primary">Add new chemical</button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
