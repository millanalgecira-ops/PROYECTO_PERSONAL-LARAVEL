<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_process_order_from_cart(): void
    {
        $cat = Categoria::create(['nombre' => 'Carnes', 'orden' => 1, 'activa' => true]);
        $prod = Producto::create([
            'nombre' => 'Punta de Anca',
            'categoria_id' => $cat->id,
            'precio' => 35000,
            'disponible' => true,
        ]);
        $mesa = Mesa::create(['numero' => 1, 'capacidad' => 4, 'estado' => 'Disponible']);

        $response = $this->postJson('/carrito/procesar', [
            'cart' => [
                [
                    'nombre' => 'Punta de Anca',
                    'precio' => 35000,
                    'qty' => 2,
                ],
            ],
            'tipo' => 'En mesa',
            'mesa_numero' => 1,
            'nombre_cliente' => 'Juan Perez',
            'metodo_pago' => 'Efectivo',
            'nota_especial' => 'Término medio',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('pedidos', [
            'total' => 70000,
            'tipo' => 'En mesa',
            'estado' => 'Recibido',
        ]);

        $this->assertDatabaseHas('mesas', [
            'id' => $mesa->id,
            'estado' => 'Ocupada',
        ]);

        $this->assertDatabaseHas('pagos', [
            'total_pagado' => 70000,
            'metodo' => 'Efectivo',
        ]);
    }
}
