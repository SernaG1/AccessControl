<?php

return [

    'defaults' => [
        'guard' => 'admin', // ← Usamos 'web' como guard por defecto
        'passwords' => 'admins',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins', // ← Asociamos el guard 'web' al provider 'admins'
        ],
    ],

    'providers' => [
        // Ya no necesitas 'users' si no lo usas
        // Puedes conservarlo si tienes otros usuarios distintos de admin
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // ← Usamos el modelo Admin
        ],
    ],

    'passwords' => [
        'admins' => [
            'provider' => 'admins', // ← Si usas recuperación, asócialo también al provider correcto
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
