@extends('layouts.app')

@section('content')
<div class="container">

    <form action="/i/i/{{$inventory->id}}" enctype="multipart/form-data" method="post">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-8 offset-2">

                <div class="row">
                    <h1>Edit Chemical Container</h1>
                </div>

                <div class="row mb-3">
                    <label for="chemical_id" class="col-md-4 col-form-label">Chemical</label>
                    <div id="chemical_id">{{ $inventory->chemical->chemical_name }} ({{ $inventory->chemical->CAS_number }})</div>
                    {{-- <select class="form-control" name="chemical_id" id="chemical_id">
                        @foreach ($chemicals as $chemical)
                            <option value="{{ $chemical->id }}">{{ $chemical->chemical_name }} ({{ $chemical->CAS_number }})</option>
                        @endforeach
                    </select> --}}

                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-4 col-form-label">Description</label>

                    <input id="description"
                            type="text"
                            class="form-control @error('description') is-invalid @enderror"
                            name="description"
                            value="{{ old('description') ?? $inventory->description }}"
                            required autocomplete="description" autofocus>

                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="row mb-3">
                    <label for="location" class="col-md-4 col-form-label">Location</label>
                    <input id="location"
                            type="text"
                            class="form-control @error('location') is-invalid @enderror"
                            name="location"
                            value="{{ old('location') ?? $inventory->location }}"
                            required autocomplete="location" autofocus>
                    @error('location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="quantity" class="col-md-4 col-form-label">Quantity Per Container</label>
                    <input id="quantity"
                            type="number"
                            step="0.01"
                            class="form-control @error('quantity') is-invalid @enderror"
                            name="quantity"
                            value="{{ old('quantity') ?? $inventory->quantity }}"
                            required autocomplete="quantity" autofocus>
                    @error('quantity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- <div class="row mb-3">
                    <label for="container_count" class="col-md-4 col-form-label">Number of Containers</label>
                    <input id="container_count"
                            type="number"
                            step="1"
                            class="form-control @error('container_count') is-invalid @enderror"
                            name="container_count"
                            value="1"
                            min="1"
                            required autocomplete="container_count" autofocus>
                    @error('container_count')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}

                <div class="row mb-3">
                    <label for="notes" class="col-md-4 col-form-label">Notes</label>
                    <input id="notes"
                            type="text"
                            class="form-control @error('notes') is-invalid @enderror"
                            name="notes"
                            value="{{ old('notes') ?? $inventory->notes }}"
                            required autocomplete="notes" autofocus>
                    @error('notes')
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
                            value="{{ old('acq_at') ?? $inventory->acq_at }}"
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
                            value="{{ old('exp_at') ?? $inventory->exp_at }}"
                            required autocomplete="exp_at" autofocus>
                    @error('exp_at')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="brand" class="col-md-4 col-form-label">Brand</label>
                    <div id="brand">{{ $inventory->brand }}</div>
                    {{-- <select class="form-control" name="brand" id="brand">
                        <option value="" selected>-- Select Brand --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                        @endforeach
                    </select> --}}
                    {{-- nnti add something so that admin can add just supplier list that are not registered, bcuz they are website for example -> then tukar jadi required --}}
                </div>

                <div class="row mb-3">
                    <label for="serial_number" class="col-md-4 col-form-label">Serial Number (If Any)</label>
                    <div id="serial_number">{{ $inventory->serial_number }}</div>
                    {{-- <input id="serial_number"
                            type="text"
                            class="form-control @error('serial_number') is-invalid @enderror"
                            name="serial_number"
                            value="{{ old('serial_number') ?? $inventory->serial_number }}"
                            autocomplete="serial_number" autofocus>
                    @error('serial_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror --}}
                </div>

                {{-- <div class="mb-3">
                    <label for="SDS_file" class="col-md-4 col-form-label">Upload SDS file</label>
                    <input id="SDS_file"
                            type="file"
                            class="form-control"
                            name="SDS_file" accept=".pdf">
                </div> --}}




                <div class="row pt-4 col-2">
                    <button class="btn btn-primary">Edit container</button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
