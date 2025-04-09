@extends('layouts.app')

@section('content')
<div class="container">
<form action="/profile/{{ $user->id }}" enctype="multipart/form-data" method="post">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-8 offset-2">

                <div class="row">
                    <h1>Edit Profile</h1>
                </div>

                <div class="row mb-3">
                    <label for="user_name" class="col-md-4 col-form-label">Name</label>

                    <input id="user_name"
                            type="text"
                            class="form-control @error('user_name') is-invalid @enderror"
                            name="user_name"
                            value="{{ old('user_name') ?? $user->name }}"
                            required autocomplete="user_name" autofocus>

                    @error('user_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label">Email</label>

                    <input id="email"
                            type="text"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') ?? $user->email }}"
                            required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="row mb-3">
                    <label for="status" class="col-md-4 col-form-label">Status</label>

                    <input id="status"
                            type="text"
                            class="form-control @error('status') is-invalid @enderror"
                            name="status"
                            value="{{ old('status') ?? $user->profile->status }}"
                            required autocomplete="status" autofocus>

                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="row mb-3">
                    <label for="phone_number" class="col-md-4 col-form-label">Phone Number</label>

                    <input id="phone_number"
                            type="text"
                            class="form-control @error('phone_number') is-invalid @enderror"
                            name="phone_number"
                            value="{{ old('phone_number') ?? $user->profile->phone_number }}"
                            required autocomplete="phone_number" autofocus>

                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                {{-- <div class="row mb-3">
                    <label for="score" class="col-md-4 col-form-label">Score</label>

                    <input id="score"
                            type="text"
                            class="form-control @error('score') is-invalid @enderror"
                            name="score"
                            value="{{ old('score') ?? $user->profile->score }}"
                            required autocomplete="score" autofocus>

                    @error('score')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div> --}}

                <div class="row">
                    <label for="profile_pic" class="col-md-4 col-form-label">Profile Picture</label>
                    <input type="file" class="form-input" id="profile_pic" name="profile_pic">

                    @error('profile_pic')
                        <!-- <span class="invalid-feedback" role="alert"> -->
                            <strong>{{ $message }}</strong>
                        <!-- </span> -->
                    @enderror
                </div>

                <div class="row pt-4 col-2">
                    <button class="btn btn-primary">Save Profile</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
