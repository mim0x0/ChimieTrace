@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mx-2">
        <a href="{{ route('inventory.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
    </div>
    <h2 class="mb-4 text-center fw-bold"><i class="bi bi-box-seam me-2"></i>Chemical Inventory</h2>
    <div class="mx-2">
    {{-- <div class="d-flex justify-content-center pt-3">
        <div class="mx-2">
            <a href="{{ route('inventory.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
        </div>
        <h2>Inventory</h2>
    </div> --}}

    <div class="card shadow-sm mb-4 border-0">
        <div class="row g-0">
            <!-- Image -->
            {{-- <div class="col-md-3 bg-white p-3 text-center">
                <img src="{{ $chemical->image() }}" class="img-fluid rounded" alt="Chemical Image">
            </div> --}}

            <!-- Structure -->
            <div class="col-md-3 p-3 text-center">
                {{-- <p class="fw-semibold mb-2">Structure</p> --}}
                <img src="{{ $chemical->structure() }}" class="img-fluid" alt="Structure">
            </div>

            <!-- Chemical Info -->
            <div class="col-md p-4">
                <h3 class="fw-bold">{{ $chemical->chemical_name }}</h3>
                {{-- <p class="mb-1"><strong>CAS:</strong> {{ $chemical->CAS_number }}</p>
                <p class="mb-1"><strong>Formula:</strong> {{ $chemical->empirical_formula }}</p>
                <p class="mb-1"><strong>EC Number:</strong> {{ $chemical->ec_number }}</p>
                <p class="mb-1"><strong>Molecular Weight:</strong> {{ $chemical->molecular_weight }}</p> --}}
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>CAS Number:</strong> {{ $chemical->CAS_number }}</li>
                    <li class="list-group-item"><strong>Empirical Formula:</strong> {{ $chemical->empirical_formula }}</li>
                    <li class="list-group-item"><strong>EC Number:</strong> {{ $chemical->ec_number }}</li>
                    <li class="list-group-item"><strong>Molecular Weight:</strong> {{ $chemical->molecular_weight }}</li>
                </ul>
                <a href="{{ $chemical->SDS() }}" target="_blank" class="btn btn-outline-success mt-2"><i class="bi bi-file-text me-2"></i>View SDS</a>
            </div>



            @if($chemical->properties)
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Chemical Properties</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2"><strong><i class="bi bi-palette-fill me-2"></i>Color:</strong> {{ $chemical->properties->color }}</div>
                        <div class="col-md-4 mb-2"><strong><i class="bi bi-droplet-fill me-2"></i>Physical State:</strong> {{ $chemical->properties->physical_state }}</div>
                        <div class="col-md-4 mb-2"><strong><i class="bi bi-fire me-2"></i>Flammability:</strong> {{ $chemical->properties->flammability }}</div>
                        <div class="col-md-4 mb-2"><strong><i class="bi bi-thermometer me-2"></i>Melting Point:</strong> {{ $chemical->properties->melting_point }} °C</div>
                        <div class="col-md-4 mb-2"><strong><i class="bi bi-thermometer-high me-2"></i>Boiling Point:</strong> {{ $chemical->properties->boiling_point }} °C</div>
                        <div class="col-md-12 mt-2"><strong>Notes:</strong> {{ $chemical->properties->other_details }}</div>
                    </div>
                </div>
            @endif

            @can('update', $chemical)
                <div class="mb-4">
                    <a href="/i/c/{{$chemical->id}}/edit" class="btn btn-outline-primary me-2"><i class="bi bi-pencil-square me-2"></i>Edit Chemical</a>
                    <a href="/i/cp/{{$chemical->id}}/edit" class="btn btn-outline-secondary"><i class="bi bi-gear-fill me-2"></i>Edit Properties</a>
                </div>
            @endcan
        </div>
    </div>


    {{-- <input type="file" id="csvFile" />
        <button onclick="uploadCsv()">Upload to Flowise</button> --}}

        {{-- @can('delete', $chemical)
            <form action="{{ url('i/c/'.$chemical->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete Chemical</button>
            </form>
        @endcan --}}

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" id="search" class="form-control flex-grow-1" style="max-width: 400px" placeholder="Search containers..." value="{{ request('search') }}">
            </div>
        </div>

        @can('create', App\Models\Inventory::class)
            <a href="{{ url('/i/createInventory/'.$chemical->id) }}" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i>Add Inventory</a>
        @endcan
    </div>



    <div id="js-inventory-results">
        @include('inventories._searchDetail', ['inventories' => $inventories])
    </div>


