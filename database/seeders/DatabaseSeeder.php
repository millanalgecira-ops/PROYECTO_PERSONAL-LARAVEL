<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Reproduce los datos iniciales del proyecto original (SQL/asadero_el_carbon.sql):
     * roles, usuarios del staff, categorias, productos y mesas.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsuariosSeeder::class,
            CategoriasSeeder::class,
            ProductosSeeder::class,
            MesasSeeder::class,
        ]);
    }
}
