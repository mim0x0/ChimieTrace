@extends('layouts.app')

@section('content')
<div class="container py-5">
    <form action="/profile/{{ $user->id }}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
        @csrf
        @method('PATCH')

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Edit Profile</h4>
                    </div>

                    <div class="card-body p-4">
                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="user_name" class="form-label fw-semibold">Name</label>
                            <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') ?? $user->name }}" required>
                            @error('user_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $user->email }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <input id="status" type="text" class="form-control @error('status') is-invalid @enderror" name="status" value="{{ old('status') ?? $user->profile->status }}" readonly>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @php
                            $role = auth()->user()->role;
                        @endphp

                        @if ($role === 'ADMIN' || $role === 'SUPPLIER')
                            {{-- Phone Number --}}
                            <div class="mb-3">
                                <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') ?? $user->profile->phone_number }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Address</label>
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') ?? $user->profile->address }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Postal --}}
                            <div class="mb-3">
                                <label for="postal" class="form-label fw-semibold">Postal</label>
                                <input id="postal" type="text" class="form-control @error('postal') is-invalid @enderror" name="postal" value="{{ old('postal') ?? $user->profile->postal }}">
                                @error('postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="mb-3">
                                <label for="city" class="form-label fw-semibold">City</label>
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') ?? $user->profile->city }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif


                        {{-- Profile Picture --}}
                        <div class="mb-4">
                            <label for="profile_pic" class="form-label fw-semibold">Profile Picture</label>
                            <input type="file" class="form-control @error('profile_pic') is-invalid @enderror" id="profile_pic" name="profile_pic">
                            @error('profile_pic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}


                        <div class="d-flex justify-content-end pt-3">
                            <div class="mx-2">
                                <a href="{{ route('profile.show', $user->id) }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary px-4">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
