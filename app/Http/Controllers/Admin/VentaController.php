<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingreso;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class VentaController extends Controller
{
    public function index(Request $request): View
    {
        $fechaInicio = $request->query('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->query('fecha_fin', now()->toDateString());

        $hoy = Carbon::today();

        $resumen = Pedido::query()
            ->leftJoin('pagos', 'pagos.pedido_id', '=', 'pedidos.id')
            ->whereDate('pedidos.creado_en', $hoy)
            ->whereIn('pedidos.estado', ['Pagado', 'Entregado'])
            ->selectRaw('COUNT(DISTINCT pedidos.id) as total_pedidos, COALESCE(SUM(pagos.total_pagado), 0) as total_vendido, COALESCE(AVG(pagos.total_pagado), 0) as ticket_promedio')
            ->first();

        $ingresos = Ingreso::with('registradoPor')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderByDesc('fecha')
            ->orderByDesc('creado_en')
            ->get();

        return view('admin.ventas', [
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'resumen' => $resumen,
            'ingresos' => $ingresos,
            'totalRango' => $ingresos->sum('monto'),
        ]);
    }
}
