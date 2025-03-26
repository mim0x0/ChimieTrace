@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory</h2>

    <div class="card p-3 mt-3 bg-light">
        <div class="row">
            <!-- Chemical Image -->
            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <img src="{{ $chemical->image() }}" class="img-fluid" alt="Chemical Image">
            </div>
            <!-- Chemical Details -->
            <div class="col-md">
                <h4><strong>Chemical Name: </strong>{{ $chemical->chemical_name }}</h4>
                <p><strong>CAS Number:</strong> {{ $chemical->CAS_number }}</p>
                <p><strong>Serial Number:</strong> {{ $chemical->serial_number }}</p>
                {{-- <p><strong>Location:</strong> {{ $location }}</p>
                <p><strong>Quantity:</strong> {{ $quantity }}</p> --}}
                <p><strong>SKU:</strong> {{ $chemical->SKU }}</p>
                {{-- <p><strong>Acquired Date:</strong> {{ $acq_at }}</p>
                <p><strong>Expiry Date:</strong> {{ $exp_at }}</p> --}}
                <a href="{{ $chemical->SDS() }}" target="_blank" class="btn btn-dark btn-success">View SDS</a>
            </div>

            <div class="col-md-3">
                {{-- <img src="{{ asset('images/sample-chemical.png') }}" class="img-fluid" alt="Chemical Image"> --}}
                <p><strong>Molecular Structure:</strong></p>
                <img src="{{ $chemical->structure() }}" class="img-fluid" alt="Chemical Image">
            </div>
        </div>
    </div>

    @foreach ($inventories as $i)
        @if (strpos(auth()->user()->email, '@admin.com') !== false || $i->status != 'disabled')
            <div class="card p-3 mt-3 bg-light">
                <div class="row">
                    <div class="col-md">
                        <p><strong>Location:</strong> {{ $i->location }}</p>
                        <p><strong>Quantity:</strong> {{ $i->quantity }}</p>
                        <p><strong>Acquired Date:</strong> {{ $i->acq_at }}</p>
                        <p><strong>Expiry Date:</strong> {{ $i->exp_at }}</p>
                    </div>
                </div>

                @if(strpos(auth()->user()->email, '@admin.com') !== false && $i->status === 'sealed')
                    {{-- <form action="{{ url('/i/'.$i->id.'/unseal') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">Unseal Inventory</button>
                    </form> --}}
                    <a href="{{ url('/i/'.$i->id.'/unseal') }}" class="btn btn-sm btn-primary">Unseal Inventory</a>
                @endif

                @if(strpos(auth()->user()->email, '@admin.com') !== false && $i->status === 'disabled')
                    <form action="{{ url('/i/'.$i->id.'/delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-warning">Delete Container</button>
                    </form>
                    {{-- <a href="{{ url('/i/'.$i->id.'/delete') }}" class="btn btn-sm btn-primary">Delete Container</a> --}}
                @else
                    <button class="list-group-item list-group-item-action" onclick="window.location='{{ url('/i/'. $i->id . '/reduce') }}';" style="cursor: pointer;" @if($i->status === 'sealed') disabled @endif>
                        ➖ Use Chemical
                    </button>
                @endif

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Buy Chemical Item
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/market">
                            {{ __('From Supplier Contacts') }}
                        </a>

                        {{-- <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a> --}}
                    </div>
                </li>

                {{-- <button class="list-group-item list-group-item-action" onclick="window.location='{{ url('/i/'. $i->id . '/reduce') }}';" style="cursor: pointer;" @if($i->status === 'sealed') disabled @endif>
                    ➖ Use Chemical
                </button> --}}

                @if($i->status === 'sealed')
                <p><strong>The container is still sealed</strong></p>
                @endif
            </div>
        @endif
    @endforeach


</div>
@endsection
