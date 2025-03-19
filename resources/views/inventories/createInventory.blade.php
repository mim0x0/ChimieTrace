@extends('layouts.app')

@section('content')
<div class="container">

    <form action="/i/inventory" enctype="multipart/form-data" method="post">
        @csrf

        <div class="row">
            <div class="col-8 offset-2">

                <div class="row">
                    <h1>Add New Chemical</h1>
                </div>

                <div class="row mb-3">
                    <label for="chemical_id" class="col-md-4 col-form-label">Select Chemical</label>

                    <select class="form-control" name="chemical_id" id="chemical_id">
                        @foreach ($chemicals as $chemical)
                            <option value="{{ $chemical->id }}">{{ $chemical->chemical_name }} ({{ $chemical->CAS_number }})</option>
                        @endforeach
                    </select>

                </div>

                {{-- <div class="row mb-3">
                    <label for="chemical_name" class="col-md-4 col-form-label">Chemical Name</label>

                    <input id="chemical_name"
                            type="text"
                            class="form-control @error('chemical_name') is-invalid @enderror"
                            name="chemical_name"
                            value="{{ old('chemical_name') }}"
                            required autocomplete="chemical_name" autofocus>

                    @error('chemical_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div> --}}

                {{-- <div class="row">
                    <label for="image" class="col-md-4 col-form-label">Chemical Image</label>
                    <input type="file" class="form-input" id="image" name="image">

                    @error('image')
                        <!-- <span class="invalid-feedback" role="alert"> -->
                            <strong>{{ $message }}</strong>
                        <!-- </span> -->
                    @enderror
                </div> --}}

                {{-- <div class="row">
                    <label for="chemical_structure" class="col-md-4 col-form-label">Chemical Structure</label>
                    <input type="file" class="form-input" id="chemical_structure" name="chemical_structure">

                    @error('chemical_structure')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}

                {{-- <div class="row mb-3">
                    <label for="CAS_number" class="col-md-4 col-form-label">CAS Number</label>
                    <input id="CAS_number"
                            type="text"
                            class="form-control @error('CAS_number') is-invalid @enderror"
                            name="CAS_number"
                            value="{{ old('CAS_number') }}"
                            required autocomplete="CAS_number" autofocus>
                    @error('CAS_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="serial_number" class="col-md-4 col-form-label">Serial Number</label>
                    <input id="serial_number"
                            type="text"
                            class="form-control @error('serial_number') is-invalid @enderror"
                            name="serial_number"
                            value="{{ old('serial_number') }}"
                            required autocomplete="serial_number" autofocus>
                    @error('serial_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}

                <div class="row mb-3">
                    <label for="location" class="col-md-4 col-form-label">Location</label>
                    <input id="location"
                            type="text"
                            class="form-control @error('location') is-invalid @enderror"
                            name="location"
                            value="{{ old('location') }}"
                            required autocomplete="location" autofocus>
                    @error('location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
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

                {{-- <div class="row mb-3">
                    <label for="SKU" class="col-md-4 col-form-label">SKU</label>
                    <input id="SKU"
                            type="text"
                            class="form-control @error('SKU') is-invalid @enderror"
                            name="SKU"
                            value="{{ old('SKU') }}"
                            required autocomplete="SKU" autofocus>
                    @error('SKU')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}

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
                </div>

                {{-- <div class="mb-3">
                    <label for="SDS_file" class="col-md-4 col-form-label">Upload SDS file</label>
                    <input id="SDS_file"
                            type="file"
                            class="form-control"
                            name="SDS_file" accept=".pdf">
                </div> --}}


                <div class="row pt-4 col-2">
                    <button class="btn btn-primary">Add new chemical</button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
