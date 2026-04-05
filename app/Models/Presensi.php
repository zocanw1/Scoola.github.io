<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $fillable = [
        'kd_presensi',
        'sesi_id',
        'tanggal',
        'kd_jp',
        'jam_masuk',
        'status',
        'NIS'
    ];

    // Relasi
    public function sesi()
    {
        return $this->belongsTo(SesiPresensi::class, 'sesi_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'kd_jp', 'kd_jp');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'NIS', 'NIS');
    }
}