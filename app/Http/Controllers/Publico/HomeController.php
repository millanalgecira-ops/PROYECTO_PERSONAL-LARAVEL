<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Imagenes de respaldo para productos sin imagen_url propia
     * (identicas a las del HomeController original).
     */
    public const IMAGENES_DEFECTO = [
        'https://images.unsplash.com/photo-1600891964092-4316c288032e?w=600&q=80',
        'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=600&q=80',
        'https://images.unsplash.com/photo-1562967914-608f82629710?w=600&q=80',
        'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=600&q=80',
        'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=600&q=80',
        'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=600&q=80',
    ];

    public function index(): View
    {
        $categoriasMenu = Categoria::where('activa', true)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->withCount([
                'productos as total_productos',
                'productos as disponibles' => fn ($query) => $query->where('disponible', true),
            ])
            ->get();

        $todosProductos = Producto::whereHas('categoria', fn ($query) => $query->where('activa', true))
            ->orderByDesc('popular')
            ->orderBy('nombre')
            ->get();

        return view('publico.home', [
            'categoriasMenu' => $categoriasMenu,
            'todosProductos' => $todosProductos,
            'imgsDefault' => self::IMAGENES_DEFECTO,
        ]);
    }
}
