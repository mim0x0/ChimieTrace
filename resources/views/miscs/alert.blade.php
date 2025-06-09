@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> Inventory Alerts</h3>
            </div>

            <div class="input-group mb-4 shadow-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" id="search" class="form-control" placeholder="Search alerts..." value="{{ request('search') }}">
            </div>

            <div id="js-alerts-results">
                @include('miscs._searchAlert', ['alerts' => $alerts])
            </div>
        </div>
    </div>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- AJAX Script --}}
<script>
    $(document).ready(function() {
        function fetchResults(query = "", page = 1, filters = []) {
            $.ajax({
                url: "{{ route('miscs.alert') }}",
                type: "GET",
                data: { search: query, page: page, filters: filters },
                success: function(data) {
                    $('#js-alerts-results').html(data);
                    applyColumnVisibility(filters);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        $('#search').on('keyup', function () {
            const query = $(this).val().trim();
            const filters = getSelectedFilters();
            fetchResults(query, 1, filters);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            const query = $('#search').val().trim();
            const filters = getSelectedFilters();
            fetchResults(query, page, filters);
        });

        function getSelectedFilters() {
            let filters = [];
            $('.filter-toggle:checked').each(function () {
                filters.push($(this).data('column'));
            });
            return filters;
        }

        function applyColumnVisibility(filters) {
            $('th, td').hide();
            filters.forEach(column => {
                $('.col-' + column).show();
            });
        }
    });
</script>
@endsection
