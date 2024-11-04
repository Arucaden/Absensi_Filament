<?php

namespace App\Http\Controllers;

use App\Exports\ThrExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ThrController extends Controller
{
    public function export()
    {
        return Excel::download(new ThrExport(), 'thr_data.xlsx');
    }
}
