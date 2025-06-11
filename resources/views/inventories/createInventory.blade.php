



@extends('layouts.app')

@section('content')
<div class="container my-5">

    <form action="/i/inventory/{{$chemical->id}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
        @csrf

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Add New Chemical Container</h2>

                {{-- Select Chemical --}}
                <div class="mb-4 row align-items-center">
                    <label for="chemical_id" class="col-md-3 col-form-label fw-semibold text-secondary">Chemical Name</label>
                    <div class="col-md-9">
                        <select id="chemical_id" name="chemical_id" class="form-select @error('chemical_id') is-invalid @enderror" required>
                            {{-- @foreach ($chemicals as $chemical) --}}
                            <option value="{{ $chemical->id }}">{{ $chemical->chemical_name }} ({{ $chemical->CAS_number }})</option>
                            {{-- @endforeach --}}
                        </select>
                        @error('chemical_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Serial Number --}}
                <div class="mb-4 row align-items-center">
                    <label for="serial_number" class="col-md-3 col-form-label fw-semibold text-secondary">Variant</label>
                    <div class="col-md-9">
                        <input id="serial_number" type="text" name="serial_number"
                            class="form-control @error('serial_number') is-invalid @enderror"
                            value="{{ old('serial_number') }}" required>
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                {{-- <div class="mb-4 row align-items-center">
                    <label for="description" class="col-md-3 col-form-label fw-semibold text-secondary">Description</label>
                    <div class="col-md-9">
                        <input id="description" type="text" name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            value="{{ old('description') }}" required autofocus>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}

                {{-- Location --}}
                <div class="mb-4 row align-items-center">
                    <label for="location" class="col-md-3 col-form-label fw-semibold text-secondary">Location</label>
                    <div class="col-md-9">
                        <input id="location" type="text" name="location"
                            class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location') }}" required>
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
                            formnovalidate>{{ old('notes') }}</textarea>
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
                            <option value="" selected>-- Select Unit --</option>
                            <option value="Vial" {{ old('packaging_type') == 'Vial' ? 'selected' : '' }}>Vial</option>
                            <option value="Canister" {{ old('packaging_type') == 'Canister' ? 'selected' : '' }}>Canister</option>
                            <option value="Drum" {{ old('packaging_type') == 'Drum' ? 'selected' : '' }}>Drum</option>
                            <option value="Bag" {{ old('packaging_type') == 'Bag' ? 'selected' : '' }}>Bag</option>
                            <option value="Bottle" {{ old('packaging_type') == 'Bottle' ? 'selected' : '' }}>Bottle</option>
                            <option value="Ampoule" {{ old('packaging_type') == 'Ampoule' ? 'selected' : '' }}>Ampoule</option>
                            <option value="Tube" {{ old('packaging_type') == 'Tube' ? 'selected' : '' }}>Tube</option>
                            <option value="Sachet" {{ old('packaging_type') == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                            <option value="Cylinder" {{ old('packaging_type') == 'Cylinder' ? 'selected' : '' }}>Cylinder</option>
                            <option value="Jar" {{ old('packaging_type') == 'Jar' ? 'selected' : '' }}>Jar</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold text-secondary">Unit</label>
                        <select id="unit" name="unit" class="form-select">
                            <option value="">-- Select Unit --</option>
                            {{-- <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>g</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="mg" {{ old('unit') == 'mg' ? 'selected' : '' }}>mg</option>
                            <option value="mL" {{ old('unit') == 'mL' ? 'selected' : '' }}>mL</option>
                            <option value="L" {{ old('unit') == 'L' ? 'selected' : '' }}>L</option> --}}
                        </select>
                    </div>
                </div>

                {{-- Quantity Per Container & Number of Containers --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="quantity" class="form-label fw-semibold text-secondary">Quantity Per Container</label>
                        <input id="quantity" type="number" step="0.01" name="quantity"
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity') }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="container_count" class="form-label fw-semibold text-secondary">Number of Containers</label>
                        <input id="container_count" type="number" step="1" name="container_count"
                            class="form-control @error('container_count') is-invalid @enderror"
                            value="{{ old('container_count') }}" min="1" required>
                        @error('container_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Acquired At & Expired At --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="acq_at" class="form-label fw-semibold text-secondary">Acquired At</label>
                        <input id="acq_at" type="date" name="acq_at"
                            class="form-control @error('acq_at') is-invalid @enderror"
                            value="{{ old('acq_at', now()->toDateString()) }}" required>
                        @error('acq_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="exp_at" class="form-label fw-semibold text-secondary">Expired At</label>
                        <input id="exp_at" type="date" name="exp_at"
                            class="form-control @error('exp_at') is-invalid @enderror"
                            value="{{ old('exp_at') }}" required>
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
                            <option value="" selected>-- Select Brand --</option>
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

                {{-- Threshold --}}
                <div class="mb-4 row align-items-center">
                    <label for="min_quantity" class="col-md-3 col-form-label fw-semibold text-secondary">Threshold</label>
                    <div class="col-md-9">
                        <input type="number" name="min_quantity" id="min_quantity" class="form-control"
                               placeholder="Enter threshold" value="{{ old('min_quantity') }}" required>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end pt-3">
                    <div class="mx-2">
                        <a href="{{ route('inventory.detail', $chemical->id) }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Add New Container
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>

{{-- Optional: Add small JS for Bootstrap validation --}}
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const oldUnit = "{{ old('unit') }}";

    const unitsByPackaging = {
        Bottle: ['mL', 'L'],
        Bag: ['g', 'kg'],
        Vial: ['mg', 'mL'],
        Canister: ['L', 'kg'],
        Drum: ['L', 'kg'],
        Ampoule: ['mL'],
        Tube: ['mg', 'g'],
        Sachet: ['mg', 'g'],
        Cylinder: ['L', 'm³'],
        Jar: ['g', 'kg']
    };

    function populateUnits(selectedType, oldUnitValue = null) {
        let options = '';
        if (unitsByPackaging[selectedType]) {
            unitsByPackaging[selectedType].forEach(unit => {
                const selected = unit === oldUnitValue ? 'selected' : '';
                options += `<option value="${unit}" ${selected}>${unit}</option>`;
            });
        } else {
            options = '<option value="">-- Select packaging type first --</option>';
        }
        $('#unit').html(options);
    }

    $(document).ready(function () {
        const oldPackagingType = "{{ old('packaging_type') }}";
        if (oldPackagingType) {
            $('#packaging_type').val(oldPackagingType).trigger('change');
            populateUnits(oldPackagingType, oldUnit);
        }

        $('#packaging_type').on('change', function () {
            const selectedType = $(this).val();
            populateUnits(selectedType);
        });
    });
</script>

<script>
    function getLocalToday() {
        const today = new Date();
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset()); // convert to local date
        return today.toISOString().split('T')[0];
    }

    document.addEventListener('DOMContentLoaded', function () {
        const acqInput = document.getElementById('acq_at');
        const expInput = document.getElementById('exp_at');

        const today = new Date();
        const fourteenDaysAgo = new Date();
        fourteenDaysAgo.setDate(today.getDate() - 14);
        acqInput.setAttribute('min', fourteenDaysAgo.toISOString().split('T')[0]);
        acqInput.setAttribute('max', getLocalToday());

        // Update exp_at min when acq_at changes
        function updateExpMin() {
            if (acqInput.value) {
                expInput.min = acqInput.value;
            } else {
                expInput.removeAttribute('min');
            }
        }

        acqInput.addEventListener('change', updateExpMin);

        // Trigger on load in case of old value
        updateExpMin();
    });
</script>
@endsection
