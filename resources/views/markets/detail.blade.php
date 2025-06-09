@extends('layouts.app')

@section('content')
<div class="container my-5">

    <div class="mx-2">
        <a href="{{ route('market.index') }}" class="btn btn-lg btn-secondary px-4 shadow-sm">Back</a>
    </div>
    <h2 class="mb-4 text-center fw-bold"><i class="bi bi-shop-window me-2"></i>Chemical Demand</h2>

    {{-- Success Alert --}}
    {{-- <div class="row" id="paypal-success" style="display: none;">
        <div class="col-md-8 offset-md-2">
            <div class="alert alert-success shadow-sm text-center">
                <strong>✅ Payment Successful!</strong>
            </div>
        </div>
    </div> --}}

    {{-- Chemical Info Card --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="row g-4 align-items-center">

            {{-- Image Section --}}
            {{-- <div class="col-md-4 text-center"> --}}
            <div class="col-md-3 p-3 text-center">
                {{-- <p class="fw-semibold">Molecular Structure</p> --}}
                <img src="{{ $markets->chemical->structure() }}" class="img-fluid" alt="Chemical Structure">
            </div>

            {{-- Info Section --}}
            <div class="col-md p-4">
                <h3 class="fw-bold mb-3">{{ $markets->chemical->chemical_name }}</h3>

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>CAS Number:</strong> {{ $markets->chemical->CAS_number }}</li>
                    <li class="list-group-item"><strong>Description:</strong> {{ $markets->inventory->description }}</li>
                    <li class="list-group-item"><strong>Quantity Needed:</strong> {{ $markets->quantity_needed }}</li>
                    <li class="list-group-item"><strong>Notes:</strong> {{ $markets->notes }}</li>
                    {{-- <li class="list-group-item"><strong>Price:</strong> {{ $markets->currency }} {{ number_format($markets->price, 2) }}</li> --}}
                </ul>

                {{-- Add to Cart --}}
                {{-- @can('buy', App\Models\Market::class)
                    <form action="{{ route('cart.add', $markets->id) }}" method="POST" class="d-flex align-items-center gap-2 mt-3">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1" max="{{ $stockLeft }}" class="form-control w-25">
                        <button type="submit" class="btn btn-primary shadow-sm" {{ $stockLeft <= 0 ? 'disabled' : '' }}><i class="bi bi-cart me-6"></i>Add to Shopping List</button>
                    </form>
                    <p>Stock left: {{ $markets->stock - ($existingQuantity ?? 0) }}</p>
                @endcan --}}

                {{-- edit --}}
                <div class="mt-3 d-flex gap-2">
                    @can('update', $markets)
                        <a href="/m/{{$markets->id}}/edit" class="btn btn-outline-success"><i class="bi bi-pencil-fill me-2"></i>Edit Demand</a>
                    @endcan
                    @can('delete', $markets)
                        <form action="{{ url('m/'.$markets->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill me-2"></i>Delete Demand</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" id="search" class="form-control flex-grow-1" style="max-width: 400px" placeholder="Search containers..." value="{{ request('search') }}">
            </div>
        </div>

        @can('create', App\Models\Bid::class)
            <a href="{{ url('/m/'.$markets->id.'/bid') }}" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i>Add Offer</a>
        @endcan
    </div>



    <div id="js-market-results">
        @include('markets._searchDetail', ['markets' => $markets])
    </div>

    {{-- PayPal --}}
    {{-- <div class="mt-4" id="payment_options"></div>
    <input type="hidden" id="market-id" value="{{ $markets->id }}"> --}}

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function(){
    function fetchResults(query = "", page = 1, filters = []) {
        let url = "{{ route('market.detail', ['markets' => $markets->id]) }}";

        // // 🔹 Prevent redirecting to inventory/search when search is empty
        // if (query === "" && page === 1) {
        //     window.location.href = "{{ route('inventory.index') }}";
        //     return;
        // }

        $.ajax({
            url: url,
            type: "GET",
            data: { search: query, page: page, filters: filters },
            success: function(data) {
                $('#js-market-results').html(data);
                // $('#search').val(query);
                applyColumnVisibility(filters);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    //Search Function (Only triggers AJAX if there's input)
    $('#search').on('keyup', function() {
        let query = $(this).val().trim();
        let filters = getSelectedFilters();

        if (query === "") {
            fetchResults("", 1, filters);
        } else {
            fetchResults(query, 1, filters);
        }
    });

    //Handle Pagination (Prevents direct redirection)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        let query = $('#search').val().trim();
        let filters = getSelectedFilters();

        if (query === "") {
            window.location.href = "{{ route('market.detail', ['markets' => $markets->id]) }}?page=" + page;
        } else {
            fetchResults(query, page, filters);
        }
    });

    //Toggle Filters
    $('.filter-toggle').on('change', function() {
        let filters = getSelectedFilters();
        applyColumnVisibility(filters);
    });

    //Get Selected Filters
    function getSelectedFilters() {
        let filters = [];
        $('.filter-toggle:checked').each(function() {
            filters.push($(this).data('column'));
        });
        return filters;
    }

    //Apply Column Visibility
    function applyColumnVisibility(filters) {
        $('th, td').hide(); // Hide all columns
        filters.forEach(column => {
            $('.col-' + column).show(); // Show selected columns
        });
    }
});
</script>



{{-- PayPal JS --}}
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency={{ $markets->currency }}&intent=capture"></script>

<script>
    paypal.Buttons({
        createOrder: function () {
            return fetch("/m/create/paypal/" + document.getElementById("market-id").value)
                .then((response) => response.text())
                .then((id) => id);
        },
        onApprove: function () {
            return fetch("/m/complete/" + document.getElementById("market-id").value, {
                method: "post",
                headers: {
                    "X-CSRF-Token": '{{ csrf_token() }}',
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(order_details => {
                document.getElementById("paypal-success").style.display = 'block';
            })
            .catch(error => console.error(error));
        },
        onCancel: function (data) {
            console.warn('Payment cancelled', data);
        },
        onError: function (err) {
            console.error('Payment error', err);
        }
    }).render('#payment_options');
</script>
@endsection
