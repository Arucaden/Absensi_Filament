<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $table = 'admin_activity_logs';
    protected $primaryKey = 'id_log';
    public $incrementing = false;

    protected $fillable = [
        'id_log',
        'id_admin',
        'activity',
        'activity_time'
    ];

    protected $casts = [
        'activity_time' => 'datetime'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }
}
