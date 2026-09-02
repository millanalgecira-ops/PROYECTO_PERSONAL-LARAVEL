<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoEstadoController extends Controller
{
    /**
     * Cambia el estado de un pedido. Accesible tanto para Administrador
     * (panel de pedidos) como para Cocina (panel de comandas); cada uno
     * vuelve a su propio panel, igual que el PedidoController original.
     */
    public function cambiarEstado(Request $request, Pedido $pedido): RedirectResponse
    {
        $estado = $request->input('estado');

        if (in_array($estado, Pedido::ESTADOS, true)) {
            $pedido->update(['estado' => $estado]);

            $pedido->historialEstados()->create([
                'estado' => $estado,
                'cambiado_por' => Auth::guard('web')->id(),
            ]);
        }

        $usuario = Auth::guard('web')->user();
        $destino = $usuario?->rolNombre() === 'cocina' ? 'cocina.comandas' : 'admin.pedidos.index';

        return redirect()->route($destino)
            ->with('alert', ['icon' => 'success', 'text' => "Estado actualizado a: {$estado}"]);
    }
}
