@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profile

                    @can('update', $user->profile)
                        <a href="/profile/{{ $user->id }}/edit" class="">Edit Profile</a>
                    @endcan

                </div>

                <div class="card-body flex">
                    {{-- <!-- @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <strong>wef</strong>
                    qdwq --> --}}

                    <div>
                        <img src="{{ $user->profile->profilePic() }}" class="img-fluid" alt="Chemical Image">
                    </div>

                    <div>
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Status:</strong> {{{ $user->profile->status }}}</p>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
