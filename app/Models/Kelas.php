<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas_nip',
    ];

    /**
     * Relasi ke guru sebagai wali kelas
     */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_nip', 'NIP');
    }

    /**
     * Relasi ke siswa di kelas ini
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas', 'nama_kelas');
    }
}
