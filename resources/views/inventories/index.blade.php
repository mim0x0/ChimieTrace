@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar
        <div class="col-md-3">
            <div class="list-group">
                <button class="list-group-item list-group-item-action">
                    🔍 Search
                </button>
                <button class="list-group-item list-group-item-action">
                    📦 Inventory
                </button>
                <button class="list-group-item list-group-item-action" onclick="window.location='{{ url('/i/create') }}';" style="cursor: pointer;">
                    ➕ Add Container
                </button>
            </div>
        </div> -->

        <input type="text" id="search" class="form-control mb-3" placeholder="🔍 Search chemicals...">

        <div class="dropdown mb-3">
            <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Select Filters
            </button>
            <ul class="dropdown-menu p-3" aria-labelledby="filterDropdown">
                <li><label><input type="checkbox" class="filter-toggle" data-column="chemical_name" checked> Chemical Name</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="CAS_number" checked> CAS Number</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="serial_number" checked> Serial Number</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="location" checked> Location</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="quantity" checked> Quantity</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="SKU" checked> SKU</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="exp_at" checked> Expiry Date</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="acq_at" checked> Acquired Date</label></li>
            </ul>
        </div>

        <div id="inventory-results">
            @include('inventories.search', ['inventories' => $inventories])
        </div>

        <!-- Main Inventory Table -->
        {{-- <div class="col-md-9">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Chemical Name</th>
                        <th>Quantity</th>
                        <th>SKU</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventories as $inventory)
                        <tr onclick="window.location='{{ url('/i/' . $inventory->id) }}';" style="cursor: pointer;">
                            <td>{{ $inventory->chemical_name }}</td>
                            <td>{{ $inventory->quantity }}</td>
                            <td>{{ $inventory->SKU }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div> --}}
    </div>
</div>
@endsection
