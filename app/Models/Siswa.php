<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Presensi;

class Siswa extends Model
{
    protected $table = 'siswa';

    // Primary key custom
    protected $primaryKey = 'NIS';

    // Karena bukan auto increment
    public $incrementing = false;

    // Karena tipe varchar
    protected $keyType = 'string';

    protected $fillable = [
        'NIS',
        'user_id',
        'nama_siswa',
        'kelas'
    ];

    /* =========================
       RELATIONSHIP
    ========================= */

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke presensi
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'NIS', 'NIS');
    }
}