</div>

<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 12px;
    }

    .btn {
        border-radius: 8px;
    }

    img {
        max-height: 200px;
        object-fit: contain;
    }

    h3, h4, h5 {
        color: #333;
    }
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function(){
    function fetchResults(query = "", page = 1, filters = []) {
        let url = "{{ route('inventory.detail', ['chemical' => $chemical->id]) }}";

        // // 🔹 Prevent redirecting to inventory/search when search is empty
        // if (query === "" && page === 1) {
        //     window.location.href = "{{ route('inventory.index') }}";
        //     return;
        // }

        $.ajax({
            url: url,
            type: "GET",
            data: { search: query, page: page, filters: filters },
            success: function(data) {
                $('#js-inventory-results').html(data);
                // $('#search').val(query);
                applyColumnVisibility(filters);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // 🔹 Search Function (Only triggers AJAX if there's input)
    $('#search').on('keyup', function() {
        let query = $(this).val().trim();
        let filters = getSelectedFilters();

        if (query === "") {
            fetchResults("", 1, filters);
        } else {
            fetchResults(query, 1, filters);
        }
    });

    // 🔹 Handle Pagination (Prevents direct redirection)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        let query = $('#search').val().trim();
        let filters = getSelectedFilters();

        if (query === "") {
            window.location.href = "{{ route('inventory.detail', ['chemical' => $chemical->id]) }}?page=" + page;
        } else {
            fetchResults(query, page, filters);
        }
    });

    // 🔹 Toggle Filters
    $('.filter-toggle').on('change', function() {
        let filters = getSelectedFilters();
        applyColumnVisibility(filters);
    });

    // 🔹 Get Selected Filters
    function getSelectedFilters() {
        let filters = [];
        $('.filter-toggle:checked').each(function() {
            filters.push($(this).data('column'));
        });
        return filters;
    }

    // 🔹 Apply Column Visibility
    function applyColumnVisibility(filters) {
        $('th, td').hide(); // Hide all columns
        filters.forEach(column => {
            $('.col-' + column).show(); // Show selected columns
        });
    }
});
</script>

{{-- <script>
    const DOC_STORE_ID = "abaf0e12-e1d4-410f-bef4-0073e637b951"; // Your actual doc store ID
    const DOC_LOADER_ID = "ff495517-8f11-44e4-9568-f1307c37bcd9";     // Unique per upload

    async function uploadCsv() {
        const input = document.getElementById("csvFile");

        if (!input.files.length) {
            alert("Please choose a CSV file first.");
            return;
        }

        let formData = new FormData();
        formData.append("files", input.files[0]);
        formData.append("docId", DOC_LOADER_ID);

        try {
            const response = await fetch(`http://localhost:3000/api/v1/document-store/upsert/${DOC_STORE_ID}`, {
                method: "POST",
                headers: {
                    "Authorization": "Bearer Mk-X8puuZ_i9nqpPlqQb91IfLI9so3tgtYW62ma_3R8"
                },
                body: formData
            });

            const result = await response.json();
            console.log("Flowise response:", result);
            alert("Upload completed");
        } catch (error) {
            console.error("Upload failed", error);
            alert("Upload failed");
        }
    }

</script> --}}

{{-- <script>
    const DOC_STORE_ID = "abaf0e12-e1d4-410f-bef4-0073e637b951";
    const DOC_LOADER_ID = "ff495517-8f11-44e4-9568-f1307c37bcd9";
    const AUTH_TOKEN = "Bearer Mk-X8puuZ_i9nqpPlqQb91IfLI9so3tgtYW62ma_3R8";

    async function uploadCsvFromString(csvContent) {
        const csvBlob = new Blob([csvContent], { type: "text/csv" });
        const csvFile = new File([csvBlob], "chemical_properties.csv", { type: "text/csv" });

        const formData = new FormData();
        formData.append("files", csvFile);
        formData.append("docId", DOC_LOADER_ID);

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

    // Automatically fetch and upload on page load or call this manually
    window.onload = fetchAndUploadCsv;
</script> --}}


@endsection
