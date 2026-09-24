<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Roles que el administrador puede asignar a personal interno.
     * (El rol "Cliente" no aplica aqui: los clientes se registran solos
     * desde el catalogo publico, en su propia tabla.)
     */
    private const ROLES = ['administrador' => 1, 'cocina' => 2];

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['required', 'in:administrador,cocina'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return $this->conAlerta('warning', 'Campos incompletos', 'Debe completar todos los campos obligatorios');
        }

        $datos = $validator->validated();

        if (Usuario::where('correo', $datos['email'])->exists()) {
            return $this->conAlerta('error', 'Correo existente', 'Este correo ya está registrado en el sistema');
        }

        Usuario::create([
            'nombre' => trim($datos['nombres'].' '.$datos['apellidos']),
            'correo' => $datos['email'],
            'telefono' => $datos['telefono'] ?? null,
            'password_hash' => Hash::make($datos['password']),
            'rol_id' => self::ROLES[$datos['rol']],
            'activo' => true,
        ]);

        return $this->conAlerta('success', 'Éxito', 'Usuario creado correctamente');
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'rol' => ['required', 'in:administrador,cocina'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return $this->conAlerta('warning', 'Campos incompletos', 'Revisa los datos del formulario');
        }

        $datos = $validator->validated();

        $usuario->nombre = trim($datos['nombres'].' '.$datos['apellidos']);
        $usuario->correo = $datos['email'];
        $usuario->rol_id = self::ROLES[$datos['rol']];

        if (! empty($datos['password'])) {
            $usuario->password_hash = Hash::make($datos['password']);
        }

        $usuario->save();

        return $this->conAlerta('success', 'Éxito', 'Usuario actualizado correctamente');
    }

    public function toggleEstado(Usuario $usuario): RedirectResponse
    {
        $usuario->update(['activo' => ! $usuario->activo]);

        $texto = $usuario->activo ? 'activado' : 'desactivado';

        return $this->conAlerta('success', 'Éxito', "Usuario {$texto} correctamente");
    }

    private function conAlerta(string $icon, string $title, string $text): RedirectResponse
    {
        return redirect()->route('admin.dashboard')
            ->with('alert', ['icon' => $icon, 'title' => $title, 'text' => $text]);
    }
}
