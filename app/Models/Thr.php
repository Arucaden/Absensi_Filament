<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Posisi;
use App\Models\SetThr;
use Illuminate\Support\Facades\DB;

class THR extends Model
{
    protected $table = 'thrs';
    protected $primaryKey = 'id_thr'; // Set primary key menjadi id_thr
    protected $fillable = ['karyawan_id', 'thr'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

    public static function updateTHRForPosisi($posisiId)
    {
        // Ambil semua karyawan dengan posisi_id yang diberikan
        $karyawans = Karyawan::where('posisi_id', $posisiId)->get();

        foreach ($karyawans as $karyawan) {
            self::calculateAndSaveTHR($karyawan->id_karyawan);
        }
    }

    public static function removeTHRForPosisi($posisiId)
    {
        // Ambil semua karyawan dengan posisi_id yang diberikan
        $karyawans = Karyawan::where('posisi_id', $posisiId)->get();

        foreach ($karyawans as $karyawan) {
            // Set THR menjadi 0 atau hapus data THR jika diinginkan
            self::updateOrCreate(
                ['karyawan_id' => $karyawan->id_karyawan],
                ['thr' => 0] // Atau bisa hapus menggunakan delete()
            );
        }
    }

    public static function calculateAndSaveTHR($karyawanId)
    {
        // 1. Ambil durasi kerja karyawan dari tabel Absensi
        $totalDurasiKerja = Absensi::where('karyawan_id', $karyawanId)
            ->sum(DB::raw('TIMESTAMPDIFF(HOUR, jam_masuk, jam_keluar)'));

        // 2. Ambil data posisi dan durasi kerja yang dibutuhkan
        $karyawan = Karyawan::find($karyawanId);
        $posisi = Posisi::find($karyawan->posisi_id);  // Pastikan `posisi_id` sesuai dengan kolom yang menghubungkan ke Posisi

        // Mengambil jam kerja per hari dan hari kerja per minggu dari posisi
        $jamKerjaPerHari = $posisi->jam_kerja_per_hari;
        $hariKerjaPerMinggu = $posisi->hari_kerja_per_minggu;

        // 3. Hitung total durasi kerja yang dibutuhkan per tahun
        $totalDurasiKerjaDibutuhkan = $jamKerjaPerHari * $hariKerjaPerMinggu * 52;

        // 4. Ambil besaran THR dari tabel SetThr sesuai posisi
        $besaranTHR = SetThr::where('posisi_id', $posisi->id_posisi)->value('besaran_thr');

        // 5. Hitung THR berdasarkan proporsi durasi kerja aktual dan yang dibutuhkan
        if ($totalDurasiKerjaDibutuhkan > 0) {
            $thr = ($besaranTHR * $totalDurasiKerja) / $totalDurasiKerjaDibutuhkan;
        } else {
            $thr = 0; // Menghindari pembagian dengan nol
        }

        // 6. Simpan nilai THR di tabel THRs
        THR::updateOrCreate(
            ['karyawan_id' => $karyawanId], // Kunci pencarian
            ['thr' => $thr]                  // Data yang ingin diupdate atau disimpan
        );
    }
}