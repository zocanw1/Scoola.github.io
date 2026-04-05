<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Setup Secret
    |--------------------------------------------------------------------------
    |
    | Secret key untuk mengakses halaman setup admin setelah admin pertama
    | sudah dibuat. Set di .env file sebagai SETUP_SECRET.
    |
    */
    'setup_secret' => env('SETUP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'login_max_attempts' => 5,
    'login_decay_minutes' => 1,

    'presensi_max_attempts' => 10,
    'presensi_decay_minutes' => 1,

];
