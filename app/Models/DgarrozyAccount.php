<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DgarrozyAccount extends Model
{
    use HasFactory;
    protected $table = 'dgarrozy_account';

    protected $fillable = [
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(DgarrozyRole::class, 'role_id');
    }
}
