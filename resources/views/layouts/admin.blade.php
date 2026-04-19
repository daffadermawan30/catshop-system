<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CatShop') — Admin</title>
    {{-- Tailwind CSS dari CDN untuk development --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js untuk interaktivitas ringan --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Wrapper utama --}}
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-orange-800 text-white flex flex-col fixed h-full z-10">
            {{-- Logo / Nama Aplikasi --}}
            <div class="p-6 border-b border-orange-700">
                <h1 class="text-xl font-bold">🐱 CatShop</h1>
                <p class="text-orange-300 text-sm">Panel Admin</p>
            </div>

            {{-- Menu Navigasi --}}
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-orange-700 transition
                          {{ request()->routeIs('admin.dashboard') ? 'bg-orange-700' : '' }}">
                    <span>📊</span> Dashboard
                </a>

                <div class="pt-4 pb-1 text-orange-400 text-xs uppercase tracking-wider">Manajemen</div>

                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-orange-700 transition
                          {{ request()->routeIs('admin.customers.*') ? 'bg-orange-700' : '' }}">
                    <span>👥</span> Pelanggan
                </a>

                <a href="{{ route('admin.cats.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-orange-700 transition
                          {{ request()->routeIs('admin.cats.*') ? 'bg-orange-700' : '' }}">
                    <span>🐱</span> Data Kucing
                </a>

                <div class="pt-4 pb-1 text-orange-400 text-xs uppercase tracking-wider">Layanan</div>

                <a href="#"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-orange-700 transition text-orange-300">
                    <span>✂️</span> Grooming <span class="ml-auto text-xs bg-orange-600 px-1.5 rounded">Soon</span>
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-orange-700 transition text-orange-300">
                    <span>🏠</span> Penitipan <span class="ml-auto text-xs bg-orange-600 px-1.5 rounded">Soon</span>
                </a>
            </nav>

            {{-- Info User yang Login --}}
            <div class="p-4 border-t border-orange-700">
                <p class="text-sm text-orange-200">{{ auth()->user()->name }}</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-xs text-orange-400 hover:text-white mt-1 transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 ml-64 overflow-y-auto">
            {{-- Header atas --}}
            <header class="bg-white shadow-sm px-8 py-4 sticky top-0 z-10">
                <h2 class="text-lg font-semibold text-gray-700">@yield('header', 'Dashboard')</h2>
            </header>

            {{-- Area konten halaman --}}
            <div class="p-8">
                {{-- Flash message sukses --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Flash message error --}}
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
