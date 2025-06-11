@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- <div class="card shadow-sm">
        <div class="card-body"> --}}
            <h2 class="h4 mb-3"><i class="bi bi-journal-text me-2"></i>Activity Logs</h2>

            {{-- Filter Buttons --}}
            <div class="mb-3 d-flex flex-wrap gap-2">
                <a href="{{ route('miscs.logs', 'user') }}" class="btn btn-outline-primary {{ $type == 'user' ? 'active' : '' }}">Users</a>
                <a href="{{ route('miscs.logs', 'chemical') }}" class="btn btn-outline-primary {{ $type == 'chemical' ? 'active' : '' }}">Chemicals</a>
                <a href="{{ route('miscs.logs', 'inventory') }}" class="btn btn-outline-primary {{ $type == 'inventory' ? 'active' : '' }}">Inventories</a>
                <a href="{{ route('miscs.logs', 'market') }}" class="btn btn-outline-primary {{ $type == 'market' ? 'active' : '' }}">Chemical Supply</a>
                <a href="/logs/usage" class="btn btn-outline-primary {{ $type == 'usage' ? 'active' : '' }}">Container Usage</a>
            </div>

            {{-- Search Box --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" id="search" class="form-control border-start-0" placeholder="Search..." value="{{ request('search') }}">
            </div>

            {{-- Loader --}}
            <div id="loader" class="text-center py-3 d-none">
                <div class="spinner-border text-primary" role="status"></div>
            </div>

            {{-- AJAX Results --}}
            <div id="js-logs-results">
                @include('miscs._searchLog', ['activities' => $activities])
            </div>
        {{-- </div>
    </div> --}}
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
@endsection
