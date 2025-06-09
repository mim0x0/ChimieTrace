









@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h2>Add Market Offer</h2> --}}

    <form action="{{ url('/m/store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Add New Chemical Product</h2>

                {{-- Select Demand --}}
                <div class="mb-4 row align-items-center">
                    <label for="chemical_id" class="col-md-3 col-form-label fw-semibold text-secondary">Select Chemical</label>
                    <div class="col-md-9">
                        <select id="chemical_id" name="chemical_id" class="form-select @error('chemical_id') is-invalid @enderror" required>
                            {{-- <option value="">-- Select a Chemical --</option>
                            @foreach ($chemicals as $c) --}}
                                <option value="{{ $inventory->chemical->id }}">{{ $inventory->chemical->chemical_name }} ({{ $inventory->chemical->CAS_number }})</option>
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
                            <option value="{{ $inventory->id }}">#{{ $inventory->serial_number }}</option>
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
                            value="{{ old('quantity_needed') }}" required>
                        @error('quantity_needed')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold text-secondary">Unit</label>
                        <select id="unit" name="unit" class="form-select">
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
                            value="{{ old('notes') }}" required autofocus>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end pt-3">
                    <div class="mx-2">
                        <a href="{{ route('market.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Add New Chemical Product
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chemicalSelect = document.getElementById('chemical_id');
    const inventorySelect = document.getElementById('inventory_id');

    chemicalSelect.addEventListener('change', function () {
        const chemicalId = this.value;
        inventorySelect.innerHTML = '<option value="">Loading...</option>';

        if (chemicalId) {
            fetch(`/m/create/${chemicalId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    let options = '<option value="">-- Select Inventory --</option>';
                    data.forEach(i => {
                        options += `<option value="${i.id}">#${i.serial_number}</option>`;
                    });
                    inventorySelect.innerHTML = options;
                })
                .catch(error => {
                    inventorySelect.innerHTML = '<option value="">Error loading inventories</option>';
                    console.error('Error fetching inventories:', error);
                });
        } else {
            inventorySelect.innerHTML = '<option value="">-- Select Inventory --</option>';
        }
    });
});
</script>

@endsection
