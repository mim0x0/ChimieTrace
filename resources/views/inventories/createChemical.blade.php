


@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <div class="card shadow-sm p-4 bg-white rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add New Chemical</h4>
        </div>
        <div class="card-body"> --}}
            <form action="/i/chemical" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h2 class="card-title mb-4 text-primary fw-bold">Register New Chemical</h2>

                        {{-- Chemical Name --}}
                        <div class="mb-4 row align-items-center">
                            <label for="chemical_name" class="col-md-3 col-form-label fw-semibold text-secondary">Chemical Name</label>
                            <div class="col-md-9">
                                <input id="chemical_name" type="text" name="chemical_name" class="form-control" required autocomplete="off">
                                    {{-- class="form-control @error('chemical_name') is-invalid @enderror"
                                    value="{{ old('chemical_name') }}" required autofocus> --}}
                                @error('chemical_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- CAS Number --}}
                        <div class="mb-4 row align-items-center">
                            <label for="CAS_number" class="col-md-3 col-form-label fw-semibold text-secondary">CAS Number</label>
                            <div class="col-md-9">
                                <input id="CAS_number" type="text" name="CAS_number" class="form-control">
                                    {{-- class="form-control @error('CAS_number') is-invalid @enderror"
                                    value="{{ old('CAS_number') }}" required> --}}
                                @error('CAS_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Empirical Structure --}}
                        <div class="mb-4 row align-items-center">
                            <label for="empirical_formula" class="col-md-3 col-form-label fw-semibold text-secondary">Empirical Structure</label>
                            <div class="col-md-9">
                                <input id="empirical_formula" type="text" name="empirical_formula" class="form-control">
                                    {{-- class="form-control @error('empirical_formula') is-invalid @enderror"
                                    value="{{ old('empirical_formula') }}" required> --}}
                                @error('empirical_formula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- EC Number --}}
                        {{-- <div class="mb-4 row align-items-center">
                            <label for="ec_number" class="col-md-3 col-form-label fw-semibold text-secondary">EC Number</label>
                            <div class="col-md-9">
                                <input id="ec_number" type="text" name="ec_number"
                                    class="form-control @error('ec_number') is-invalid @enderror"
                                    value="{{ old('ec_number') }}" required>
                                @error('ec_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- moldecular weight --}}
                        {{-- <div class="mb-4 row align-items-center">
                            <label for="molecular_weight" class="col-md-3 col-form-label fw-semibold text-secondary">Molecular Weight</label>
                            <div class="col-md-9">
                                <input id="molecular_weight" type="number" step="0.01" name="molecular_weight"
                                    class="form-control @error('molecular_weight') is-invalid @enderror"
                                    value="{{ old('molecular_weight') }}" required>
                                @error('molecular_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        {{--  --}}
                        {{-- <div class="mb-4 row align-items-center">
                            <label for="image" class="form-label fw-semibold text-secondary">Chemical Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @error('image')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div> --}}

                        <div class="mb-4 row align-items-center">
                            <label for="chemical_structure" class="form-label fw-semibold text-secondary">Chemical Structure</label>
                            <input type="file" class="form-control" id="chemical_structure" name="chemical_structure">
                            @error('chemical_structure')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="SDS_file" class="form-label fw-semibold text-secondary">Upload SDS</label>
                            <input type="file" class="form-control" id="SDS_file" name="SDS_file" accept=".pdf">
                            @error('SDS_file')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-flex justify-content-end pt-3">
                            <div class="mx-2">
                                <a href="{{ route('inventory.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                            </div>
                            <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                                <i class="bi bi-plus-circle me-2"></i> Register New Chemical
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        {{-- </div>
    </div> --}}
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
document.addEventListener("DOMContentLoaded", () => {
    const nameInput = document.getElementById("chemical_name");
    const casInput = document.getElementById("CAS_number");
    const formulaInput = document.getElementById("empirical_formula");

    let timeout = null;
    let suggestionBox;

    // Create dropdown container
    function createSuggestionBox() {
        suggestionBox = document.createElement("div");
        suggestionBox.classList.add("list-group", "position-absolute", "w-100", "z-3", "bg-white");
        suggestionBox.style.maxHeight = "200px";
        suggestionBox.style.overflowY = "auto";
        nameInput.parentNode.appendChild(suggestionBox);
    }

    function showSuggestions(data) {
        suggestionBox.innerHTML = '';
        data.forEach(item => {
            const option = document.createElement("a");
            option.href = "#";
            option.className = "list-group-item list-group-item-action";
            option.textContent = item.chemical_name;
            option.dataset.cas = item.CAS_number;
            option.dataset.formula = item.empirical_formula;

            option.onclick = function (e) {
                e.preventDefault();
                nameInput.value = item.chemical_name;
                casInput.value = item.CAS_number;
                formulaInput.value = item.empirical_formula;
                suggestionBox.innerHTML = '';
            };
            suggestionBox.appendChild(option);
        });
    }

    nameInput.addEventListener("input", function () {
        const query = this.value;
        if (query.length < 2) {
            suggestionBox.innerHTML = '';
            return;
        }

        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fetch(`/api/registered-chemicals?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => showSuggestions(data))
                .catch(err => console.error(err));
        }, 300);
    });

    createSuggestionBox();
});
</script>

@endsection
