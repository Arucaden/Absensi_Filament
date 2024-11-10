<?php

use Illuminate\Support\Facades\Route;
use App\Exports\ThrExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ThrController;
use App\Exports\KaryawanExport;
use App\Http\Controllers\KaryawanController;
use App\Exports\AbsensiExport;
use App\Http\Controllers\AbsensiController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/export-karyawan', function () {
    return Excel::download(new KaryawanExport(), 'karyawan_data.xlsx');
})->name('export-karyawan');

Route::get('/karyawan/export-pdf', [KaryawanController::class, 'exportPDF'])->name('karyawan.exportPDF');

Route::get('/export-absensi', function () {
    return Excel::download(new AbsensiExport(), 'absensi_data.xlsx');
})->name('export-absensi');

Route::get('/absensi/export-pdf', [AbsensiController::class, 'exportPDF'])->name('absensi.exportPDF');

Route::get('/export-thr', [ThrController::class, 'export'])->name('export-thr');
Route::get('/thr/export-pdf', [ThrController::class, 'exportPDF'])->name('thr.exportPDF');


Route::group(['middleware' => ['role:super_admin']], function () {
    Route::resource('all_features', AllFeaturesController::class);
});

Route::group(['middleware' => ['role:admin,super_admin']], function () {
    Route::resource('absensi', AbsensiController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('thr', ThrController::class);
});
