









@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h2>Edit Market Offer</h2> --}}

    <form action="{{ url('/m/'.$markets->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Edit Chemical Demand</h2>

                <div class="mb-4 row align-items-center">
                    <label for="chemical_id" class="col-md-3 col-form-label fw-semibold text-secondary">Select Chemical</label>
                    <div class="col-md-9">
                        <select id="chemical_id" name="chemical_id" class="form-select @error('chemical_id') is-invalid @enderror" required>
                            {{-- <option value="">-- Select a Chemical --</option> --}}
                            {{-- @foreach ($chemicals as $c) --}}
                                <option value="{{ $markets->chemical_id }}">{{ $markets->chemical->chemical_name }} ({{ $markets->chemical->CAS_number }})</option>
                            {{-- @endforeach --}}
                        </select>
                        @error('chemical_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="inventory_id" class="col-md-3 col-form-label fw-semibold text-secondary">Select Inventory</label>
                    <div class="col-md-9">
                        <select id="inventory_id" name="inventory_id" class="form-select @error('inventory_id') is-invalid @enderror" required>
                            <option value="{{ $markets->inventory_id }}">#{{ $markets->inventory->serial_number }}</option>
                        </select>
                        @error('inventory_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                {{-- quantity_needed & unit --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="quantity_needed" class="form-label fw-semibold text-secondary">Quantity Needed</label>
                        <input id="quantity_needed" type="number" step="0.01" name="quantity_needed"
                            class="form-control @error('quantity_needed') is-invalid @enderror"
                            value="{{ old('quantity_needed') ?? $markets->quantity_needed }}" required>
                        @error('quantity_needed')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold text-secondary">Unit</label>
                        <select id="unit" name="unit" class="form-select">
                            <option value="{{ $markets->unit }}" selected>{{$markets->unit}}</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>g</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="mg" {{ old('unit') == 'mg' ? 'selected' : '' }}>mg</option>
                            <option value="mL" {{ old('unit') == 'mL' ? 'selected' : '' }}>mL</option>
                            <option value="L" {{ old('unit') == 'L' ? 'selected' : '' }}>L</option>
                        </select>
                    </div>
                </div>

                {{-- stock
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-semibold text-secondary">Stock</label>
                        <input id="stock" type="number" step="0.01" name="stock"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock') }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}

                {{-- notes --}}
                <div class="mb-4 row align-items-center">
                    <label for="notes" class="col-md-3 col-form-label fw-semibold text-secondary">Notes</label>
                    <div class="col-md-9">
                        <input id="notes" type="text" name="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            value="{{ old('notes') ?? $markets->notes }}" required autofocus>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end pt-3">
                    <div class="mx-2">
                        <a href="{{ route('market.detail', $markets->id) }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Edit Chemical Demand
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- <script>
    function toggleCustomInput(select) {
        let customInput = document.getElementById('description');
        if (select.value === "desc") {
            customInput.classList.remove('d-none');
            customInput.required = true;
        } else {
            customInput.classList.add('d-none');
            customInput.required = false;
        }
    }
</script> --}}
@endsection
