



@if($inventories->count() > 0)
    @foreach ($inventories as $i)
        @if (auth()->user()->role === config('roles.admin') || $i->status != 'disabled')
            <div class="card shadow-lg mb-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">
                            Variant: {{ $i->serial_number }} #{{ $i->container_number }}
                            @if($i->status === 'sealed')
                                <span class="badge bg-warning text-dark ms-2">Sealed</span>
                            @elseif($i->status === 'empty')
                                <span class="badge bg-secondary ms-2">Empty</span>
                            @else
                                <span class="badge bg-success ms-2">Opened</span>
                            @endif
                        </h5>

                        @if((auth()->user()->role === config('roles.admin') || strpos(auth()->user()->email, config('roles.lecturer')) !== false) && $i->status === 'sealed')
                            <a href="{{ url('/i/'.$i->id.'/unseal') }}" class="btn btn-sm btn-outline-warning">Unseal</a>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            {{-- <p><strong>Variant:</strong> {{ $i->serial_number }}</p> --}}
                            {{-- <p><strong>Description:</strong> {{ $i->description }}</p> --}}
                            <p><strong>Location:</strong> {{ $i->location }}</p>
                            <p><strong>Quantity:</strong> {{ $i->quantity }} {{ $i->unit }}</p>
                            <p><strong>Threshold:</strong> {{ $i->min_quantity }} {{ $i->unit }}</p>
                            <p><strong>Brand:</strong> {{ $i->brand }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Acquired:</strong> {{ $i->acq_at }}</p>
                            <p><strong>Expiry:</strong> {{ $i->exp_at }}</p>
                            <p><strong>Notes:</strong> {{ $i->notes }}</p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @if(auth()->user()->role === config('roles.admin') && $i->status === 'empty')
                            <form action="{{ url('/i/'.$i->id.'/delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this container?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash-fill me-2"></i>Delete Container</button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary"
                                    onclick="window.location='{{ url('/i/'. $i->id . '/reduce') }}';"
                                    @if($i->status === 'sealed') disabled @endif>
                                <i class="bi bi-play-fill me-2"></i>Use Chemical
                            </button>
                        @endif

                        @can('update', $i)
                            <a href="/i/i/{{$i->id}}/edit" class="btn btn-outline-success"><i class="bi bi-pencil-fill me-2"></i>Edit Container</a>
                        @endcan

                        @can('create', App\Models\Inventory::class)
                            <a href="{{ url('/i/addInventory/'.$i->id) }}" class="btn btn-outline-primary"><i class="bi bi-plus-circle me-2"></i>Top Up Container</a>
                        @endcan
                    </div>

                </div>
            </div>
        @endif
    @endforeach

    <div class="mt-4">
        {{ $inventories->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info">No results found.</div>
@endif


<style>
    .card-title {
        font-weight: 600;
    }

    .btn {
        border-radius: 8px;
        min-width: 120px;
    }

    .card-body p {
        margin-bottom: 0.5rem;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.6em;
        border-radius: 0.5rem;
    }

</style>
