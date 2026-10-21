<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Usuarios de prueba del staff (Administrador / Cocina), tal como en el
     * README del proyecto original.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'nombre' => 'Administrador',
                'correo' => 'millanalgecira@gmail.com',
                'telefono' => '3000000000',
                'password' => 'camilo123',
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Administrador',
                'correo' => 'admin@asaderoelcarbon.test',
                'telefono' => '3000000000',
                'password' => 'password',
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Cocina Principal',
                'correo' => 'cocina@asaderoelcarbon.test',
                'telefono' => '3000000001',
                'password' => 'password',
                'rol_id' => 2,
            ],
                    [
                'nombre' => 'Benjamin Santos',
                'correo' => 'benjamin@gmail.com',
                'telefono' => null,
                'password' => 'Ben123!',
                'rol_id' => 3,
            ],
            [
                'nombre' => 'benjamin',
                'correo' => 'benjamin123@gmail.com',
                'telefono' => '3001234567',
                'password' => 'Ben456@',
                'rol_id' => 3,
            ],
            [
                'nombre' => 'benjamin2',
                'correo' => 'benjamin1234@gmail.com',
                'telefono' => '123456789100',
                'password' => 'Ben789#',
                'rol_id' => 3,
            ],
            [
                'nombre' => 'miguell',
                'correo' => 'miguelancho@gmail.com',
                'telefono' => '12345678910',
                'password' => 'Miguel01$',
                'rol_id' => 3,
            ],
            [
                'nombre' => 'gordoche',
                'correo' => 'gordoche@gmail.com',
                'telefono' => '1234567891',
                'password' => 'Gordo22%',
                'rol_id' => 3,
            ],
];

        foreach ($usuarios as $u) {
            Usuario::updateOrCreate(
                ['correo' => $u['correo']],
                [
                    'nombre' => $u['nombre'],
                    'telefono' => $u['telefono'],
                    'password_hash' => Hash::make($u['password']),
                    'rol_id' => $u['rol_id'],
                    'activo' => true,
                ]
            );
        }
    }
}
