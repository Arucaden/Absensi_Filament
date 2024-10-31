<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';
    public $incrementing = false;

    protected $fillable = [
        'id_karyawan',
        'nama',
        'alamat',
        'no_telp',
        'email',
        'tanggal_masuk',
        'id_posisi'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'id_posisi', 'id_posisi');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'id_karyawan', 'id_karyawan');
    }
}
