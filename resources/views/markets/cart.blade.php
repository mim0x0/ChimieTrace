@extends('layouts.app')

@section('content')
<div class="container my-5">

    <div class="mx-2">
        <a href="{{ route('market.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
    </div>
    <h2 class="text-center fw-bold mb-4"><i class="bi bi-cart me-2"></i>Cart</h2>

    @if($carts && $carts->count())
        @foreach($carts as $cart)
            @if($cart->items->count())
                <h3 class="mt-4">Supplier: {{ $cart->supplier->name ?? 'Unknown' }}</h3>

                <div class="table-responsive shadow-sm rounded mb-4">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price (MYR)</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Subtotal (MYR)</th>
                                <th scope="col">Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart->items as $item)
                                @php
                                    $quantity = $item->quantity;

                                    $tier = $item->bid->bulkPrices
                                        ->where('min_qty', '<=', $quantity)
                                        ->sortByDesc('min_qty')
                                        ->first();

                                    $price = $tier ? $tier->price_per_unit : $item->bid->price;
                                    $subtotal = $price * $quantity;
                                    $total += $subtotal;
                                @endphp

                                <tr>
                                    <td>
                                        <strong>{{ $item->bid->market->chemical->chemical_name }}</strong><br>
                                        <small class="text-muted">{{ $item->bid->market->inventory->serial_number }}</small>
                                    </td>
                                    <td>{{ number_format($price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="bid_id" value="{{ $item->bid_id }}">

                                            <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary">-</button>
                                            <span class="mx-2">{{ $item->quantity }}</span>
                                            <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary"
                                                @if($item->quantity >= ($item->bid->stock ?? 0)) disabled @endif>+</button>
                                        </form>
                                        <small class="text-muted">In stock: {{ $item->bid->stock }}</small>
                                    </td>
                                    <td>
                                        {{ number_format($subtotal, 2) }}<br>
                                        <small class="text-muted">
                                            {{ $tier ? 'Discount Tier ' . $tier->tier : 'Standard price' }}
                                        </small>
                                    </td>
                                    <td>{{ optional($item->bid->user->profile)->phone_number ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-2">
                        <h5>Total ({{ $cart->supplier->name ?? 'SupplierTest' }}):
                            <span class="text-success">RM {{ number_format($total, 2) }}</span>
                        </h5>
                    </div>
                </div>
                <form action="{{ route('cart.checkout', $cart->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success btn-lg mt-3 shadow-sm">
                        Checkout for {{ $cart->supplier->name ?? 'Supplier' }}
                    </button>
                </form>

            @endif
        @endforeach

        {{-- Checkout Button --}}
        {{-- <div class="d-flex justify-content-end">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <button class="btn btn-success btn-lg mt-3 shadow-sm">
                    Checkout
                </button>
            </form>
        </div> --}}
    @else
        <div class="alert alert-info text-center shadow-sm">
            <strong>Your cart is empty.</strong><br>
        </div>
    @endif


</div>
@endsection
