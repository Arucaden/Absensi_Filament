<?php

use Illuminate\Support\Facades\Route;
use App\Exports\ThrExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ThrController;
use App\Exports\KaryawanExport;
use App\Http\Controllers\KaryawanController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/export-karyawan', function () {
    return Excel::download(new KaryawanExport(), 'karyawan_data.xlsx');
})->name('export-karyawan');

Route::get('/karyawan/export-pdf', [KaryawanController::class, 'exportPDF'])->name('karyawan.exportPDF');

Route::get('/export-thr', [ThrController::class, 'export'])->name('export-thr');


