<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Res', 'descripcion' => 'Carnes de res asadas al carbon', 'orden' => 1],
            ['nombre' => 'Pollo', 'descripcion' => 'Pollo asado, presas y platos derivados', 'orden' => 2],
            ['nombre' => 'Cerdo', 'descripcion' => 'Carnes de cerdo y costillas', 'orden' => 3],
            ['nombre' => 'Combos', 'descripcion' => 'Combos personales y familiares', 'orden' => 4],
            ['nombre' => 'Acompanamientos', 'descripcion' => 'Papas, arepas, yucas, ensaladas', 'orden' => 5],
            ['nombre' => 'Bebidas', 'descripcion' => 'Bebidas frias y jugos', 'orden' => 6],
        ];

        foreach ($categorias as $cat) {
            Categoria::updateOrCreate(['nombre' => $cat['nombre']], $cat);
        }
    }
}
