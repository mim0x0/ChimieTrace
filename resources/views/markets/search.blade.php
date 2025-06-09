





@if($markets->count() > 0)
    <h3>Available Supplier Prices</h3>
    <table class="table table-bordered" >
        <thead>
            <tr>
                <th class="col-chemical_name">Chemical Name</th>
                <th class="col-description">Description</th>
                <th class="col-brand">Brand</th>
                <th class="col-price">Price</th>
                <th class="col-stock">stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($markets as $market)
                <tr onclick="window.location='{{ url('/m/' . $market->id) }}';" style="cursor: pointer;">
                    <td class="col-chemical_name">{{ $market->chemical->chemical_name }}</td>
                    <td class="col-description">{{ $market->description }}</td>
                    <td class="col-brand">{{ $market->user->name }}</td>
                    <td class="col-price">{{ $market->currency }} {{ number_format($market->price, 2) }}</td>
                    <td class="col-stock">{{ $market->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <table class="table">
        <thead>
            <tr>
                <th>Chemical</th>
                <th>Description</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Stock Available</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($markets as $market)
                <tr onclick="window.location='{{ url('/m/'.$market->id) }}';" style="cursor: pointer;">
                    <td>{{ $market->chemical->chemical_name }}</td>
                    <td>{{ $market->description }}</td>
                    <td>{{ $market->user->name }}</td>
                    <td>{{ $market->currency }} {{ number_format($market->price, 2) }}</td>
                    <td>{{ $market->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

    <div class="mt-3">
        {{ $markets->appends(request()->except('page'))->links() }}
    </div>

@else
    <p>No results found</p>
@endif
