@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <input type="text" id="search" class="form-control mb-3" placeholder="🔍 Search chemicals...">

        <div class="dropdown mb-3">
            <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Select Filters
            </button>
            <ul class="dropdown-menu p-3" aria-labelledby="filterDropdown">
                <li><label><input type="checkbox" class="filter-toggle" data-column="chemical_name" checked> Chemical Name</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="CAS_number" checked> CAS Number</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="serial_number" checked> Serial Number</label></li>
                {{-- <li><label><input type="checkbox" class="filter-toggle" data-column="location" checked> Location</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="quantity" checked> Quantity</label></li> --}}
                <li><label><input type="checkbox" class="filter-toggle" data-column="SKU" checked> SKU</label></li>
                {{-- <li><label><input type="checkbox" class="filter-toggle" data-column="exp_at" checked> Expiry Date</label></li>
                <li><label><input type="checkbox" class="filter-toggle" data-column="acq_at" checked> Acquired Date</label></li> --}}
            </ul>
        </div>

        {{-- <div id="inventory-results">
            @include('inventories.search', ['chemicals' => $chemicals])
        </div> --}}

        @if($markets->count() > 0)
            <h3>Available Supplier Prices</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Chemical</th>
                        <th>Description</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Stock Available</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($chemical->supplierPrices as $offer)
                        <tr>
                            <td>{{ $offer->supplier->company_name }}</td>
                            <td>${{ $offer->price }}</td>
                            <td>{{ $offer->stock_available }}</td>
                            <td>
                                <a href="{{ route('buy.from.supplier', $offer->id) }}" class="btn btn-success">Buy</a>
                            </td>
                        </tr>
                    @endforeach --}}
                    @foreach ($markets as $market)
                        <tr onclick="window.location='{{ url('/m/'.$market->id) }}';" style="cursor: pointer;">
                            <td>{{ $market->chemical->chemical_name }}</td>
                            <td>{{ $market->description }}</td>
                            <td>{{ $market->user->name }}</td>
                            <td>{{ $market->currency }} {{ number_format($market->price, 2) }}</td>
                            <td>{{ $market->stock }}</td>
                            <td>
                                {{-- @can('update', $markets)
                                    <a href="{{ route('market.edit', $market) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endcan --}}
                                {{-- @can('delete', $markets)
                                    <form action="{{ route('market.destroy', $market) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endcan --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $markets->appends(request()->except('page'))->links() }}
            </div>

        @else
            <p>No results found</p>
        @endif
    </div>
</div>
@endsection
