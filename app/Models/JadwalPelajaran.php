<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $primaryKey = 'kd_jp';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_jp',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kd_mapel',
        'NIP',
        'kelas',
    ];

    public $timestamps = true;

    /* ==============================
     * RELATIONSHIPS
     * ============================== */

    /**
     * Relasi ke tabel guru
     * jadwal_pelajaran.NIP -> guru.NIP
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'NIP', 'NIP');
    }

    /**
     * Relasi ke tabel mapel
     * jadwal_pelajaran.kd_mapel -> mapel.kd_mapel
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'kd_mapel', 'kd_mapel');
    }
}
