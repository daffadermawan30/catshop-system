<?php

namespace App\Exports;

use App\Models\GroomingBooking;
use Maatwebsite\Excel\Concerns\FromCollection;

class GroomingReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GroomingBooking::all();
    }
}
