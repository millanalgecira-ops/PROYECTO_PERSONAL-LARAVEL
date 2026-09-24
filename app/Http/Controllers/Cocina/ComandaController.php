<?php

namespace App\Http\Controllers\Cocina;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoAgotamiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ComandaController extends Controller
{
    /**
     * Comandas en curso (pedidos que no estan Pagados/Cancelados),
     * ordenadas por estado y luego por antiguedad, igual que el
     * panel de cocina original.
     */
    public function comandas(Request $request): View
    {
        $orden = ['Recibido' => 1, 'En preparacion' => 2, 'Listo' => 3, 'Entregado' => 4];

        // sortBy es estable (PHP 8+), asi que ordenar primero por fecha y
        // luego por estado produce el orden combinado correcto.
        $pedidos = Pedido::with(['mesa', 'cliente', 'items'])
            ->whereNotIn('estado', ['Pagado', 'Cancelado'])
            ->get()
            ->sortBy('creado_en')
            ->sortBy(fn (Pedido $pedido) => $orden[$pedido->estado] ?? 99)
            ->values();

        $filtro = $request->query('estado', 'todos');
        $conteo = $pedidos->countBy('estado');
        $lista = $filtro === 'todos' ? $pedidos : $pedidos->where('estado', $filtro)->values();

        return view('cocina.comandas', [
            'pedidos' => $pedidos,
            'lista' => $lista,
            'filtro' => $filtro,
            'conteo' => $conteo,
        ]);
    }

    public function productos(): View
    {
        $productos = Producto::with('categoria')
            ->orderByDesc('disponible')
            ->join('categorias', 'categorias.id', '=', 'productos.categoria_id')
            ->orderBy('categorias.nombre')
            ->orderBy('productos.nombre')
            ->select('productos.*')
            ->get();

        return view('cocina.productos', compact('productos'));
    }

    public function agotar(Producto $producto): RedirectResponse
    {
        $producto->update(['disponible' => false]);

        ProductoAgotamiento::create([
            'producto_id' => $producto->id,
            'reportado_por' => Auth::guard('web')->id(),
            'motivo' => 'Reportado por cocina',
        ]);

        return redirect()->route('cocina.productos')
            ->with('alert', ['icon' => 'warning', 'text' => 'Producto marcado como agotado']);
    }

    public function activar(Producto $producto): RedirectResponse
    {
        $producto->update(['disponible' => true]);

        return redirect()->route('cocina.productos')
            ->with('alert', ['icon' => 'success', 'text' => 'Producto reactivado en el catálogo']);
    }
}
