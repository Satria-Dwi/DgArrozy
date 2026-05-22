<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DgarrozyVerifyResumeRanap extends Model
{
    protected $table = 'dgarrozy_verify_resume_ranap';

    protected $fillable = [
        'no_rawat',
        'no_rm',
        'verify_date',
        'verified_by',
        'comment'
    ];

    protected $casts = [
        'verify_date' => 'datetime'
    ];
}