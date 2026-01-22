<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DgarrozyFinance extends Model
{
    use HasFactory;

    protected $table = 'dgarrozy_finances';

    protected $fillable = [
        'nama_perencanaan',
        'total_perencanaan',
        'deskripsi',
        'modal_awal',
        'total_pengeluaran',
        'total_pendapatan',
        'modal_akhir',
    ];

    protected $casts = [
        'total_perencanaan'  => 'decimal:2',
        'modal_awal'         => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'total_pendapatan'  => 'decimal:2',
        'modal_akhir'        => 'decimal:2',
    ];

    /**
     * Auto hitung modal akhir setiap simpan / update
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->modal_akhir =
                ($model->modal_awal ?? 0)
                + ($model->total_pendapatan ?? 0)
                - ($model->total_pengeluaran ?? 0);
        });
    }
}
