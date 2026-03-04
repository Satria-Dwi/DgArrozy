<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketErm extends Model
{
    use HasFactory;

    // Nama table (opsional jika mengikuti konvensi)
    protected $table = 'dgarrozy_ticketserm';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_nik',
        'user_nama',
        'user_departemen',
        'kode_ticket',
        'judul',
        'deskripsi',
        'kategori',
        'prioritas',
        'status',
        'approved_by',
        'approved_at',
        'headsection_comment',
        'appdept_comment',
        'approved_comment',
        'rejected_by',
        'rejected_reason',
        'sla_hours',
        'sla_deadline',
        'resolved_at',
        'assigned_to',
    ];

    // Jika ada kolom tanggal/timestamp
    protected $dates = [
        'approved_at',
        'sla_deadline',
        'resolved_at',
        'created_at',
        'updated_at',
    ];

    // Bisa menambahkan mutator/accessor jika perlu
    // Contoh: format tanggal resolved
    public function getResolvedAtFormattedAttribute()
    {
        return $this->resolved_at ? $this->resolved_at->format('d-m-Y H:i') : '-';
    }
}
