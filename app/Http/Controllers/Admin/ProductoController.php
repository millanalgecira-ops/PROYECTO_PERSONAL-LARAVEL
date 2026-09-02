<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with('categoria')
            ->join('categorias', 'categorias.id', '=', 'productos.categoria_id')
            ->orderBy('categorias.nombre')
            ->orderBy('productos.nombre')
            ->select('productos.*')
            ->get();

        $categorias = Categoria::where('activa', true)->orderBy('orden')->orderBy('nombre')->get();

        return view('admin.productos', compact('productos', 'categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->reglas());

        if ($validator->fails()) {
            return $this->conAlerta('warning', 'Campos incompletos', 'Nombre, precio y categoría son obligatorios');
        }

        $datos = $validator->validated();

        Producto::create([
            'categoria_id' => $datos['categoria_id'],
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'imagen_url' => $datos['imagen_url'] ?? null,
            'precio' => $datos['precio'],
            'popular' => $request->boolean('popular'),
            'disponible' => $request->boolean('disponible'),
        ]);

        return $this->conAlerta('success', 'Éxito', 'Producto creado correctamente');
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->reglas());

        if ($validator->fails()) {
            return $this->conAlerta('warning', 'Campos incompletos', 'Nombre, precio y categoría son obligatorios');
        }

        $datos = $validator->validated();

        $producto->update([
            'categoria_id' => $datos['categoria_id'],
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'imagen_url' => $datos['imagen_url'] ?? null,
            'precio' => $datos['precio'],
            'popular' => $request->boolean('popular'),
            'disponible' => $request->boolean('disponible'),
        ]);

        return $this->conAlerta('success', 'Éxito', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return $this->conAlerta('success', 'Eliminado', 'Producto eliminado correctamente');
    }

    public function toggleDisponible(Producto $producto): RedirectResponse
    {
        $producto->update(['disponible' => ! $producto->disponible]);

        return $this->conAlerta('success', 'Éxito', 'Estado del producto actualizado');
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function reglas(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'imagen_url' => ['nullable', 'url', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ];
    }

    private function conAlerta(string $icon, string $title, string $text): RedirectResponse
    {
        return redirect()->route('admin.productos.index')
            ->with('alert', ['icon' => $icon, 'title' => $title, 'text' => $text]);
    }
}
