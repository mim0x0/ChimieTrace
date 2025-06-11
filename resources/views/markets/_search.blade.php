@if($markets->count() > 0)
    {{-- <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm bg-white rounded">
            <thead class="table-light">
                <tr>
                    <th class="col-chemical_name">Chemical</th>
                    <th class="col-description">Description</th>
                    <th class="col-brand">Supplier</th>
                    <th class="col-price">Price</th>
                    <th class="col-stock">Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($markets as $market)
                    <tr onclick="window.location='{{ url('/m/' . $market->id) }}';" style="cursor: pointer;">
                        <td class="col-chemical_name fw-semibold">{{ $market->chemical->chemical_name }}</td>
                        <td class="col-description text-muted">{{ $market->description }}</td>
                        <td class="col-brand">{{ $market->user->name }}</td>
                        <td class="col-price text-success fw-bold">{{ $market->currency }} {{ number_format($market->price, 2) }}</td>
                        <td class="col-stock">{{ $market->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $markets->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div> --}}


    <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="col-chemical_name">Chemical Name</th>
                            <th class="col-description">Variant</th>
                            <th class="col-quantity_needed">Quantity Needed</th>
                            <th class="col-notes">Stock Needed</th>
                            {{-- <th class="col-stock">Stock</th> --}}
                            <th class="always-visible">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($markets as $market)
                            <tr>
                                <td class="col-chemical_name">{{ $market->chemical->chemical_name ?? '' }}</td>
                                <td class="col-description">{{ $market->inventory->serial_number ?? '' }}</td>
                                <td class="col-quantity_needed">{{ $market->quantity_needed }} {{ $market->unit }}</td>
                                <td class="col-notes">{{ $market->stock_needed }}</td>
                                {{-- <td class="col-stock">{{ $market->stock }}</td> --}}
                                <td class="always-visible">
                                    <a href="{{ url('/m/' . $market->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{-- {{ $chemicals->withQueryString()->links() }} --}}
                {{ $markets->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
@else
    <div class="alert alert-warning shadow-sm">
        No results found
    </div>
@endif
