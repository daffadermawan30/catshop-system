@extends('layouts.admin')

@section('title', 'Kalender Grooming')
@section('header', 'Kalender Jadwal Grooming')

@push('styles')
{{-- FullCalendar CSS dari CDN --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<style>
    /* Override warna FullCalendar agar sesuai tema orange catshop */
    .fc-button-primary {
        background-color: #ea580c !important;
        border-color: #ea580c !important;
    }
    .fc-button-primary:hover {
        background-color: #c2410c !important;
    }
    .fc-day-today { background-color: #fff7ed !important; }
    .fc-event { cursor: pointer; font-size: 12px; border-radius: 4px !important; }
    #calendar { background: white; padding: 20px; border-radius: 12px; }
</style>
@endpush

@section('content')

{{-- Legend warna status --}}
<div class="flex gap-4 mb-4 text-sm">
    <div class="flex items-center gap-1.5">
        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
        <span class="text-gray-600">Menunggu</span>
    </div>
    <div class="flex items-center gap-1.5">
        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
        <span class="text-gray-600">Dikonfirmasi</span>
    </div>
    <div class="flex items-center gap-1.5">
        <div class="w-3 h-3 rounded-full bg-orange-500"></div>
        <span class="text-gray-600">Sedang Dikerjakan</span>
    </div>
    <div class="flex items-center gap-1.5">
        <div class="w-3 h-3 rounded-full bg-green-500"></div>
        <span class="text-gray-600">Selesai</span>
    </div>
    <a href="{{ route('admin.grooming-bookings.create') }}"
       class="ml-auto bg-orange-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-orange-700">
        + Booking Baru
    </a>
</div>

{{-- Elemen kalender — FullCalendar akan di-render di sini --}}
<div id="calendar"></div>

{{-- Tooltip popup saat hover event --}}
<div id="event-tooltip"
     class="hidden fixed z-50 bg-gray-900 text-white text-xs rounded-lg p-3 shadow-xl max-w-xs pointer-events-none">
    <p class="font-medium" id="tooltip-title"></p>
    <p class="text-gray-300 mt-1" id="tooltip-customer"></p>
    <p class="text-gray-300" id="tooltip-price"></p>
    <p class="text-green-300 text-xs mt-1">Klik untuk lihat detail</p>
</div>

@endsection

@push('scripts')
{{-- FullCalendar JS dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const tooltip    = document.getElementById('event-tooltip');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        // Mode tampilan default
        initialView: 'timeGridWeek',

        // Toolbar atas kalender
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            // Mode tampilan: bulan, minggu (grid), hari, dan list
            right:  'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },

        // Jam operasional catshop yang ditampilkan
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',

        // Format waktu Indonesia
        locale: 'id',

        // Tinggi kalender
        height: 650,

        // FullCalendar akan fetch events dari URL ini
        // Setiap kali bulan/minggu berubah, request baru dikirim dengan parameter start & end
        events: {
            url: '{{ route("admin.grooming-calendar.events") }}',
            method: 'GET',
            // Jika request gagal
            failure: function () {
                alert('Gagal memuat jadwal. Coba refresh halaman.');
            }
        },

        // Saat event di-klik, navigasi ke halaman detail
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },

        // Tampilkan tooltip saat mouse hover di atas event
        eventMouseEnter: function (info) {
            const props = info.event.extendedProps;
            document.getElementById('tooltip-title').textContent    = info.event.title;
            document.getElementById('tooltip-customer').textContent = '👤 ' + props.customer;
            document.getElementById('tooltip-price').textContent    = '💰 ' + props.price;
            tooltip.classList.remove('hidden');
        },

        // Sembunyikan tooltip saat mouse keluar
        eventMouseLeave: function () {
            tooltip.classList.add('hidden');
        },
    });

    calendar.render();

    // Ikuti posisi mouse untuk tooltip
    document.addEventListener('mousemove', function (e) {
        tooltip.style.left = (e.clientX + 15) + 'px';
        tooltip.style.top  = (e.clientY + 10) + 'px';
    });
});
</script>
@endpush
