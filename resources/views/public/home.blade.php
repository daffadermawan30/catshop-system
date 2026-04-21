@extends('layouts.public')

@section('title', 'CatShop — Pet Shop & Grooming Kucing Terpercaya')
@section('description', 'Layanan grooming profesional dan penitipan nyaman untuk kucing kesayangan Anda. Booking mudah, online 24 jam.')

@section('content')

{{-- ========================================
     HERO SECTION
     ======================================== --}}
<section class="relative overflow-hidden bg-gradient-to-br from-orange-50 via-white to-amber-50 pt-12 pb-20">
    <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="inline-block bg-orange-100 text-orange-700 text-xs font-semibold px-3 py-1 rounded-full mb-4">
                🐱 Pet Shop Khusus Kucing
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                Perawatan Terbaik untuk<br>
                <span class="text-orange-600">Kucing Kesayangan</span> Anda
            </h1>
            <p class="text-gray-500 text-lg mb-8 leading-relaxed">
                Grooming profesional, penitipan nyaman, dan produk pilihan terbaik.
                Dipercaya ratusan pecinta kucing di Jakarta.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('public.booking') }}"
                   class="bg-orange-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-orange-700 transition shadow-md text-sm">
                   📅 Booking Sekarang
                </a>
                <a href="{{ route('services') }}"
                   class="bg-white border border-orange-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-orange-50 transition text-sm">
                   Lihat Layanan →
                </a>
            </div>

            {{-- Trust Badges --}}
            <div class="flex gap-6 mt-8 text-sm text-gray-500">
                <div class="flex items-center gap-1">
                    <span class="text-green-500 font-bold">✓</span> Groomer Berpengalaman
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-green-500 font-bold">✓</span> Produk Aman Kucing
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-green-500 font-bold">✓</span> Booking Online 24 Jam
                </div>
            </div>
        </div>

        {{-- Hero Image --}}
        <div class="relative">
            <div class="bg-orange-100 rounded-3xl overflow-hidden aspect-square flex items-center justify-center text-9xl shadow-xl">
                🐱
            </div>
            {{-- Floating card: status --}}
            <div class="absolute -bottom-4 -left-4 bg-white shadow-lg rounded-2xl px-4 py-3 flex items-center gap-3">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <p class="text-sm font-bold text-gray-800">Buka Sekarang</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================
     STATS / SOCIAL PROOF
     ======================================== --}}
<section class="bg-orange-600 py-10">
    <div class="max-w-4xl mx-auto px-4 grid grid-cols-3 gap-6 text-center text-white">
        @foreach([['500+', 'Pelanggan Puas'], ['1000+', 'Kucing Dirawat'], ['4.9★', 'Rating Google']] as [$num, $label])
        <div>
            <p class="text-3xl font-extrabold">{{ $num }}</p>
            <p class="text-orange-100 text-sm mt-1">{{ $label }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ========================================
     LAYANAN UTAMA
     ======================================== --}}
