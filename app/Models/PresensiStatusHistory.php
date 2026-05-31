<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiStatusHistory extends Model
{
    protected $fillable = [
        'presensi_id',
        'sesi_id',
        'nis',
        'old_status',
        'new_status',
        'reason',
        'changed_by',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'presensi_id');
    }

    public function sesi()
    {
        return $this->belongsTo(SesiPresensi::class, 'sesi_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'NIS');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
