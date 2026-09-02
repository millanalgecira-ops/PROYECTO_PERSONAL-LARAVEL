<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        $idPorNombre = Categoria::pluck('id', 'nombre');

        $productos = [
            ['categoria' => 'Pollo', 'nombre' => 'Pollo asado entero', 'descripcion' => 'Pollo asado al carbon con acompanamientos', 'precio' => 42000, 'popular' => true],
            ['categoria' => 'Pollo', 'nombre' => 'Medio pollo asado', 'descripcion' => 'Medio pollo asado al carbon', 'precio' => 24000, 'popular' => true],
            ['categoria' => 'Res', 'nombre' => 'Carne asada', 'descripcion' => 'Porcion de carne de res asada al carbon', 'precio' => 30000, 'popular' => true],
            ['categoria' => 'Cerdo', 'nombre' => 'Costillas BBQ', 'descripcion' => 'Costillas de cerdo en salsa BBQ', 'precio' => 34000, 'popular' => false],
            ['categoria' => 'Combos', 'nombre' => 'Combo familiar', 'descripcion' => 'Pollo entero, papas, arepas y bebida', 'precio' => 62000, 'popular' => true],
            ['categoria' => 'Acompanamientos', 'nombre' => 'Papas a la francesa', 'descripcion' => 'Porcion de papas crocantes', 'precio' => 9000, 'popular' => false],
            ['categoria' => 'Bebidas', 'nombre' => 'Gaseosa personal', 'descripcion' => 'Bebida gaseosa personal', 'precio' => 5000, 'popular' => false],
        ];

        foreach ($productos as $p) {
            Producto::updateOrCreate(
                ['nombre' => $p['nombre']],
                [
                    'categoria_id' => $idPorNombre[$p['categoria']],
                    'descripcion' => $p['descripcion'],
                    'precio' => $p['precio'],
                    'popular' => $p['popular'],
                    'disponible' => true,
                ]
            );
        }
    }
}
