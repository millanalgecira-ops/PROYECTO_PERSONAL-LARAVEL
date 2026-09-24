<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'nombre' => 'Administrador', 'descripcion' => 'Acceso completo al panel de administracion'],
            ['id' => 2, 'nombre' => 'Cocina', 'descripcion' => 'Acceso al panel de comandas y cambio de estado de pedidos'],
            ['id' => 3, 'nombre' => 'Cliente', 'descripcion' => 'Acceso al catalogo y pedidos propios'],
        ];

        foreach ($roles as $rol) {
            Rol::updateOrCreate(['id' => $rol['id']], $rol);
        }
    }
}
