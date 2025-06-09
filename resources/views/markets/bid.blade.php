









@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h2>Add Market Offer</h2> --}}

    <form action="{{ url('/m/'.$markets->id.'/bid') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Submit Offer for: {{ $markets->inventory->chemical->chemical_name }} ({{ $markets->inventory->description }})</h2>

                {{-- price & unit --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold text-secondary">Price Offered (RM)</label>
                        <input id="price" type="number" step="0.01" name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="quantity" class="form-label fw-semibold text-secondary">Quantity Offered</label>
                        <input id="quantity" type="number" step="0.01" name="quantity"
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity') }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- delivery --}}
                <div class="mb-4 row align-items-center">
                    <label for="delivery" class="col-md-3 col-form-label fw-semibold text-secondary">Delivery Time</label>
                    <div class="col-md-9">
                        <input id="delivery" type="text" name="delivery"
                            class="form-control @error('delivery') is-invalid @enderror"
                            value="{{ old('delivery') }}" required autofocus>
                        @error('delivery')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- notes --}}
                <div class="mb-4 row align-items-center">
                    <label for="notes" class="col-md-3 col-form-label fw-semibold text-secondary">Notes</label>
                    <div class="col-md-9">
                        <input id="notes" type="text" name="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            value="{{ old('notes') }}" autofocus>
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


@endsection
