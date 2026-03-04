<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable; // optional, kalau nanti pakai notifikasi

    // Nama table sesuai database
    protected $table = 'user';

    // Primary key sesuai database
    protected $primaryKey = 'id_user';

    // Field yang bisa diisi mass assignment
    protected $fillable = [
        'id_user',
        'password',
    ];

    // Agar password tidak ditampilkan saat dipanggil
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
