@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory Alerts</h2>

    @foreach ($alerts as $alert)
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $alert->message }}</strong>
            </div>
            <a href="/i/alerts/{{$alert->id}}/read" class="btn btn-sm btn-primary">Mark as Read</a>
        </div>
    @endforeach

    @if($alerts->isEmpty())
        <p>No alerts</p>
    @endif

    <div class="mt-3">
        {{ $alerts->appends(request()->except('page'))->links() }}
    </div>

</div>
@endsection
