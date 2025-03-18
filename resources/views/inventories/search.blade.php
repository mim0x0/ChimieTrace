





@if($inventories->count() > 0)
    {{-- @foreach ($inventories as $inventory)
        <div class="card p-3 mt-3 bg-light">
            <div class="row" onclick="window.location='{{ url('/i/' . $inventory->id) }}';" style="cursor: pointer;">
                <div class="col-md-3">
                    <img src="{{ asset('storage/'.$inventory->image) }}" class="img-fluid" alt="Chemical Image">
                </div>
                <div class="col-md-9">
                    <h4><strong>{{ $inventory->chemical_name }}</strong></h4>
                    <p><strong>CAS Number:</strong> {{ $inventory->CAS_number }}</p>
                    <p><strong>Location:</strong> {{ $inventory->location }}</p>
                    <p><strong>Quantity:</strong> {{ $inventory->quantity }}</p>
                    <p><strong>Expiry Date:</strong> {{ $inventory->exp_at }}</p>
                </div>
            </div>
        </div>
    @endforeach --}}

    <table class="table table-bordered" >
        <thead>
            <tr>
                <th class="col-chemical_name">Chemical Name</th>
                <th class="col-CAS_number">CAS Number</th>
                <th class="col-serial_number">Serial Number</th>
                <th class="col-location">Location</th>
                <th class="col-quantity">Quantity</th>
                <th class="col-SKU">SKU</th>
                <th class="col-acq_at">Acquired Date</th>
                <th class="col-exp_at">Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $inventory)
                <tr onclick="window.location='{{ url('/i/' . $inventory->id) }}';" style="cursor: pointer;">
                    <td class="col-chemical_name">{{ $inventory->chemical_name }}</td>
                    <td class="col-CAS_number">{{ $inventory->CAS_number }}</td>
                    <td class="col-serial_number">{{ $inventory->serial_number }}</td>
                    <td class="col-location">{{ $inventory->location }}</td>
                    <td class="col-quantity">{{ $inventory->quantity }}</td>
                    <td class="col-SKU">{{ $inventory->SKU }}</td>
                    <td class="col-acq_at">{{ $inventory->acq_at }}</td>
                    <td class="col-exp_at">{{ $inventory->exp_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $inventories->appends(request()->except('page'))->links() }}
    </div>

@else
    <p>No results found</p>
@endif
