<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiPresensi extends Model
{
    use HasFactory;

    protected $table = 'sesi_presensis';

    protected $fillable = [
        'guru_id',
        'kelas',
        'kode_presensi',
        'waktu_berlaku',
        'status',
    ];

    protected $casts = [
        'waktu_berlaku' => 'datetime',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
