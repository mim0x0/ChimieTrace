@if($inventoryUsage->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm rounded">
            <thead class="table-light">
                <tr>
                    <th><i class="bi bi-calendar me-2"></i>Date</th>
                    <th><i class="bi bi-people me-2"></i>User</th>
                    <th><i class="bi bi-box me-2"></i>Container</th>
                    <th><i class="bi bi-chat-left-text me-2"></i>Reason</th>
                    <th><i class="bi bi-123 me-2"></i>Quantity Used</th>
                    <th><i class="bi bi-123 me-2"></i>Quantity Left</th>
                    <th><i class="bi bi-123 me-2"></i>Container Left</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventoryUsage as $i)
                    <tr>
                        <td>{{ $i->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $i->user_name ?? 'System' }}</td>
                        <td class="text-capitalize">{{ $i->chemical_name }} - {{ $i->chemical_cas }} ({{ $i->inventory_serial }})</td>
                        <td>{{ $i->reason }}</td>
                        <td>{{ $i->quantity_used ?? '' }}</td>
                        <td>{{ $i->quantity_left ?? '' }}</td>
                        <td>{{ $i->container_left ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{-- {{ $activities->links('vendor.pagination.bootstrap-5') }} --}}
        {{ $inventoryUsage->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info">No results found.</div>
@endif
