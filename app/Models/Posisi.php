<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    use HasFactory;

    protected $table = 'posisis';
    protected $primaryKey = 'id_posisi';

    protected $fillable = ['posisi', 'jam_kerja_per_hari', 'hari_kerja_per_minggu'];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($posisi) {
            // When the position is updated, recalculate THR for all associated employees
            THR::updateTHRForPosisi($posisi->id_posisi);
        });
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'id_posisi', 'id_posisi');
    }

    public static function updateTHRForPosisi($posisiId)
    {
        // Ambil semua karyawan dengan posisi_id yang diberikan
        $karyawans = Karyawan::where('posisi_id', $posisiId)->get();

        // Loop melalui setiap karyawan untuk menghitung dan menyimpan THR mereka
        foreach ($karyawans as $karyawan) {
            self::calculateAndSaveTHR($karyawan->id_karyawan);
        }
    }

}
