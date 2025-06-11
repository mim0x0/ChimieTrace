









@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h2>Add Market Offer</h2> --}}

    <form action="{{ url('/m/'.$markets->id.'/bid') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Submit Offer for: {{ $markets->inventory->chemical->chemical_name }} ({{ $markets->inventory->serial_number }})</h2>

                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="quantity" class="form-label fw-semibold text-secondary">Quantity per unit (in {{ $markets->unit }})</label>
                        <input id="quantity" type="number" step="0.01" name="quantity"
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity') }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-semibold text-secondary">Stock Available</label>
                        <input id="stock" type="number" step="0.01" name="stock"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock') }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- price --}}
                <div class="mb-4 row align-items-center">
                    <label for="price" class="col-md-3 col-form-label fw-semibold text-secondary">Price Offered per unit (do not put RM)</label>
                    <div class="col-md-9">
                        <input id="price" type="text" name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price') }}" required autofocus>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- <div class="mb-4 row align-items-center">
                    <label for="price" class="col-md-3 col-form-label fw-semibold text-secondary">Price Offered per unit (do not put RM)</label>
                    <input id="price" type="number" step="0.01" name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                {{-- notes --}}
                <div class="mb-4 row align-items-center">
                    <label for="notes" class="col-md-3 col-form-label fw-semibold text-secondary">Notes (optional)</label>
                    <div class="col-md-9">
                        <input id="notes" type="text" name="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            value="{{ old('notes') }}" autofocus>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <fieldset class="border rounded p-3 mb-4">
                    <legend class="float-none w-auto px-2 text-primary fw-semibold">Bulk Pricing Tiers</legend>

                    <div class="row mb-2 fw-semibold text-secondary">
                        <div class="col-1">Tier</div>
                        <div class="col">Min Qty</div>
                        <div class="col">Price per Unit (RM)</div>
                        <div class="col-auto"></div>
                    </div>

                    <div id="tier-wrapper">
                        @foreach(old('tiers', $offer->bulkPrices ?? [['min_qty' => '', 'price' => '']]) as $i => $tier)
                            <div class="row mb-2">
                                <div class="col-1">
                                    <input type="text" readonly class="form-control-plaintext fw-semibold" value="Tier {{ $i + 1 }}">
                                    <input type="hidden" name="tiers[{{ $i }}][tier]" value="{{ $i + 1 }}">
                                </div>
                                <div class="col">
                                    <input type="number" name="tiers[{{ $i }}][min_qty]" class="form-control" placeholder="Min Qty" value="{{ $tier['min_qty'] ?? '' }}" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="tiers[{{ $i }}][price]" class="form-control" placeholder="Price per Unit (RM)" step="0.01" value="{{ $tier['price'] ?? '' }}" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">-</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-primary mt-2" onclick="addTier()">+ Add Tier</button>
                </fieldset>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end pt-3">
                    <div class="mx-2">
                        <a href="{{ route('market.detail', $markets->id) }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Add New Supply Offer
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
let tierIndex = {{ count(old('tiers', $offer->bulkPrices ?? [[]])) }};
function addTier() {
    const wrapper = document.getElementById('tier-wrapper');
    const row = document.createElement('div');
    row.classList.add('row', 'mb-2');
    row.innerHTML = `
        <div class="col-1">
            <input type="text" readonly class="form-control-plaintext fw-semibold" value="Tier ${tierIndex + 1}">
            <input type="hidden" name="tiers[${tierIndex}][tier]" value="${tierIndex + 1}">
        </div>
        <div class="col">
            <input type="number" name="tiers[${tierIndex}][min_qty]" class="form-control" placeholder="Min Qty" required>
        </div>
        <div class="col">
            <input type="number" name="tiers[${tierIndex}][price]" class="form-control" placeholder="Price per Unit (RM)" step="0.01" required>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">-</button>
        </div>
    `;
    wrapper.appendChild(row);
    tierIndex++;
}
</script>


@endsection
