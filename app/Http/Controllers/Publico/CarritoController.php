<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Ingreso;
use App\Models\Mesa;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\PedidoEstadoHistorial;
use App\Models\PedidoItem;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CarritoController extends Controller
{
    /**
     * Procesa el pedido armado en el carrito (localStorage) y lo guarda en
     * base de datos: pedido, items, historial de estado, pago e ingreso.
     * Replica Controllers/CarritoController.php, pero envuelto en una
     * transaccion para que un fallo a mitad de camino no deje datos sueltos.
     */
    public function procesar(Request $request): JsonResponse
    {
        $cart = (array) $request->input('cart', []);
        $tipo = $request->input('tipo', 'En mesa');
        $mesaNumero = $request->input('mesa_numero');
        $direccionEntrega = $request->input('direccion_entrega');
        $nombreCliente = $request->input('nombre_cliente', 'Invitado');
        $notaEspecial = (string) $request->input('nota_especial', '');
        $metodoPago = $request->input('metodo_pago', 'Efectivo');

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'El carrito está vacío']);
        }

        if (! in_array($tipo, ['En mesa', 'Para llevar'], true)) {
            return response()->json(['success' => false, 'message' => 'Tipo de pedido no válido']);
        }

        if (! in_array($metodoPago, Pedido::METODOS_PAGO, true)) {
            return response()->json(['success' => false, 'message' => 'Método de pago no válido']);
        }

        try {
            $pedido = DB::transaction(function () use ($cart, $tipo, $mesaNumero, $direccionEntrega, $nombreCliente, $notaEspecial, $metodoPago) {
                $clienteId = Auth::guard('cliente')->id();

                $mesaId = null;
                if ($mesaNumero) {
                    $mesa = Mesa::where('numero', $mesaNumero)->first();
                    if ($mesa) {
                        $mesaId = $mesa->id;
                        $mesa->update(['estado' => 'Ocupada']);
                    }
                }

                $total = 0;
                foreach ($cart as $item) {
                    $total += $this->precioItem($item) * (int) ($item['qty'] ?? 0);
                }

                $numeroOrden = strtoupper(substr(md5(uniqid((string) random_int(0, PHP_INT_MAX), true)), 0, 8));

                $pedido = Pedido::create([
                    'numero_orden' => $numeroOrden,
                    'cliente_id' => $clienteId,
                    'mesa_id' => $mesaId,
                    'direccion_entrega' => $direccionEntrega,
                    'tipo' => $tipo,
                    'estado' => 'Recibido',
                    'subtotal' => $total,
                    'total' => $total,
                    'observaciones' => $notaEspecial,
                ]);

                $productoFallbackId = Producto::query()->orderBy('id')->value('id');

                foreach ($cart as $item) {
                    $precioUnitario = $this->precioItem($item);
                    $cantidad = (int) ($item['qty'] ?? 1);

                    $productoId = Producto::where('nombre', $item['nombre'] ?? null)->value('id') ?? $productoFallbackId;

                    PedidoItem::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $productoId,
                        'nombre_producto' => $item['nombre'] ?? 'Producto',
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $precioUnitario * $cantidad,
                    ]);
                }

                PedidoEstadoHistorial::create([
                    'pedido_id' => $pedido->id,
                    'estado' => 'Recibido',
                ]);

                Pago::create([
                    'pedido_id' => $pedido->id,
                    'metodo' => $metodoPago,
                    'total_pagado' => $total,
                ]);

                Ingreso::create([
                    'pedido_id' => $pedido->id,
                    'descripcion' => "Pedido #{$numeroOrden} - {$nombreCliente}",
                    'metodo' => $metodoPago,
                    'monto' => $total,
                    'fecha' => now()->toDateString(),
                ]);

                return $pedido;
            });

            return response()->json([
                'success' => true,
                'numero_orden' => $pedido->numero_orden,
                'pedido_id' => $pedido->id,
                'total' => (float) $pedido->total,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function precioItem(array $item): float
    {
        $precio = $item['precio'] ?? 0;

        return is_numeric($precio) ? (float) $precio : (float) preg_replace('/[^0-9]/', '', (string) $precio);
    }
}
