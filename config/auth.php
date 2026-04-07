<?php
return [
    'defaults' => [
        'guard'     => 'sanctum',
        'passwords' => 'candidats',
    ],

    'guards' => [
        'web'       => ['driver' => 'session', 'provider' => 'users'],
        'sanctum'   => ['driver' => 'sanctum',  'provider' => null],
        'admin'     => ['driver' => 'sanctum',  'provider' => 'admins'],
        'candidat'  => ['driver' => 'sanctum',  'provider' => 'candidats'],
        'entreprise'=> ['driver' => 'sanctum',  'provider' => 'entreprises'],
    ],

    'providers' => [
        'admins'     => ['driver' => 'eloquent', 'model' => App\Models\Admin::class],
        'candidats'  => ['driver' => 'eloquent', 'model' => App\Models\Candidat::class],
        'entreprises'=> ['driver' => 'eloquent', 'model' => App\Models\Entreprise::class],
        'users'      => ['driver' => 'eloquent', 'model' => App\Models\Candidat::class],
    ],

    'passwords' => [
        'candidats'  => ['provider' => 'candidats',  'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
        'entreprises'=> ['provider' => 'entreprises', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
        'admins'     => ['provider' => 'admins',      'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
    ],

    'password_timeout' => 10800,
];
