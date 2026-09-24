<?php

use App\Models\Cliente;
use App\Models\Usuario;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'usuarios'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | El proyecto original tiene dos "tablas de login" independientes:
    | - usuarios (staff: Administrador / Cocina)  -> guard "web"
    | - clientes (publico, se registran en el catalogo) -> guard "cliente"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'usuarios',
        ],

        'cliente' => [
            'driver' => 'session',
            'provider' => 'clientes',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'usuarios' => [
            'driver' => 'eloquent',
            'model' => Usuario::class,
        ],

        'clientes' => [
            'driver' => 'eloquent',
            'model' => Cliente::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | La recuperacion de contrasena la maneja
    | App\Services\PasswordRecoveryService con la tabla "tokens_recuperacion"
    | (comun a usuarios y clientes), tal como en el proyecto original.
    | Estos brokers quedan definidos por si se prefiere adoptar mas adelante
    | el sistema de notificaciones nativo de Laravel.
    |
    */

    'passwords' => [
        'usuarios' => [
            'provider' => 'usuarios',
            'table' => 'tokens_recuperacion',
            'expire' => 60,
            'throttle' => 60,
        ],
        'clientes' => [
            'provider' => 'clientes',
            'table' => 'tokens_recuperacion',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
