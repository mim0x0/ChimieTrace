



@if($bids->count() > 0)
    @foreach ($bids as $b)
        {{-- @if (auth()->user()->role === config('roles.admin') || $i->status != 'disabled') --}}
        <div class="card shadow-lg mb-4 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">
                        Supplier: {{ $b->user->name }}
                        {{-- @if($b->status === 'pending')
                            <span class="badge bg-warning text-dark ms-2">Pending</span>
                        @else
                            <span class="badge bg-success ms-2">Accepted</span>
                        @endif --}}
                    </h5>

                    {{-- @if((auth()->user()->role === config('roles.admin')) && $b->status === 'pending')
                        <a href="{{ route('market.accept', $b->id) }}" class="btn btn-sm btn-outline-warning">Accept</a>
                    @endif --}}
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{-- <p><strong>Supplier:</strong> {{ $b->user->name }}</p> --}}
                        <p><strong>Quantity per unit:</strong> {{ $b->quantity }}</p>
                        <p><strong>Stock Available:</strong> {{ $b->stock }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Price per unit:</strong> RM{{ $b->price }}</p>
                        <p><strong>Notes:</strong> {{ $b->notes }}</p>
                    </div>
                </div>

                <fieldset class="border rounded p-3 mb-4 mt-3">
                    <legend class="float-none w-auto px-2 text-primary fw-semibold">Bulk Pricing Tiers</legend>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tier</th>
                                <th>Minimum Stock</th>
                                <th>Price per Unit (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($b->bulkPrices->sortBy('min_qty') as $tier)
                                <tr>
                                    <td>Tier {{ $tier->tier }}</td>
                                    <td>{{ $tier->min_qty }}</td>
                                    <td>RM {{ number_format($tier->price_per_unit, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </fieldset>
                <h5 class="mt-4">Bulk Price Table</h5>



                <div class="d-flex flex-wrap gap-2 mt-3">
                    @if(auth()->user()->id === $b->user_id)
                        <form action="{{ url('/m/'.$b->id.'/bid') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this container?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash-fill me-2"></i>Delete Offer</button>
                        </form>
                    {{-- @else
                        <button class="btn btn-outline-secondary"
                                onclick="window.location='{{ url('/i/'. $i->id . '/reduce') }}';"
                                @if($i->status === 'sealed') disabled @endif>
                            <i class="bi bi-play-fill me-2"></i>Use Chemical
                        </button> --}}
                    @endif

                    @if(auth()->user()->id === $b->user_id)
                        <a href="/m/{{$b->id}}/bid/edit" class="btn btn-outline-success"><i class="bi bi-pencil-fill me-2"></i>Edit Offer</a>
                    @endif

                    @can('create', App\Models\Market::class)
                        <form action="{{ route('cart.add', $b->id) }}" method="POST" class="d-flex gap-2 align-items-end">
                            @csrf
                            <div>
                                <label for="quantity" class="form-label">Quantity to Order</label>
                                <input type="number" name="quantity" class="form-control" min="1" max="{{ $b->stock }}" required>
                                {{-- <small class="text-muted">In Cart: {{ $userCartItems[$b->id]->quantity ?? 0 }}</small> --}}
                            </div>
                            <button class="btn btn-success mt-3" type="submit">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                        </form>
                    @endcan
                </div>

            </div>
        </div>
        {{-- @endif --}}
    @endforeach

    <div class="mt-4">
        {{ $bids->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
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
