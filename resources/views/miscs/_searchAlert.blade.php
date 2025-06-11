@if($alerts->count() > 0)
    @foreach ($alerts as $alert)
        <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm p-3 mb-3 rounded">
            <div>
                <div class="fw-semibold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $alert->message }}
                </div>
            </div>

            <div class="d-flex flex-column align-items-end ms-auto">
                <form action="{{ route('alerts.increment', $alert->id) }}" method="POST" class="d-inline">
                    @csrf
                    @if($alert->userRequest && $alert->inventory_id && $alert->userRequest->item_id !== -1)
                        <button class="btn btn-sm btn-dark mt-2 rounded-pill">
                            <i class="bi bi-shop-window me-1"></i> Create Chemical Request
                        </button>
                    @elseif (($alert->userRequest->type === 'chemical' || $alert->userRequest->type === 'inventory') && $alert->userRequest->item_id !== -1)
                        <button class="btn btn-sm btn-dark mt-2 rounded-pill">
                            <i class="bi bi-box me-1"></i> Go To Inventory
                        </button>
                    @elseif ($alert->userRequest->type === 'market' && $alert->userRequest->item_id === -2)
                        <button class="btn btn-sm btn-dark mt-2 rounded-pill">
                            <i class="bi bi-shop-window me-1"></i> Go To PO
                        </button>
                    @elseif ($alert->userRequest->type === 'market' && $alert->userRequest->item_id !== -1)
                        <button class="btn btn-sm btn-dark mt-2 rounded-pill">
                            <i class="bi bi-shop-window me-1"></i> Go To Market
                        </button>
                    @elseif ($alert->userRequest->type === 'user' && $alert->userRequest->item_id !== -1)
                        <button class="btn btn-sm btn-dark mt-2 rounded-pill">
                            <i class="bi bi-person-lines-fill me-1"></i> View User
                        </button>
                    @endif
                </form>
            </div>
            @if ($alert->current_containers !== null || $alert->userRequest->item_id === -1)
                <a href="/alerts/{{ $alert->id }}/read" class="btn btn-sm btn-outline-dark rounded-pill ms-2">
                    <i class="bi bi-check2-circle me-1"></i>
                </a>
            @endif

            {{-- @if($alert->userRequest && $alert->inventory_id)
                <a href="{{ route('market.createRe', ['inventory_id' => $alert->inventory_id]) }}"
                    class="btn btn-sm btn-primary mt-2 rounded-pill">
                    <i class="bi bi-shop-window me-1"></i> Create Demand
                </a>
            @elseif ($alert->userRequest->type === 'chemical' || $alert->userRequest->type === 'inventory')
                <a href="{{ route('inventory.detail', ['chemical' => $alert->userRequest->item_id]) }}"
                    class="btn btn-sm btn-primary mt-2 rounded-pill">
                    <i class="bi bi-box me-1"></i> GoTo Inventory
                </a>
            @elseif ($alert->userRequest->type === 'market')
                <a href="{{ route('market.detail', ['markets' => $alert->userRequest->item_id]) }}"
                    class="btn btn-sm btn-primary mt-2 rounded-pill">
                    <i class="bi bi-shop-window me-1"></i> GoTo Market
                </a>
            @elseif ($alert->userRequest->type === 'user')
                <a href="{{ route('admin.viewUsers') }}"
                    class="btn btn-sm btn-dark mt-2 rounded-pill">
                    <i class="bi bi-person-lines-fill me-1"></i> View User
                </a>
            @else
                <a href="/alerts/{{ $alert->id }}/read" class="btn btn-sm btn-outline-dark rounded-pill">
                    <i class="bi bi-check2-circle me-1"></i> Mark as Read
                </a>
            @endif --}}

        </div>
    @endforeach

    <div class="mt-4">
        {{ $alerts->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill me-2"></i> No alerts found.
    </div>
@endif
