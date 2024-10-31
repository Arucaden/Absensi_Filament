<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetThr extends Model
{
    use HasFactory;

    protected $table = 'set_thrs';
    protected $primaryKey = 'id_set_thr';

    protected $fillable = ['posisi_id', 'besaran_thr'];

    // Boot method to register model events
    protected static function boot()
    {
        parent::boot();

        // Event ketika set_thr diupdate
        static::updated(function ($setThr) {
            // Panggil fungsi untuk memperbarui THR untuk semua karyawan dengan posisi_id yang sama
            THR::updateTHRForPosisi($setThr->posisi_id);
        });

        // Event ketika set_thr dihapus
        static::deleted(function ($setThr) {
            // Panggil fungsi untuk menghapus THR untuk semua karyawan dengan posisi_id yang sama
            THR::removeTHRForPosisi($setThr->posisi_id);
        });
    }

    public function thrs()
    {
        return $this->hasMany(Thr::class, 'tahun', 'tahun');
    }

    // In SetThr.php
    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id', 'id_posisi');
    }

}

