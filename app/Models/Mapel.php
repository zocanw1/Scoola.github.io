<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';

    protected $primaryKey = 'kd_mapel';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_mapel',
        'nama_mapel',
    ];

    // 🔑 Route Model Binding pakai kd_mapel
    public function getRouteKeyName()
    {
        return 'kd_mapel';
    }
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'kd_mapel', 'NIP');
    }
}
