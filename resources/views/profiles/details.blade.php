@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    {{-- @can()
                        <div class="mx-2">
                            <a href="{{ route('inventory.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                        </div>
                    @endcan
                    @can()
                        <div class="mx-2">
                            <a href="{{ route('inventory.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                        </div>
                    @endcan --}}
                    <h5 class="mb-0">User Profile</h5>
                    @can('update', $user->profile)
                        <a href="/profile/{{ $user->id }}/edit" class="btn btn-light btn-sm">Edit Profile</a>
                    @endcan
                </div>

                <div class="card-body text-center p-4">

                    <img src="{{ $user->profile->profilePic() }}" alt="Profile Picture"
                         class="rounded-circle shadow mb-3" width="150" height="150" style="object-fit: cover;">

                    <h4 class="fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted"><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                    <p class="mb-1">
                        <strong>Status:</strong>
                        <span class="badge bg-secondary">{{ $user->profile->status }}</span>
                    </p>

                    <p class="mb-3">
                        <strong>Role:</strong>
                        <span class="badge bg-info text-dark text-uppercase">{{ $user->role }}</span>
                    </p>

                    {{-- <p class="text-muted small mb-0">
                        <i class="bi bi-clock-history"></i> Last login:
                        {{ $user->last_login_at ? $user->last_login_at->format('F j, Y g:i A') : 'Never' }}
                    </p> --}}
                </div>
            </div>

            {{-- Activity History --}}
            <div class="card mt-4 shadow-sm border-0 rounded-4">
                <div class="card-header bg-light fw-bold">
                    Recent Activity
                </div>
                <div class="card-body">
                    @if ($activities->isEmpty())
                        <p class="text-muted">No recent activity.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($activities as $activity)
                                <li class="list-group-item">
                                    <i class="bi bi-chevron-right text-primary"></i>
                                    {{ $activity->event }}    {{ $activity->description }}
                                    <small class="text-muted d-block">{{ $activity->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
