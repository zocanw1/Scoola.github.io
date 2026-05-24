<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'kelas',
        'nis',
        'birthplace',
        'birthdate',
        'phone',
        'description',
        'jobdesk',
        'photo',
        'skills',
        'sticker_bg',
        'img_bg',
        'role_color',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'skills' => 'array',
    ];
}
