<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Custom Styles -->
<style>
    body {
        font-size: 1.15rem;
        font-family: 'Nunito', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        transition: background-color 0.3s, color 0.3s;
    }

    :root {
        --bg-color: #f5f7fa;
        --text-color: #212529;
        --card-bg: #ffffff;
        --btn-bg: #e9ecef;
        --btn-hover: #d1d1d1;
    }

    [data-theme="dark"] {
        --bg-color: #121212;
        --text-color: #f1f1f1;
        --card-bg: #1e1e1e;
        --btn-bg: #333;
        --btn-hover: #444;
    }

    .navbar, .sidebar, main {
        background-color: var(--card-bg);
    }

    .navbar, .sidebar {
        border-bottom: 1px solid #dee2e6;
    }

    .navbar-brand {
        font-size: 1.6rem;
        font-weight: bold;
    }

    .btn {
        font-size: 1.1rem;
        border-radius: 8px;
    }

    /* .sidebar {
        border-right: 1px solid #dee2e6;
        min-height: 100vh;
        padding: 1rem;
    }

    .sidebar ul li a {
        display: block;
        padding: 0.75rem;
        color: var(--text-color);
        background-color: var(--btn-bg);
        margin-bottom: 0.5rem;
        border-radius: 6px;
        transition: background 0.3s;
    }

    .sidebar ul li a:hover {
        background-color: var(--btn-hover);
        color: var(--text-color);
    }

    main {
        padding: 2rem;
        border-radius: 12px;
        margin: 2rem;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
    }

    .badge {
        font-size: 1rem;
        padding: 0.4em 0.6em;
    }

    .tooltip-inner {
        font-size: 1rem;
    }

    .theme-toggle {
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0.5rem 1rem;
        border: none;
        background: none;
        color: var(--text-color);
    }

    @media (max-width: 768px) {
        .sidebar {
            display: none;
        }
        main {
            margin: 1rem;
            padding: 1rem;
        }
    } */
     #sidebar {
        width: 250px;
        transition: transform 0.3s ease;
        z-index: 1050;
    }

    @media (max-width: 768px) {
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            transform: translateX(-100%);
            background-color: var(--card-bg);
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }

        #sidebar.active {
            transform: translateX(0);
        }

        .main-content {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

</style>

</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container align-items-center">

                {{-- <div class="me-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">&larr; Back</a>
                </div> --}}

                <!-- Sidebar Toggle Button for Mobile -->
                <button class="btn btn-outline-secondary d-md-none me-2" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>

                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="/svg/chimieTraceLogo.svg" alt="Logo" style="height: 32px;" class="me-2">
                    <span>ChimieTrace</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        {{-- <li class="nav-item">
                            <button id="themeToggle" class="theme-toggle" title="Toggle dark mode">
                                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                            </button>
                        </li> --}}

                        @can('viewAny', App\Models\InventoryUsage::class)
                            @php
                                $alertCount = \App\Models\Alert::where('is_read', false)->count();
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link" href="/alerts/chemical">
                                    Alerts <span class="badge bg-danger">{{ $alertCount }}</span>
                                </a>
                            </li>
                        @endcan

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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="/profile/{{ auth()->user()->id }}">Profile</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
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

        <div class="row g-0">
            @auth
            <!-- Sidebar -->
            <div class="sidebar bg-light col-md-3 col-lg-2 p-4" id="sidebar">
                <ul class="list-unstyled">
                    @can('viewAny', App\Models\Inventory::class)
                        <li><a href="{{ url('/inventory') }}" class="btn btn-outline-primary w-100"><i class="bi bi-box-seam me-2"></i>Inventory</a></li>
                        <li><a href="{{ url('/chemistry-news') }}" class="btn btn-outline-primary w-100"><i class="bi bi-newspaper me-2"></i>Chemistry News</a></li>
                    @endcan
                    @can('viewAny', App\Models\Market::class)
                        <li><a href="{{ url('/market') }}" class="btn btn-outline-primary w-100"><i class="bi bi-shop-window me-2"></i>Chemical Supply</a></li>
                        <li><a href="{{ url('/orders') }}" class="btn btn-outline-primary w-100"><i class="bi bi-receipt me-2"></i>Purchase Orders</a></li>
                    @endcan
                    @cannot('viewAny', App\Models\Market::class)
                        <li><a href="{{ url('/request') }}" class="btn btn-outline-primary w-100"><i class="bi bi-envelope-check me-2"></i>Request to Admin</a></li>
                    @endcan
                    @can('create', App\Models\Inventory::class)
                        <li><a href="{{ url('/brands') }}" class="btn btn-outline-primary w-100"><i class="bi bi-tags me-2"></i>Add Brands</a></li>
                    @endcan

                    @can('viewAny', App\Models\InventoryUsage::class)
                        <li class="mt-3 fw-bold text-muted">Admin</li>
                        <li><a href="{{ url('/logs/user') }}" class="btn btn-outline-danger w-100"><i class="bi bi-journal-text me-2"></i>Activity Log</a></li>
                        <li><a href="{{ url('/a/users') }}" class="btn btn-outline-danger w-100"><i class="bi bi-people me-2"></i>View Users</a></li>
                    @endcan
                </ul>
            </div>
            @endauth

            <!-- Main Content -->
            <main class="col-md p-4 main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Quick Assist Embed -->
    {{-- @auth
        @if(auth()->user()->role === config('roles.admin') || auth()->user()->role === config('roles.faculty')) --}}
            {{-- <script type="module">
                import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
                Chatbot.init({
                    chatflowid: "ab779e35-7a6a-4ddd-b3ef-5eb8c60237ef",
                    apiHost: "https://flowise-1-468w.onrender.com",
                })
            </script> --}}
            {{-- <script type="module">
                import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
                Chatbot.init({
                    chatflowid: "ab779e35-7a6a-4ddd-b3ef-5eb8c60237ef",
                    apiHost: "http://localhost:3000",
                })
            </script>
        @endif
    @endauth --}}

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        sidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Optional: close sidebar when clicking outside (mobile)
        document.addEventListener('click', function (event) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>


    {{-- <script>
        // Theme toggle
        const toggleBtn = document.getElementById('themeToggle');
        const icon = document.getElementById('themeIcon');

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
            localStorage.setItem('theme', theme);
        }

        toggleBtn?.addEventListener('click', () => {
            const current = localStorage.getItem('theme') || 'light';
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);

        // Bootstrap tooltip init
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script> --}}

</body>
</html>
