@extends('layouts.app')

@section('content')
<div class="container">

    <form action={{ url('/i/'. $inventory->id . '/reduce') }} enctype="multipart/form-data" method="post">
        @csrf

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Use Container # {{$inventory->serial_number}}</h2>

                <p>Quantity Available: {{ $inventory->quantity }}</p>

                {{-- Quantity To Use --}}
                <div class="mb-4 row align-items-center">
                    <label for="quantity_used" class="col-md-3 col-form-label fw-semibold text-secondary">Quantity To Use:</label>
                    <div class="col-md-9">
                        <input id="quantity_used" type="number" step="0.01" name="quantity_used"
                            class="form-control @error('quantity_used') is-invalid @enderror"
                            value="{{ old('quantity_used') }}"
                            max="{{ $inventory->quantity }}" required>
                        @error('quantity_used')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Reason --}}
                <div class="mb-4 row align-items-center">
                    <label for="reason" class="col-md-3 col-form-label fw-semibold text-secondary">Reason</label>
                    <div class="col-md-9">
                        <textarea id="reason" type="text" name="reason"
                            class="form-control @error('reason') is-invalid @enderror"
                            required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <div class="d-flex justify-content-end pt-3">
                    <div class="mx-2">
                        <a href="{{ route('inventory.detail', $inventory->chemical->id) }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Use Chemical Item
                    </button>
                </div>

            </div>
        </div>
        {{-- <div class="row">
            <div class="col-8 offset-2">

                <p>Quantity Available: {{ $inventory->quantity }}</p>

                <div class="row mb-3">
                    <label for="quantity_used" class="col-md-4 col-form-label">Quantity to Use:</label>
                    <input id="quantity_used"
                            type="number"
                            step="0.01"
                            class="form-control @error('quantity_used') is-invalid @enderror"
                            name="quantity_used"
                            value="{{ old('quantity_used') }}"
                            max="{{ $inventory->quantity }}"
                            required autocomplete="quantity_used" autofocus>
                    @error('quantity_used')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="reason" class="col-md-4 col-form-label">Reason for Use:</label>
                    <input id="reason"
                            type="text"
                            class="form-control @error('reason') is-invalid @enderror"
                            name="reason"
                            value="{{ old('reason') }}"
                            required autocomplete="reason" autofocus>
                    @error('reason')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row pt-4 col-2">
                    <button class="btn btn-primary">Use Chemical</button>
                </div>
            </div>
        </div> --}}
    </form>

</div>
@endsection