<section class="py-16 max-w-6xl mx-auto px-4">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900">Layanan Kami</h2>
        <p class="text-gray-500 mt-2">Semua yang dibutuhkan kucing Anda, dalam satu tempat</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        {{-- Grooming Card --}}
        <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-100 rounded-3xl p-6">
            <div class="text-5xl mb-4">✂️</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Grooming Profesional</h3>
            <p class="text-gray-500 text-sm mb-4">
                Mandi, potong kuku, bersih telinga, dan perawatan lengkap lainnya
                oleh groomer berpengalaman yang mencintai kucing.
            </p>
            @if($groomingPackages->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($groomingPackages as $pkg)
                <div class="flex justify-between items-center text-sm bg-white rounded-xl px-3 py-2">
                    <span class="text-gray-700 font-medium">{{ $pkg->name }}</span>
                    <span class="text-orange-600 font-bold">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            @endif
            <a href="{{ route('services') }}#grooming"
               class="text-orange-600 text-sm font-semibold hover:underline">
               Lihat semua paket →
            </a>
        </div>

        {{-- Penitipan Card --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-3xl p-6">
            <div class="text-5xl mb-4">🏠</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Penitipan Nyaman</h3>
            <p class="text-gray-500 text-sm mb-4">
                Titipkan kucing Anda saat bepergian. Kamar bersih, makan teratur,
                dan laporan harian dikirim ke WhatsApp Anda.
            </p>
            @if($roomTypes->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($roomTypes as $room)
                <div class="flex justify-between items-center text-sm bg-white rounded-xl px-3 py-2">
                    <span class="text-gray-700 font-medium">{{ $room->name }}</span>
                    <span class="text-blue-600 font-bold">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}/malam</span>
                </div>
                @endforeach
            </div>
            @endif
            <a href="{{ route('services') }}#boarding"
               class="text-blue-600 text-sm font-semibold hover:underline">
               Lihat tipe kamar →
            </a>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('public.booking') }}"
           class="inline-block bg-orange-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-orange-700 transition shadow-md">
           Booking Layanan Sekarang
        </a>
    </div>
</section>

{{-- ========================================
     GALERI
     ======================================== --}}
@if($galleryPhotos->count() > 0)
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Galeri</h2>
            <p class="text-gray-500 mt-2">Momen-momen kucing kesayangan pelanggan kami</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($galleryPhotos as $photo)
            <div class="aspect-square bg-orange-100 rounded-2xl overflow-hidden">
                <img src="{{ Storage::url($photo->photo) }}"
                     alt="{{ $photo->caption ?? 'CatShop Gallery' }}"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                     loading="lazy">
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('gallery') }}"
               class="text-orange-600 font-semibold hover:underline text-sm">
               Lihat semua foto →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ========================================
     CARA BOOKING (HOW IT WORKS)
     ======================================== --}}
<section class="py-16 max-w-5xl mx-auto px-4">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900">Cara Booking</h2>
        <p class="text-gray-500 mt-2">Mudah, cepat, dan bisa dari mana saja</p>
    </div>
    <div class="grid md:grid-cols-4 gap-6 text-center">
        @foreach([
            ['1', '📱', 'Pilih Layanan', 'Pilih grooming atau penitipan sesuai kebutuhan kucing kamu'],
            ['2', '📅', 'Pilih Jadwal', 'Pilih tanggal dan jam yang tersedia'],
            ['3', '📝', 'Isi Data Kucing', 'Ceritakan kebiasaan & kebutuhan khusus kucing kamu'],
            ['4', '✅', 'Konfirmasi', 'Tim kami konfirmasi booking via WhatsApp'],
        ] as [$step, $icon, $title, $desc])
        <div class="relative">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-xl mx-auto mb-3">
                {{ $icon }}
            </div>
            <span class="absolute top-0 right-1/4 text-xs bg-orange-600 text-white w-5 h-5 rounded-full flex items-center justify-center font-bold">
                {{ $step }}
            </span>
            <h4 class="font-bold text-gray-800 mb-1">{{ $title }}</h4>
            <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('public.booking') }}"
           class="bg-orange-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-orange-700 transition">
           Mulai Booking →
        </a>
    </div>
</section>

{{-- ========================================
     CTA AKHIR
     ======================================== --}}
<section class="bg-orange-600 py-14 text-center text-white">
    <h2 class="text-3xl font-extrabold mb-3">Siap merawat kucing Anda?</h2>
    <p class="text-orange-100 mb-6 text-sm max-w-md mx-auto">
        Daftar gratis dan booking layanan pertama Anda hari ini.
        Tim kami siap membantu 7 hari seminggu.
    </p>
    <div class="flex gap-3 justify-center flex-wrap">
        <a href="{{ route('register') }}"
           class="bg-white text-orange-600 px-6 py-3 rounded-full font-bold hover:bg-orange-50 transition text-sm">
           Daftar Gratis
        </a>
        <a href="{{ route('public.booking') }}"
           class="border border-white text-white px-6 py-3 rounded-full font-semibold hover:bg-orange-700 transition text-sm">
           Booking Tanpa Daftar
        </a>
    </div>
</section>

@endsection
