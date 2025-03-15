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

        <!-- Main Inventory Table -->
        <div class="col-md-9">
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

                    <div class="row">
                        <div class="col-12">
                            {{ $inventories->links() }}
                        </div>
                    </div>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
