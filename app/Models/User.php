<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'from' => null, // Karena ini adalah penambahan
                'to' => json_encode($model->getAttributes()),
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->getChanges(); // Mendapatkan atribut yang diubah
            $original = $model->getOriginal(); // Mendapatkan nilai asli

            // Hanya menyimpan atribut yang berubah, kecuali 'tanggal_masuk' dan kolom timestamp
            $from = ['id'];
            $to = [];

            foreach ($changes as $key => $value) {
                // Mengabaikan kolom 'tanggal_masuk' dan kolom timestamp
                if ($key !== 'tanggal_masuk' && !in_array($key, ['created_at', 'updated_at'])) {
                    // Menyimpan atribut yang diubah
                    $from[$key] = $original[$key];
                    $to[$key] = $value;
                }
            }

            if (!empty($from) || !empty($to)) { // Pastikan hanya mencatat jika ada perubahan yang relevan
                AdminActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'from' => json_encode($from), // Hanya atribut yang diubah
                    'to' => json_encode($to), // Hanya atribut yang diubah
                ]);
            }
        });

        static::deleted(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'from' => json_encode($model->getAttributes()), // Menyimpan semua data saat dihapus
                'to' => null, // Karena data ini dihapus
            ]);
        });
    }
}
