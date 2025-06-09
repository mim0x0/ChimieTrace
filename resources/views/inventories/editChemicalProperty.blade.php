@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Loading Spinner -->
    <div id="loading-spinner" class="d-none text-center my-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Saving and uploading chemical data...</p>
    </div>



    <form id="chemical-property-form" action="/i/cp/{{$chemical->id}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
        @csrf
        @method('PATCH')

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h2 class="card-title mb-4 text-primary fw-bold">Edit Chemical Property: {{$chemical->chemical_name}} ({{$chemical->CAS_number}})</h2>

                {{-- Color --}}
                <div class="mb-4 row align-items-center">
                    <label for="color" class="col-md-3 col-form-label fw-semibold text-secondary">Color</label>
                    <div class="col-md-9">
                        <input id="color" type="text" name="color"
                            class="form-control @error('color') is-invalid @enderror"
                            value="{{ old('color') ?? $property->color }}" required autofocus>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Physical State --}}
                <div class="mb-4 row align-items-center">
                    <label for="physical_state" class="col-md-3 col-form-label fw-semibold text-secondary">Physical State</label>
                    <div class="col-md-9">
                        <input id="physical_state" type="text" name="physical_state"
                            class="form-control @error('physical_state') is-invalid @enderror"
                            value="{{ old('physical_state') ?? $property->physical_state }}" required>
                        @error('physical_state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Melting Point --}}
                <div class="mb-4 row align-items-center">
                    <label for="melting_point" class="col-md-3 col-form-label fw-semibold text-secondary">Melting Point</label>
                    <div class="col-md-9">
                        <input id="melting_point" type="number" step="0.01" name="melting_point"
                            class="form-control @error('melting_point') is-invalid @enderror"
                            value="{{ old('melting_point') ?? $property->melting_point }}" required>
                        @error('melting_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Boiling Point --}}
                <div class="mb-4 row align-items-center">
                    <label for="boiling_point" class="col-md-3 col-form-label fw-semibold text-secondary">Boiling Point</label>
                    <div class="col-md-9">
                        <input id="boiling_point" type="number" step="0.01" name="boiling_point"
                            class="form-control @error('boiling_point') is-invalid @enderror"
                            value="{{ old('boiling_point') ?? $property->boiling_point }}" required>
                        @error('boiling_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Flammability --}}
                <div class="mb-4 row align-items-center">
                    <label for="flammability" class="col-md-3 col-form-label fw-semibold text-secondary">Flammability</label>
                    <div class="col-md-9">
                        <input id="flammability" type="text" name="flammability"
                            class="form-control @error('flammability') is-invalid @enderror"
                            value="{{ old('flammability') ?? $property->flammability }}" required>
                        @error('flammability')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Other Details --}}
                <div class="mb-4 row align-items-center">
                    <label for="other_details" class="col-md-3 col-form-label fw-semibold text-secondary">Other Details</label>
                    <div class="col-md-9">
                        <textarea id="other_details" type="text" name="other_details"
                            class="form-control @error('other_details') is-invalid @enderror"
                            required>{{ old('other_details', $property->other_details) }}</textarea>
                        @error('other_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-3">
                    <div class="mx-2">
                        <a href="{{ route('inventory.detail', $chemical->id) }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Edit Chemical Property
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>

{{-- <script>
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
</script> --}}

<script>
    const DOC_STORE_ID = "abaf0e12-e1d4-410f-bef4-0073e637b951";
    const DOC_LOADER_ID = "ff495517-8f11-44e4-9568-f1307c37bcd9";
    const AUTH_TOKEN = "Bearer EwU_7nsxoRtE5GAIYjfGe5jszb8_vve-usgigj-z3NU";

    async function uploadCsvFromString(csvContent) {
        const csvBlob = new Blob([csvContent], { type: "text/csv" });
        const csvFile = new File([csvBlob], "chemical_properties.csv", { type: "text/csv" });

        const formData = new FormData();
        formData.append("files", csvFile);
        formData.append("docId", DOC_LOADER_ID);
        formData.append("replaceExisting", true);

        try {
            const response = await fetch(`http://localhost:3000/api/v1/document-store/upsert/${DOC_STORE_ID}`, {
            method: "POST",
            headers: {
                Authorization: AUTH_TOKEN,
            },
            body: formData,
            });

            const result = await response.json();
            console.log("Flowise response:", result);
            alert("Upload completed");
        } catch (error) {
            console.error("Upload failed", error);
            alert("Upload failed");
        }
    }

    async function fetchAndUploadCsv() {
        try {
            const res = await fetch('/api/chemical-properties/csv-content');
            const data = await res.json();
            await uploadCsvFromString(data.csvContent);
        } catch (err) {
            console.error("Failed to fetch CSV content or upload:", err);
        }
    }

    document.getElementById('chemical-property-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const spinner = document.getElementById('loading-spinner');

        spinner.classList.remove('d-none');

        try {
            await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'X-HTTP-Method-Override': 'PATCH',
                },
                body: formData,
            });

            await fetchAndUploadCsv();

            // Redirect to inventory detail page
            window.location.href = "{{ route('inventory.detail', $chemical->id) }}";
        } catch (err) {
            console.error('Submission or CSV upload failed:', err);
            alert("Failed to save or upload.");
        } finally {
            spinner.classList.add('d-none'); // Hide spinner just in case
        }
    });
</script>

@endsection
