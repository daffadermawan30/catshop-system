<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO Meta --}}
    <title>@yield('title', 'CatShop — Pet Shop Khusus Kucing')</title>
    <meta name="description" content="@yield('description', 'CatShop adalah pet shop khusus kucing terpercaya. Layanan grooming profesional, penitipan nyaman, dan produk terlengkap untuk kucing kesayangan Anda.')">

    {{-- Open Graph (untuk preview WhatsApp, Instagram, dll) --}}
    <meta property="og:title"       content="@yield('og_title', 'CatShop — Pet Shop Khusus Kucing')">
    <meta property="og:description" content="@yield('og_description', 'Layanan grooming & penitipan kucing terpercaya.')">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-default.jpg'))">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>

    @stack('styles')
</head>
<body class="bg-white text-gray-800">

    {{-- Navbar Publik --}}
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-orange-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-2xl">🐱</span>
                <span class="font-extrabold text-orange-600 text-lg">CatShop</span>
            </a>

            {{-- Nav Links (Desktop) --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="{{ route('home') }}"
                   class="hover:text-orange-600 transition {{ request()->routeIs('home') ? 'text-orange-600' : '' }}">
                   Beranda
                </a>
                <a href="{{ route('services') }}"
                   class="hover:text-orange-600 transition {{ request()->routeIs('services') ? 'text-orange-600' : '' }}">
                   Layanan
                </a>
                <a href="{{ route('gallery') }}"
                   class="hover:text-orange-600 transition {{ request()->routeIs('gallery') ? 'text-orange-600' : '' }}">
                   Galeri
                </a>
                <a href="{{ route('about') }}"
                   class="hover:text-orange-600 transition {{ request()->routeIs('about') ? 'text-orange-600' : '' }}">
                   Tentang Kami
                </a>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-2">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-sm text-gray-600 hover:text-orange-600">Dashboard</a>
                    @else
                        <a href="{{ route('customer.dashboard') }}"
                           class="text-sm text-gray-600 hover:text-orange-600">Akun Saya</a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-600 hover:text-orange-600 hidden md:block">Masuk</a>
                @endauth

                <a href="{{ route('public.booking') }}"
                   class="bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-orange-700 transition shadow-sm">
                   Booking Sekarang
                </a>

                {{-- Hamburger Mobile --}}
                <button id="mobile-menu-btn" class="md:hidden text-gray-600 hover:text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden px-4 pb-3 border-t border-orange-100">
            <div class="flex flex-col gap-2 pt-3 text-sm">
                <a href="{{ route('home') }}"     class="text-gray-700 hover:text-orange-600 py-1">Beranda</a>
                <a href="{{ route('services') }}" class="text-gray-700 hover:text-orange-600 py-1">Layanan</a>
                <a href="{{ route('gallery') }}"  class="text-gray-700 hover:text-orange-600 py-1">Galeri</a>
                <a href="{{ route('about') }}"    class="text-gray-700 hover:text-orange-600 py-1">Tentang</a>
                @guest
                <a href="{{ route('login') }}"    class="text-gray-700 hover:text-orange-600 py-1">Masuk</a>
                <a href="{{ route('register') }}" class="text-orange-600 font-semibold py-1">Daftar Gratis</a>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 pt-12 pb-6 mt-16">
        <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-4 gap-8 mb-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-2xl">🐱</span>
                    <span class="font-extrabold text-white text-lg">CatShop</span>
                </div>
                <p class="text-sm leading-relaxed text-gray-400">
                    Pet shop khusus kucing terpercaya. Kami merawat kucing kesayangan Anda
                    dengan penuh cinta dan keahlian.
                </p>
                {{-- WhatsApp CTA --}}
                <a href="https://wa.me/62XXXXXXXXXX?text=Halo+CatShop,+saya+ingin+booking"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 mt-4 bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.554 4.103 1.523 5.828L0 24l6.338-1.492C8.044 23.456 9.99 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.671-.498-5.214-1.368l-.374-.22-3.866.91.978-3.783-.242-.39C2.51 15.565 2 13.836 2 12 2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                    </svg>
                    Chat WhatsApp
                </a>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-3">Layanan</h4>
                <ul class="space-y-1 text-sm text-gray-400">
                    <li><a href="{{ route('services') }}#grooming" class="hover:text-white transition">Grooming Kucing</a></li>
                    <li><a href="{{ route('services') }}#boarding" class="hover:text-white transition">Penitipan Kucing</a></li>
                    <li><a href="{{ route('public.booking') }}" class="hover:text-white transition">Booking Online</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-3">Info</h4>
                <ul class="space-y-1 text-sm text-gray-400">
                    <li>📍 [Alamat Toko]</li>
                    <li>🕐 Buka: 08.00 – 20.00</li>
                    <li>📞 [Nomor Telepon]</li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">Tentang Kami</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 pt-6 border-t border-gray-800 text-center text-xs text-gray-500">
            © {{ date('Y') }} CatShop. Dibuat dengan ❤️ untuk para pecinta kucing.
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>
