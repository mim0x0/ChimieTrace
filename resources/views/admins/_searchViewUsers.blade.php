@if($users->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm rounded">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-secondary text-uppercase">
                                {{ $user->role ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $status = $user->profile->status ?? 'unknown';
                            @endphp
                            <span class="badge bg-{{ $status === 'banned' ? 'danger' : ($status === 'active' ? 'success' : 'secondary') }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            @if ($user->profile)
                                @if($user->role === config('roles.admin'))
                                    {{-- Disable ban button for admins --}}
                                    <button class="btn btn-sm btn-secondary" disabled title="Admins cannot be banned">
                                        <i class="bi bi-shield-lock"></i> Protected
                                    </button>
                                @else
                                    <form action="{{ url('/a/users/' . $user->id . '/ban') }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-{{ $user->profile->status === 'banned' ? 'success' : 'danger' }}">
                                            <i class="bi bi-{{ $user->profile->status === 'banned' ? 'person-check' : 'person-x' }}"></i>
                                            {{ $user->profile->status === 'banned' ? 'Unban' : 'Ban' }}
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">No Profile</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No users found.
    </div>
@endif
