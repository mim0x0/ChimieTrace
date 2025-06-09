@if($activities->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm rounded">
            <thead class="table-light">
                <tr>
                    <th><i class="bi bi-calendar me-2"></i>Date</th>
                    <th><i class="bi bi-people me-2"></i>User</th>
                    <th><i class="bi bi-calendar-event me-2"></i>Event</th>
                    <th><i class="bi bi-chat-left-text me-2"></i>Description</th>
                    <th><i class="bi bi-info-circle me-2"></i>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $activity->properties['custom']['causer_name'] ?? '' }}</td>
                        <td class="text-capitalize">{{ $activity->event }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>
                            @if ($activity->properties->has('attributes') && $activity->properties->has('old'))
                                @foreach ($activity->properties['attributes'] as $key => $new)
                                    @php
                                        $old = $activity->properties['old'][$key] ?? 'N/A';
                                    @endphp
                                    <div><strong>{{ $key }}:</strong> <span class="text-danger">'{{ $old }}'</span> → <span class="text-success">'{{ $new }}'</span></div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{-- {{ $activities->links('vendor.pagination.bootstrap-5') }} --}}
        {{ $activities->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info">No results found.</div>
@endif
