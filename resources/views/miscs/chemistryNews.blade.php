@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center text-primary"><i class="bi bi-newspaper me-2"></i>Latest News from ChemistryWorld</h1>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($items as $item)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ $item->get_permalink() }}" class="text-decoration-none text-dark" target="_blank">
                                {{ $item->get_title() }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            {!! Str::limit(strip_tags($item->get_description()), 150) !!}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <small class="text-secondary">
                            <i class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($item->get_date())->diffForHumans() }}
                        </small>
                        <a href="{{ $item->get_permalink() }}" class="btn btn-outline-primary btn-sm" target="_blank">
                            Read more →
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Bootstrap + jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function(){
        function fetchResults(query = "", page = 1) {
            let url = "{{ route('miscs.logs', ['type' => $type ?? null]) }}";
            $('#loader').removeClass('d-none');

            $.ajax({
                url: url,
                type: "GET",
                data: { search: query, page: page },
                success: function(data) {
                    $('#js-logs-results').html(data);
                    $('#loader').addClass('d-none');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $('#loader').addClass('d-none');
                }
            });
        }

        $('#search').on('keyup', function() {
            let query = $(this).val().trim();
            fetchResults(query, 1);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            let query = $('#search').val().trim();
            fetchResults(query, page);
        });
    });
</script>

{{-- <script>
    function checkAccountBalance(userId) {
        if (!userId) {
            throw new Error("User ID is required");
        }

        const user = getUserFromDatabase(userId);
        if (!user) {
            throw new Error("User not found");
        }

        if (!user.isLoggedIn) {
            throw new Error("User is not authenticated");
        }

        const account = getAccountDetails(user.accountId);
        if (!account) {
            throw new Error("Account not found");
        }

        if (account.balance < 0) {
            return `Warning: Your account balance is negative:
                    $${account.balance.toFixed(2)}`;
        }

        return `Your balance is $${account.balance.toFixed(2)}`;
    }
</script> --}}
@endsection
