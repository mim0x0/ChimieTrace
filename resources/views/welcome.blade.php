<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChimieTrace | Chemical Inventory & Marketplace</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body {
        font-family: 'Inter', sans-serif;
      }
    </style>
  </head>
  <body class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">
    <header class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md py-6">
      <div class="max-w-7xl mx-auto flex justify-between items-center px-6">
        <h1 class="text-2xl font-bold text-white tracking-wide">
          ChimieTrace
        </h1>
        <nav class="flex space-x-6">
          @auth
          {{-- <a href="/inventory" class="text-white hover:text-gray-200 font-medium">Inventory</a> --}}
          {{-- <a href="/market" class="text-white hover:text-gray-200 font-medium">Market</a> --}}
          @else
          <a href="{{ route('login') }}" class="text-white hover:text-gray-200 font-medium">Login</a>
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="text-white hover:text-gray-200 font-medium">Register</a>
          @endif
          @endauth
        </nav>
      </div>
    </header>

    <main class="relative overflow-hidden">
      <section class="text-center py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-4xl mx-auto px-6">
          <h2 class="text-4xl font-extrabold leading-tight mb-4">
            Revolutionizing Chemical Inventory & Procurement
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
            ChimieTrace helps you manage, trace, and procure chemicals easily and safely with robust tools tailored for labs and suppliers.
          </p>
          <div class="space-x-4">
            @can('viewAny', App\Models\Inventory::class)
                <a href="/inventory" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-lg font-semibold">Explore Inventory</a>
            @endcan
            @can('viewAny', App\Models\Market::class)
                <a href="/market" class="bg-white hover:bg-gray-100 text-indigo-600 border border-indigo-600 px-6 py-3 rounded-xl text-lg font-semibold">Visit Market</a>
            @endcan
          </div>
        </div>
      </section>

      <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-indigo-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v1.5M14.25 3v1.5M3 9.75h1.5M3 14.25h1.5m15 0h1.5M3 3l18 18" />
            </svg>
            <h3 class="text-xl font-semibold mb-2">Secure Inventory Tracking</h3>
            <p class="text-gray-600 dark:text-gray-400">Easily track chemical usage, access logs, and seal records.</p>
          </div>
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-indigo-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <h3 class="text-xl font-semibold mb-2">Real-Time Inventory</h3>
            <p class="text-gray-600 dark:text-gray-400">Track chemical usage and stock levels instantly across your lab or institution.</p>
          </div>
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-indigo-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M9 16h6" />
            </svg>
            <h3 class="text-xl font-semibold mb-2">Admin & Faculty Tools</h3>
            <p class="text-gray-600 dark:text-gray-400">Role-based access ensures only authorized edits and purchases are allowed.</p>
          </div>
        </div>
      </section>
    </main>

    <footer class="bg-gray-100 dark:bg-gray-800 text-center text-sm py-6">
      <div class="max-w-6xl mx-auto px-6 text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} ChimieTrace. All rights reserved.
      </div>
    </footer>
  </body>
</html>
