<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Mapel;

class Guru extends Model
{
    protected $table = 'guru';

    protected $primaryKey = 'NIP';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'NIP',
        'user_id',
        'nama_guru',
        'kd_mapel',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'kd_mapel', 'kd_mapel');
    }

    /**
     * Relasi ke kelas yang di-wali
     */
    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_nip', 'NIP');
    }

    /**
     * Cek apakah guru ini adalah wali kelas
     */
    public function isWaliKelas(): bool
    {
        return $this->kelasWali()->exists();
    }
}
