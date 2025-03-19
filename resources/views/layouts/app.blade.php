<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex" href="{{ url('/') }}">
                    <div><img src="/svg/chimieTraceLogo.svg" style="height: 30px; padding-right: 5px;" ></div>
                    <div style="padding-left: 5px">ChimieTrace</div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/profile/{{ auth()->user()->id }}">
                                        {{ __('Profile') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row">
        @auth
        <!-- Sidebar -->
        <div class="sidebar bg-light p-3" style="width: 250px; height: 100vh;">
            <ul class="list-unstyled mt-4">
                {{-- <li><a href="{{ url('/search') }}" class="btn btn-secondary w-100">🔍 Search</a></li> --}}
                <li><a href="{{ url('/inventory') }}" class="btn btn-secondary w-100 mt-2">📦 Inventory</a></li>
                <li><a href="{{ url('/i/createChemical') }}" class="btn btn-secondary w-100 mt-2">➕ Add Chemical Item</a></li>
                <li><a href="{{ url('/i/createInventory') }}" class="btn btn-secondary w-100 mt-2">➕ Add Chemical Inventory</a></li>
            </ul>
        </div>
        @endauth

        <main class="py-4 col-md">
            @yield('content')
        </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- chemical search function --}}
    <script>
        // $(document).ready(function() {
        //     $('#search').on('keyup', function() {
        //         let query = $(this).val();

        //         $.ajax({
        //             url: "{{ route('inventory.search') }}",
        //             type: "GET",
        //             data: { search: query },
        //             success: function(data) {
        //                 $('#inventory-results').html(data);
        //             }
        //         })
        //     })
        // })

        // $(document).ready(function(){
        //     function fetchResults(query = "", page = 1) {
        //         let url = query ? "{{ route('inventory.search') }}" : "{{ route('inventory.index') }}";

        //         $.ajax({
        //             url: url,
        //             type: "GET",
        //             data: { search: query, page: page },
        //             success: function(data) {
        //                 $('#inventory-results').html(data);
        //             }
        //         });
        //     }

        //     // Real-time search
        //     $('#search').on('keyup', function() {
        //         let query = $(this).val().trim();

        //         if (query === "") {
        //             window.location.href = "{{ route('inventory.index') }}"; // Redirect to normal inventory page
        //         } else {
        //             fetchResults(query);
        //         }
        //     });

        //     // Handle pagination clicks
        //     $(document).on('click', '.pagination a', function(e) {
        //         e.preventDefault();
        //         let page = $(this).attr('href').split('page=')[1];
        //         let query = $('#search').val().trim();

        //         fetchResults(query, page);
        //     });
        // });

        $(document).ready(function(){
        function fetchResults(query = "", page = 1, filters = []) {
            let url = "{{ route('inventory.search') }}";

            // 🔹 Prevent redirecting to inventory/search when search is empty
            if (query === "" && page === 1) {
                window.location.href = "{{ route('inventory.index') }}";
                return;
            }

            $.ajax({
                url: url,
                type: "GET",
                data: { search: query, page: page, filters: filters },
                success: function(data) {
                    $('#inventory-results').html(data);
                    applyColumnVisibility(filters);
                }
            });
        }

        // 🔹 Search Function (Only triggers AJAX if there's input)
        $('#search').on('keyup', function() {
            let query = $(this).val().trim();
            let filters = getSelectedFilters();

            if (query === "") {
                fetchResults("", 1, filters);
            } else {
                fetchResults(query, 1, filters);
            }
        });

        // 🔹 Handle Pagination (Prevents direct redirection)
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            let query = $('#search').val().trim();
            let filters = getSelectedFilters();

            if (query === "") {
                window.location.href = "{{ route('inventory.index') }}?page=" + page;
            } else {
                fetchResults(query, page, filters);
            }
        });

        // 🔹 Toggle Filters
        $('.filter-toggle').on('change', function() {
            let filters = getSelectedFilters();
            applyColumnVisibility(filters);
        });

        // 🔹 Get Selected Filters
        function getSelectedFilters() {
            let filters = [];
            $('.filter-toggle:checked').each(function() {
                filters.push($(this).data('column'));
            });
            return filters;
        }

        // 🔹 Apply Column Visibility
        function applyColumnVisibility(filters) {
            $('th, td').hide(); // Hide all columns
            filters.forEach(column => {
                $('.col-' + column).show(); // Show selected columns
            });
        }
    });
    </script>

</body>
</html>
