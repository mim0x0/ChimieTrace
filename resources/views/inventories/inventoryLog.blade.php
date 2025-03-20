@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory Log</h2>

    @foreach ($inventoryUsage as $i)
        <div class="card p-3 mt-3 bg-light">
            <div class="row">
                <div class="col-md">
                    <p><strong>Chemical Name:</strong> {{ $i->inventory->chemical->chemical_name }}</p>
                    <p><strong>Reason of Use:</strong> {{ $i->reason }}</p>
                    <p><strong>Quantity Used:</strong> {{ $i->quantity_used }}</p>
                    <p><strong>User:</strong> {{ $i->user->name }}</p>
                </div>
            </div>
        </div>
    @endforeach

    <div class="mt-3">
        {{ $inventoryUsage->appends(request()->except('page'))->links() }}
    </div>

</div>
@endsection
