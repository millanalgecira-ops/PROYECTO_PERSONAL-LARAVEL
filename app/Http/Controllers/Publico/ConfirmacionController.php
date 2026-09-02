<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfirmacionController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $numeroOrden = $request->query('orden');

        if (! $numeroOrden) {
            return redirect()->route('home');
        }

        $pedido = Pedido::with(['mesa', 'cliente', 'pago', 'items'])
            ->where('numero_orden', $numeroOrden)
            ->first();

        if (! $pedido) {
            return redirect()->route('home');
        }

        return view('publico.confirmacion', compact('pedido'));
    }
}
