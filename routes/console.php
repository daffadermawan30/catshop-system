<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Reminder grooming: setiap hari jam 10 pagi
Schedule::command('catshop:send-grooming-reminders')
    ->dailyAt('10:00')
    ->withoutOverlapping();

// Laporan harian penitipan: setiap hari jam 5 sore
Schedule::command('catshop:send-boarding-reports')
    ->dailyAt('17:00')
    ->withoutOverlapping();
