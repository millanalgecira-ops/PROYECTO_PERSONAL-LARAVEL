<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MesaController extends Controller
{
    public function index(): View
    {
        $mesas = Mesa::withCount([
            'pedidos as pedidos_activos' => fn ($query) => $query->whereNotIn('estado', ['Pagado', 'Cancelado']),
        ])->orderBy('numero')->get();

        // Ultimo pedido activo de cada mesa ocupada, para el modal de detalle.
        $pedidosPorMesa = [];
        foreach ($mesas as $mesa) {
            if ($mesa->estado === 'Ocupada' && $mesa->pedidos_activos > 0) {
                $pedido = $mesa->pedidos()
                    ->whereNotIn('estado', ['Pagado', 'Cancelado'])
                    ->with('items')
                    ->latest('creado_en')
                    ->first();

                if ($pedido) {
                    $pedidosPorMesa[$mesa->id] = [
                        'numero_orden' => $pedido->numero_orden,
                        'estado' => $pedido->estado,
                        'total' => (float) $pedido->total,
                        'tipo' => $pedido->tipo,
                        'hora' => $pedido->creado_en->format('H:i'),
                        'items' => $pedido->items->map(fn ($item) => "{$item->nombre_producto} x{$item->cantidad}")->implode(', '),
                    ];
                }
            }
        }

        return view('admin.mesas', [
            'mesas' => $mesas,
            'disponibles' => $mesas->where('estado', 'Disponible')->count(),
            'ocupadas' => $mesas->where('estado', 'Ocupada')->count(),
            'pedidosPorMesa' => $pedidosPorMesa,
        ]);
    }

    public function liberar(Mesa $mesa): RedirectResponse
    {
        $mesa->liberar();

        return $this->conAlerta('Mesa liberada correctamente');
    }

    public function liberarTodas(): RedirectResponse
    {
        DB::table('mesas')->update(['estado' => 'Disponible', 'liberada_en' => now()]);

        return $this->conAlerta('Todas las mesas han sido liberadas');
    }

    private function conAlerta(string $texto): RedirectResponse
    {
        return redirect()->route('admin.mesas.index')
            ->with('alert', ['icon' => 'success', 'text' => $texto]);
    }
}
