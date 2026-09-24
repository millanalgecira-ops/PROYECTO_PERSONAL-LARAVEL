<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:check-users', function () {
    $this->info('=== USUARIOS (STAFF) ===');
    $usuarios = \App\Models\Usuario::with('rol')->get();
    if ($usuarios->isEmpty()) {
        $this->warn('No hay usuarios de staff registrados.');
    } else {
        $this->table(
            ['ID', 'Nombre', 'Correo', 'Teléfono', 'Rol', 'Activo'],
            $usuarios->map(fn ($u) => [$u->id, $u->nombre, $u->correo, $u->telefono ?? '-', $u->rol->nombre ?? $u->rol_id, $u->activo ? 'Sí' : 'No'])
        );
    }

    $this->newLine();
    $this->info('=== CLIENTES (PÚBLICO) ===');
    $clientes = \App\Models\Cliente::all();
    if ($clientes->isEmpty()) {
        $this->warn('No hay clientes registrados en la base de datos actualmente.');
    } else {
        $this->table(
            ['ID', 'Nombre', 'Correo', 'Teléfono', 'Dirección', 'Activo', 'Creado En'],
            $clientes->map(fn ($c) => [$c->id, $c->nombre, $c->correo, $c->telefono ?? '-', $c->direccion ?? '-', $c->activo ? 'Sí' : 'No', $c->creado_en])
        );
    }
})->purpose('Muestra los usuarios del staff y clientes registrados');

Artisan::command('app:sync-migrations', function () {
    $migrations = [
        '0001_01_01_000000_create_sessions_table',
        '0001_01_01_000001_create_cache_table',
        '0001_01_01_000002_create_jobs_table',
        '2024_01_01_000001_create_roles_table',
        '2024_01_01_000002_create_usuarios_table',
        '2024_01_01_000003_create_clientes_table',
        '2024_01_01_000004_create_tokens_recuperacion_table',
        '2024_01_01_000005_create_categorias_table',
        '2024_01_01_000006_create_productos_table',
        '2024_01_01_000007_create_producto_agotamientos_table',
        '2024_01_01_000008_create_mesas_table',
        '2024_01_01_000009_create_carrito_items_table',
        '2024_01_01_000010_create_pedidos_table',
        '2024_01_01_000011_create_pedido_items_table',
        '2024_01_01_000012_create_pedido_estados_historial_table',
        '2024_01_01_000013_create_pagos_table',
        '2024_01_01_000014_create_ingresos_table',
        '2024_01_01_000015_create_reporting_views',
    ];

    \DB::table('migrations')->truncate();
    foreach ($migrations as $migration) {
        \DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => 1,
        ]);
    }
    $this->info('Migraciones sincronizadas correctamente con los archivos actuales.');
})->purpose('Sincroniza la tabla migrations con los archivos de migracion del proyecto');



