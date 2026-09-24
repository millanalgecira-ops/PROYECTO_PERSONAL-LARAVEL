<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function inicio(): View
    {
        return view('cliente.inicio');
    }

    public function pedidos(): View
    {
        $pedidos = Pedido::with(['mesa', 'pago'])
            ->where('cliente_id', Auth::guard('cliente')->id())
            ->orderByDesc('creado_en')
            ->get();

        return view('cliente.pedidos', compact('pedidos'));
    }

    /**
     * Detalle de un pedido propio. Se verifica la pertenencia manualmente
     * (en vez de confiar solo en el route-model-binding) para que un
     * cliente jamas pueda ver el pedido de otro cambiando el id en la URL.
     */
    public function detalle(Pedido $pedido): View
    {
        abort_unless($pedido->cliente_id === Auth::guard('cliente')->id(), 404);

        $pedido->load(['mesa', 'pago', 'items']);

        return view('cliente.detalle', compact('pedido'));
    }
}
