<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Panel principal del administrador: estadisticas y listado de
     * usuarios del staff (Administrador / Cocina).
     */
    public function index(): View
    {
        $usuarios = Usuario::with('rol')->orderByDesc('creado_en')->get();

        return view('admin.dashboard', [
            'usuarios' => $usuarios,
            'total' => $usuarios->count(),
            'activos' => $usuarios->where('activo', true)->count(),
            'inactivos' => $usuarios->where('activo', false)->count(),
        ]);
    }
}
