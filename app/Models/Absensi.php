<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';
    protected $primaryKey = 'id_absensi';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'durasi',
        'status',
        'keterangan'
    ];

    public function getJamMasukAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getJamKeluarAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getDurasiAttribute()
    {
        if ($this->jam_masuk && $this->jam_keluar) {
            $jamMasuk = Carbon::parse($this->jam_masuk);
            $jamKeluar = Carbon::parse($this->jam_keluar);
            $durasi = $jamKeluar->diff($jamMasuk);

            return $durasi->format('%H:%I');
        }

        return '00:00';
    }

    protected static function boot()
    {
        parent::boot();

        // Event yang akan dijalankan setiap kali absensi disimpan
        static::saved(function ($absensi) {
            THR::calculateAndSaveTHR($absensi->karyawan_id);
        });

        // Event yang akan dijalankan setiap kali absensi dihapus
        static::deleted(function ($absensi) {
            THR::calculateAndSaveTHR($absensi->karyawan_id);
        });

        // Event yang akan dijalankan setiap kali absensi diupdate
        static::updated(function ($absensi) {
            THR::calculateAndSaveTHR($absensi->karyawan_id);
        });
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }
}
