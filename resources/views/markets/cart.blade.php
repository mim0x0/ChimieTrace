@extends('layouts.app')

@section('content')
<div class="container my-5">

    <div class="mx-2">
        <a href="{{ route('market.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
    </div>
    <h2 class="text-center fw-bold mb-4"><i class="bi bi-cart me-2"></i>Your Shopping List</h2>

    @if($cart && $cart->items->count())
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price (MYR)</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Subtotal (MYR)</th>
                        <th scope="col">Supplier</th>
                        <th scope="col">Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart->items as $item)
                        @php
                            $subtotal = $item->market->price * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $item->market->chemical->chemical_name }}</strong><br>
                                <small class="text-muted">{{ $item->market->chemical->CAS_number }}</small>
                            </td>
                            <td>{{ number_format($item->market->price, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="market_id" value="{{ $item->market_id }}">

                                    <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary">−</button>

                                    <span class="mx-2">{{ $item->quantity }}</span>

                                    <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary"
                                        @if($item->quantity >= $item->market->stock) disabled @endif>+</button>
                                </form>
                                <small class="text-muted">In stock: {{ $item->market->stock}}</small>
                            </td>
                            <td>{{ number_format($subtotal, 2) }}</td>
                            <td>{{ $item->market->user->name ?? 'N/A' }}</td>
                            <td>{{ $item->market->user->profile->phone_number ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Total --}}
        <div class="d-flex justify-content-end mt-3">
            <h4>Total: <span class="text-success">{{ number_format($total, 2) }} MYR</span></h4>
        </div>

        {{-- Checkout Button --}}
        <div class="d-flex justify-content-end">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <button class="btn btn-success btn-lg mt-3 shadow-sm">
                    Clear Shopping List
                </button>
            </form>
        </div>

    @else
        <div class="alert alert-info text-center shadow-sm">
            <strong>Your cart is empty.</strong><br>
        </div>
    @endif

</div>
@endsection
