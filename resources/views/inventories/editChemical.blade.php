@extends('layouts.app')

@section('content')
<div class="container">

    <form action="/i/c/{{$chemical->id}}" enctype="multipart/form-data" method="post">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-8 offset-2">

                <div class="row">
                    <h1>Edit Chemical</h1>
                </div>

                <div class="row mb-3">
                    <label for="chemical_name" class="col-md-4 col-form-label">Chemical Name</label>

                    <input id="chemical_name"
                            type="text"
                            class="form-control @error('chemical_name') is-invalid @enderror"
                            name="chemical_name"
                            value="{{ old('chemical_name') ?? $chemical->chemical_name }}"
                            required autocomplete="chemical_name" autofocus>

                    @error('chemical_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="empirical_formula" class="col-md-4 col-form-label">Empirical Formula</label>

                    <input id="empirical_formula"
                            type="text"
                            class="form-control @error('empirical_formula') is-invalid @enderror"
                            name="empirical_formula"
                            value="{{ old('empirical_formula') ?? $chemical->empirical_formula }}"
                            required autocomplete="empirical_formula" autofocus>

                    @error('empirical_formula')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="CAS_number" class="col-md-4 col-form-label">CAS Number</label>
                    <input id="CAS_number"
                            type="text"
                            class="form-control @error('CAS_number') is-invalid @enderror"
                            name="CAS_number"
                            value="{{ old('CAS_number') ?? $chemical->CAS_number }}"
                            required autocomplete="CAS_number" autofocus>
                    @error('CAS_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="molecular_weight" class="col-md-4 col-form-label">Molecular Weight</label>
                    <input id="molecular_weight"
                            type="number"
                            step="0.01"
                            class="form-control @error('molecular_weight') is-invalid @enderror"
                            name="molecular_weight"
                            value="{{ old('molecular_weight') ?? $chemical->molecular_weight }}"
                            required autocomplete="molecular_weight" autofocus>
                    @error('molecular_weight')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="ec_number" class="col-md-4 col-form-label">EC Number</label>
                    <input id="ec_number"
                            type="text"
                            class="form-control @error('ec_number') is-invalid @enderror"
                            name="ec_number"
                            value="{{ old('ec_number') ?? $chemical->ec_number }}"
                            required autocomplete="ec_number" autofocus>
                    @error('ec_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row">
                    <label for="image" class="col-md-4 col-form-label">Chemical Image</label>
                    <input type="file" class="form-input" id="image" name="image">

                    @error('image')
                        <!-- <span class="invalid-feedback" role="alert"> -->
                            <strong>{{ $message }}</strong>
                        <!-- </span> -->
                    @enderror
                </div>

                <div class="row">
                    <label for="chemical_structure" class="col-md-4 col-form-label">Chemical Structure</label>
                    <input type="file" class="form-input" id="chemical_structure" name="chemical_structure">

                    @error('chemical_structure')
                        {{-- <span class="invalid-feedback" role="alert"> --}}
                            <strong>{{ $message }}</strong>
                        {{-- </span> --}}
                    @enderror
                </div>

                <div class="row">
                    <label for="SDS_file" class="col-md-4 col-form-label">Upload SDS</label>
                    <input type="file" class="form-input" id="SDS_file" name="SDS_file">

                    @error('SDS_file')
                        {{-- <span class="invalid-feedback" role="alert"> --}}
                            <strong>{{ $message }}</strong>
                        {{-- </span> --}}
                    @enderror
                </div>

                <div class="row pt-4 col-2">
                    <button class="btn btn-primary">Update chemical</button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
