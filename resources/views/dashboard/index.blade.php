

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-sm text-gray-500">Total Chemicals</h2>
            <p class="text-2xl font-bold text-blue-600">{{ $chemicalsCount }}</p>
        </div>

        {{-- <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-sm text-gray-500">Total Orders</h2>
            <p class="text-2xl font-bold text-green-600">{{ $ordersCount }}</p>
        </div> --}}

        <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-sm text-gray-500">Suppliers</h2>
            <p class="text-2xl font-bold text-purple-600">{{ $suppliersCount }}</p>
        </div>

        {{-- <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-sm text-gray-500">Pending Deliveries</h2>
            <p class="text-2xl font-bold text-red-600">{{ $pendingDeliveries }}</p>
        </div> --}}
    </div>

    {{-- Recent Offers or Market Overview --}}
    <div class="bg-white shadow rounded-2xl p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Recent Supplier Offers</h2>
        <table class="w-full text-left border-t border-gray-200">
            <thead>
                <tr>
                    <th class="py-2">Chemical</th>
                    <th class="py-2">Supplier</th>
                    <th class="py-2">Price (MYR)</th>
                    <th class="py-2">Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOffers as $offer)
                    <tr class="border-t">
                        <td class="py-2">{{ $offer->inventory->name }}</td>
                        <td class="py-2">{{ $offer->supplier->name }}</td>
                        <td class="py-2">{{ $offer->price }}</td>
                        <td class="py-2 text-sm text-gray-500">{{ $offer->updated_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Cart Quick View --}}
    <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-lg font-semibold mb-4">Your Cart</h2>
        @if($cart && $cart->items->count())
            <ul class="divide-y">
                @foreach($cart->items as $item)
                    <li class="py-2 flex justify-between">
                        <div>
                            {{ $item->market->inventory->name }} (x{{ $item->quantity }})
                        </div>
                        <div class="text-gray-600">
                            {{ $item->market->price * $item->quantity }} MYR
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 text-right">
                <a href="{{ route('cart.index') }}" class="text-blue-500 underline">Go to Cart</a>
            </div>
        @else
            <p class="text-gray-500">Your cart is empty.</p>
        @endif
    </div>
</div>
@endsection
