@extends('layouts.admin')
@section('title', 'Kalender Penitipan')
@section('header', 'Kalender Hunian Kamar')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<style>
    .fc-button-primary { background-color: #ea580c !important; border-color: #ea580c !important; }
    .fc-button-primary:hover { background-color: #c2410c !important; }
    .fc-day-today { background-color: #fff7ed !important; }
    .fc-event { cursor: pointer; font-size: 11px; border-radius: 4px !important; }
    #boarding-calendar { background: white; padding: 20px; border-radius: 12px; }
</style>
@endpush

@section('content')

<div class="flex gap-4 mb-4 text-sm items-center">
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-yellow-400"></span><span class="text-gray-600">Menunggu</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-blue-500"></span><span class="text-gray-600">Dikonfirmasi</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-orange-500"></span><span class="text-gray-600">Dititipkan</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-green-500"></span><span class="text-gray-600">Selesai</span>
    </div>
    <a href="{{ route('admin.boarding-bookings.create') }}"
       class="ml-auto bg-orange-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-orange-700">
        + Booking Baru
    </a>
</div>

<div id="boarding-calendar"></div>

<div id="event-tooltip"
     class="hidden fixed z-50 bg-gray-900 text-white text-xs rounded-lg p-3 shadow-xl max-w-xs pointer-events-none">
    <p class="font-medium" id="tt-title"></p>
    <p class="text-gray-300 mt-1" id="tt-customer"></p>
    <p class="text-gray-300" id="tt-room"></p>
    <p class="text-orange-300" id="tt-duration"></p>
    <p class="text-green-300 text-xs mt-1">Klik untuk lihat detail</p>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('boarding-calendar');
    const tooltip    = document.getElementById('event-tooltip');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        // dayGridMonth cocok untuk penitipan karena multi-hari
        initialView: 'dayGridMonth',
        headerToolbar: {
            left:  'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth',
        },
        locale: 'id',
        height: 650,
        events: {
            url: '{{ route("admin.boarding-calendar.events") }}',
            method: 'GET',
            failure: () => alert('Gagal memuat kalender.'),
        },
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            if (info.event.url) window.location.href = info.event.url;
        },
        eventMouseEnter: function (info) {
            const p = info.event.extendedProps;
            document.getElementById('tt-title').textContent    = info.event.title;
            document.getElementById('tt-customer').textContent = '👤 ' + p.customer;
            document.getElementById('tt-room').textContent     = '🏠 ' + p.room;
            document.getElementById('tt-duration').textContent = '📅 ' + p.duration;
            tooltip.classList.remove('hidden');
        },
        eventMouseLeave: () => tooltip.classList.add('hidden'),
    });

    calendar.render();

    document.addEventListener('mousemove', function (e) {
        tooltip.style.left = (e.clientX + 15) + 'px';
        tooltip.style.top  = (e.clientY + 10) + 'px';
    });
});
</script>
@endpush
