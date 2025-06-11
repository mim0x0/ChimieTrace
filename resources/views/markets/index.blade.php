@extends('layouts.app')

@section('content')
{{-- <div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4">
        <div class="w-100 me-md-3">
            <input type="text" id="search" class="form-control form-control-lg shadow-sm rounded-pill mb-3" placeholder="🔍 Search chemicals..." value="{{ request('search') }}">

            <div class="dropdown">
                <button class="btn btn-outline-primary rounded-pill shadow-sm px-4" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-sliders"></i> Filter Columns
                </button>
                <ul class="dropdown-menu p-3 rounded shadow" aria-labelledby="filterDropdown">
                    <li><label><input type="checkbox" class="filter-toggle me-2" data-column="chemical_name" checked> Chemical Name</label></li>
                    <li><label><input type="checkbox" class="filter-toggle me-2" data-column="description" checked> Description</label></li>
                    <li><label><input type="checkbox" class="filter-toggle me-2" data-column="brand" checked> Brand</label></li>
                    <li><label><input type="checkbox" class="filter-toggle me-2" data-column="price" checked> Price</label></li>
                    <li><label><input type="checkbox" class="filter-toggle me-2" data-column="stock" checked> Stock</label></li>
                </ul>
            </div>
        </div>

        <div class="d-flex flex-column align-items-end mt-3 mt-md-0">
            @can('create', App\Models\Market::class)
                <a href="{{ url('/m/create') }}" class="btn btn-success rounded-pill shadow-sm mb-2">+ Create Chemical Supply</a>
            @endcan
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">🛒 View Cart</a>
        </div>
    </div>

    <div id="market-results">
        @include('markets._search', ['markets' => $markets])
    </div>
</div> --}}
{{-- ---------------------------------------------------- --}}
<div class="container py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-shop-window me-2"></i>Chemical Supply</h2>
        <div class="d-flex flex-column align-items-end ms-auto me-2">
            @can('buy', App\Models\Market::class)
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm"><i class="bi bi-cart me-2"></i>View Cart</a>
            @endcan
        </div>
        @can('create', App\Models\Market::class)
        <a href="{{ url('/m/create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle me-2"></i>Add Chemical Request
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
                                'variant' => 'Variant',
                                'quantity_needed' => 'Quantity Needed',
                                'stock_needed' => 'Stock Needed',
                                // 'stock' => 'Stock'
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
    <div class="card shadow-sm" id="js-market-results">
        @include('markets._search', ['markets' => $markets])
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


{{-- market search function --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function fetchResults(query = "", page = 1, filters = []) {
            $.ajax({
                url: "{{ route('market.index') }}",
                type: "GET",
                data: { search: query, page: page, filters: filters },
                success: function (data) {
                    $('#js-market-results').html(data);
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
                window.location.href = "{{ route('market.index') }}?page=" + page;
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
