@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Purchase Orders</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center shadow-sm">
            <strong>No purchase orders found.</strong><br>
        </div>
    @else
        @foreach($orders as $order)
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <div>
                    <strong>{{ $order->user->name }}</strong>  <br>
                    <small class="text-muted">Address: {{ $order->user->profile->address ?? 'N/A' }}</small><br>
                    <small class="text-muted">City: {{ $order->user->profile->city ?? 'N/A' }}</small><br>
                    <small class="text-muted">Postal: {{ $order->user->profile->postal ?? 'N/A' }}</small><br>
                    <small class="text-muted">Email: {{ $order->user->email ?? 'N/A' }}</small><br>
                    <small class="text-muted">Contact: {{ $order->user->profile->phone_number ?? 'N/A' }}</small><br>
                </div>
                <div>
                    @if($order->status === 'pending')
                        <span class="badge bg-warning text-dark ms-2">Pending</span>
                    @elseif ($order->status === 'rejected')
                        <span class="badge bg-secondary ms-2">Rejected</span>
                    @else
                        <span class="badge bg-success ms-2">Accepted</span>
                    @endif
                </div>
            </div>
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <div>
                    <strong>Bill To</strong> <br>
                    {{-- <small class="text-muted">{{ $order->supplier_phone ?? 'N/A' }}</small> --}}
                    <strong>{{ $order->supplier_name }}</strong>  <br>
                    <small class="text-muted">Address: {{ $order->supplier->profile->address ?? 'N/A' }}</small><br>
                    <small class="text-muted">City: {{ $order->supplier->profile->city ?? 'N/A' }}</small><br>
                    <small class="text-muted">Postal: {{ $order->supplier->profile->postal ?? 'N/A' }}</small><br>
                    <small class="text-muted">Email: {{ $order->supplier->email ?? 'N/A' }}</small><br>
                    <small class="text-muted">Contact: {{ $order->supplier->profile->phone_number ?? 'N/A' }}</small><br>
                </div>
                <div>
                    <strong>Order ID:</strong> {{ $order->po_number }} <br>
                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small><br>
                     @if ($order->delivery_date && $order->status === 'accepted')
                        <small class="text-success">Delivery by: {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}</small>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Chemical</th>
                                <th>Variant</th>
                                <th>Unit Price (MYR)</th>
                                <th>Discount Tier</th>
                                <th>Quantity per unit</th>
                                <th>Stock</th>
                                <th>Subtotal (MYR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->chemical_name }}</td>
                                <td>{{ $item->variant }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->tier ? 'Tier ' . $item->tier : 'Standard Price' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end p-3 border-top">
                    <h5>Total: <span class="text-success">{{ number_format($order->total, 2) }} MYR</span></h5>
                </div>
            </div>
            @php
                $user = auth()->user();
            @endphp
            @if ($user->role === config('roles.supplier') && $order->supplier_id === $user->id)
                <div class="card-footer bg-white border-top">
                    <form action="{{ route('orders.respond', $order->id) }}" method="POST" class="row g-2 align-items-center">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-4">
                            <label class="form-label mb-0">Response</label>
                            <select name="status" class="form-select" onchange="toggleDeliveryInput(this)">
                                <option value="" selected disabled>-- Select Response --</option>
                                <option value="accepted">Accept</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-none" id="delivery-time-group">
                            <label class="form-label mb-0">Delivery Time</label>
                            <input id="delivery_date" type="date" name="delivery_date" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-dark w-100 mt-4">
                                <i class="bi bi-check-circle me-1"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            @elseif ($user->role === config('roles.admin') && $order->user_id === $user->id && ($order->status === 'accepted' || $order->status === 'rejected'))
                <div class="card-footer bg-white border-top">
                    @if($order->status !== 'done')
                        <form action="{{ route('orders.markDone', $order->id) }}" method="POST" onsubmit="return confirm('Mark this order as done?');">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-success mt-2" type="submit">
                                <i class="bi bi-check2-circle me-1"></i> Mark as Done
                            </button>
                        </form>
                    @endif
                </div>
            @endif

        </div>
        @endforeach
    @endif
</div>

<script>
    function toggleDeliveryInput(selectElement) {
        const deliveryGroup = selectElement.closest('form').querySelector('#delivery-time-group');
        if (selectElement.value === 'accepted') {
            deliveryGroup.classList.remove('d-none');
        } else {
            deliveryGroup.classList.add('d-none');
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deliveryInput = document.getElementById('delivery_date');

        if (deliveryInput) {
            const today = new Date();
            today.setMinutes(today.getMinutes() - today.getTimezoneOffset()); // Convert to local ISO format
            const localToday = today.toISOString().split('T')[0];

            deliveryInput.setAttribute('min', localToday);
        }
    });
</script>


@endsection
