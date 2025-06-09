@extends('layouts.app')

@section('content')
<div class="container">

    <form action="/i/i/{{$inventory->id}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
        @csrf
        @method('PATCH')

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Topup Chemical Container</h2>

                {{-- Select Chemical --}}
                <div class="mb-4 row align-items-center">
                    <label for="chemical_id" class="col-md-3 col-form-label fw-semibold text-secondary">Select Chemical</label>
                    <div class="col-md-9">
                        <select id="chemical_id" name="chemical_id" class="form-select @error('chemical_id') is-invalid @enderror" required>
                            {{-- @foreach ($chemicals as $chemical) --}}
                            <option value="{{ $inventory->chemical->id }}">{{ $inventory->chemical->chemical_name }} ({{ $inventory->chemical->CAS_number }})</option>
                            {{-- @endforeach --}}
                        </select>
                        @error('chemical_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-4 row align-items-center">
                    <label for="description" class="col-md-3 col-form-label fw-semibold text-secondary">Description</label>
                    <div class="col-md-9">
                        <input id="description" type="text" name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            value="{{ old('description') ?? $inventory->description }}" required autofocus>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Location --}}
                <div class="mb-4 row align-items-center">
                    <label for="location" class="col-md-3 col-form-label fw-semibold text-secondary">Location</label>
                    <div class="col-md-9">
                        <input id="location" type="text" name="location"
                            class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location') ?? $inventory->location }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mb-4 row align-items-center">
                    <label for="notes" class="col-md-3 col-form-label fw-semibold text-secondary">Notes</label>
                    <div class="col-md-9">
                        <textarea id="notes" type="text" name="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            >{{ old('notes') ?? $inventory->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Packaging Type & Unit --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="packaging_type" class="form-label fw-semibold text-secondary">Packaging Type</label>
                        <select id="packaging_type" name="packaging_type" class="form-select">
                            <option value="{{ $inventory->packaging_type }}" selected>{{ $inventory->packaging_type }}</option>
                            <option value="Bag" {{ old('packaging_type') == 'Bag' ? 'selected' : '' }}>Bag</option>
                            <option value="Bottle" {{ old('packaging_type') == 'Bottle' ? 'selected' : '' }}>Bottle</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold text-secondary">Unit</label>
                        <select id="unit" name="unit" class="form-select">
                            <option value="{{ $inventory->unit }}" selected>{{ $inventory->unit }}</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>g</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="mg" {{ old('unit') == 'mg' ? 'selected' : '' }}>mg</option>
                            <option value="mL" {{ old('unit') == 'mL' ? 'selected' : '' }}>mL</option>
                            <option value="L" {{ old('unit') == 'L' ? 'selected' : '' }}>L</option>
                        </select>
                    </div>
                </div>

                {{-- Quantity Per Container & Number of Containers --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="quantity" class="form-label fw-semibold text-secondary">Quantity Per Container</label>
                        <input id="quantity" type="number" step="0.01" name="quantity" readonly
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity') ?? $inventory->quantity }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="col-md-6">
                        <label for="container_count" class="form-label fw-semibold text-secondary">Number of Containers</label>
                        <input id="container_count" type="number" step="1" name="container_count"
                            class="form-control @error('container_count') is-invalid @enderror"
                            value="1" min="1" required>
                        @error('container_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}
                </div>

                {{-- Acquired At & Expired At --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="acq_at" class="form-label fw-semibold text-secondary">Acquired At</label>
                        <input id="acq_at" type="date" name="acq_at"
                            class="form-control @error('acq_at') is-invalid @enderror"
                            value="{{ old('acq_at') ?? $inventory->acq_at }}" required>
                        @error('acq_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="exp_at" class="form-label fw-semibold text-secondary">Expired At</label>
                        <input id="exp_at" type="date" name="exp_at"
                            class="form-control @error('exp_at') is-invalid @enderror"
                            value="{{ old('exp_at') ?? $inventory->exp_at }}" required>
                        @error('exp_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Brand --}}
                <div class="mb-4 row align-items-center">
                    <label for="brand" class="col-md-3 col-form-label fw-semibold text-secondary">Select Brand</label>
                    <div class="col-md-9">
                        <select id="brand" name="brand" class="form-select">
                            <option value="{{ $inventory->brand }}" selected>{{ $inventory->brand }}</option>
                            <option value="">-- Select Brand --</option>
                            {{-- <option value="Sigma-Aldrich" {{ old('brand') == 'Sigma-Aldrich' ? 'selected' : '' }}>Sigma-Aldrich</option> --}}
                            @foreach ($users as $user)
                                <option value="{{ $user->name }} {{ old('brand') == $user->name ? 'selected' : '' }}">{{ $user->name }}</option>
                            @endforeach
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->name }} {{ old('brand') == $brand->name ? 'selected' : '' }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Serial Number --}}
                <div class="mb-4 row align-items-center">
                    <label for="serial_number" class="col-md-3 col-form-label fw-semibold text-secondary">Serial Number</label>
                    <div class="col-md-9">
                        <input id="serial_number" type="text" name="serial_number"
                            class="form-control @error('serial_number') is-invalid @enderror"
                            value="{{ old('serial_number') ?? $inventory->serial_number }}" required>
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Threshold --}}
                <div class="mb-4 row align-items-center">
                    <label for="min_quantity" class="col-md-3 col-form-label fw-semibold text-secondary">Threshold</label>
                    <div class="col-md-9">
                        <input type="number" name="min_quantity" id="min_quantity" class="form-control"
                               placeholder="Enter threshold" value="{{ old('min_quantity') ?? $inventory->min_quantity }}" required>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end pt-3">
                    <div class="mx-2">
                        <a href="{{ route('inventory.detail', $inventory->chemical->id) }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Edit Container
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>

<script>
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
@endsection
