<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $filtro = $request->query('estado', 'todos');

        $pedidos = Pedido::with(['cliente', 'mesa'])
            ->when($filtro !== 'todos', fn ($query) => $query->where('estado', $filtro))
            ->orderByDesc('creado_en')
            ->get();

        return view('admin.pedidos', [
            'pedidos' => $pedidos,
            'filtro' => $filtro,
            'estados' => Pedido::ESTADOS,
        ]);
    }

    /**
     * Solo el administrador puede cancelar un pedido directamente
     * (igual que en PedidoController.php original).
     */
    public function cancelar(Pedido $pedido): RedirectResponse
    {
        if (! in_array($pedido->estado, ['Pagado', 'Cancelado'], true)) {
            $pedido->update([
                'estado' => 'Cancelado',
                'cancelado_por' => Auth::guard('web')->id(),
            ]);

            $pedido->historialEstados()->create([
                'estado' => 'Cancelado',
                'cambiado_por' => Auth::guard('web')->id(),
            ]);

            $pedido->mesa?->liberar();
        }

        return redirect()->route('admin.pedidos.index')
            ->with('alert', ['icon' => 'success', 'title' => 'Listo', 'text' => 'Pedido cancelado correctamente']);
    }
}
