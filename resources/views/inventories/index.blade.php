{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <input type="text" id="search" class="form-control mb-3" placeholder="🔍 Search chemicals..." value="{{ request('search') }}">

        <div class="d-flex justify-content-between align-items-start mb-3"> --}}
            {{-- Left --}}
            {{-- <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Filters
                </button>
                <ul class="dropdown-menu p-3" aria-labelledby="filterDropdown">
                    <li><label><input type="checkbox" class="filter-toggle" data-column="chemical_name" checked> Chemical Name</label></li>
                    <li><label><input type="checkbox" class="filter-toggle" data-column="CAS_number" checked> CAS Number</label></li>
                    <li><label><input type="checkbox" class="filter-toggle" data-column="empirical_formula" checked> Empirical Formula</label></li>
                    <li><label><input type="checkbox" class="filter-toggle" data-column="ec_number" checked> EC Number</label></li>
                    <li><label><input type="checkbox" class="filter-toggle" data-column="molecular_weight" checked> Molecular Weight</label></li>
                </ul>
            </div> --}}

            {{-- Right --}}
            {{-- @can('create', App\Models\Chemical::class)
                <a href="{{ url('/i/createChemical') }}" class="btn btn-secondary ms-3">Add Chemical Item</a>
            @endcan
        </div>

        <div id="js-inventory-results">
            @include('inventories._search', ['chemicals' => $chemicals])
        </div>
    </div>
</div> --}}

{{-- chemical search function --}}
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function(){
    function fetchResults(query = "", page = 1, filters = []) {
        let url = "{{ route('inventory.index') }}";

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
            window.location.href = "{{ route('inventory.index') }}?page=" + page;
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

@endsection --}}


@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-box-seam me-2"></i>Chemical Inventory</h2>
        @can('create', App\Models\Chemical::class)
            <a href="{{ url('/i/createChemical') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>Add Chemical
            </a>
        @endcan
    </div>

    {{-- Search + Filter --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="search" class="form-control border-start-0" placeholder="Search chemicals..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                            Filter Columns
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-3" style="min-width: 250px;">
                            @foreach ([
                                'chemical_name' => 'Chemical Name',
                                'CAS_number' => 'CAS Number',
                                'empirical_formula' => 'Empirical Formula',
                                'ec_number' => 'EC Number',
                                'molecular_weight' => 'Mol. Weight'
                            ] as $col => $label)
                                <li>
                                    <label class="form-check">
                                        <input class="form-check-input filter-toggle" type="checkbox" data-column="{{ $col }}" checked>
                                        {{ $label }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Table --}}
    <div class="card shadow-sm" id="js-inventory-results">
        @include('inventories._search', ['chemicals' => $chemicals])
    </div>
</div>

{{-- Styles --}}
<style>
    body {
        background-color: #f8f9fa;
        font-size: 1.1rem; /* Increase base font size */
    }

    h2 {
        font-size: 1.75rem;
    }

    .form-control,
    .btn,
    .dropdown-toggle {
        font-size: 1.1rem;
    }

    .table thead th,
    .table tbody td {
        font-size: 1.05rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f3f5;
    }

    .dropdown-menu label {
        cursor: pointer;
        font-size: 1.05rem;
    }

    .card {
        border-radius: 12px;
    }

    .btn {
        border-radius: 8px;
    }

    .pagination {
        font-size: 1.1rem;
    }
</style>


{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function fetchResults(query = "", page = 1, filters = []) {
            $.ajax({
                url: "{{ route('inventory.index') }}",
                type: "GET",
                data: { search: query, page: page, filters: filters },
                success: function (data) {
                    $('#js-inventory-results').html(data);
                    applyColumnVisibility(filters);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        $('#search').on('keyup', function () {
            let query = $(this).val().trim();
            let filters = getSelectedFilters();

            if (query === "") {
                fetchResults("", 1, filters);
            } else {
                fetchResults(query, 1, filters);
            }
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            let query = $('#search').val().trim();
            let filters = getSelectedFilters();

            if (query === "") {
                window.location.href = "{{ route('inventory.index') }}?page=" + page;
            } else {
                fetchResults(query, page, filters);
            }
        });

        $('.filter-toggle').on('change', function () {
            applyColumnVisibility(getSelectedFilters());
        });

        function getSelectedFilters() {
            let filters = [];
            $('.filter-toggle:checked').each(function () {
                filters.push($(this).data('column'));
            });
            return filters;
        }

        function applyColumnVisibility(filters) {
            $('th, td').not('.always-visible').hide(); // Hide all except actions
            filters.forEach(column => {
                $('.col-' + column).show(); // Show only filtered columns
            });
        }
    });
</script>
@endsection
