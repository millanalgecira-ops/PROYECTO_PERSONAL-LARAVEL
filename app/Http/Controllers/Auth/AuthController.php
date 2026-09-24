<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return $this->redirigirStaff(Auth::guard('web')->user());
        }

        if (Auth::guard('cliente')->check()) {
            return redirect()->route('cliente.inicio');
        }

        return view('auth.login');
    }

    /**
     * Replica el flujo del AuthController original: primero intenta la
     * tabla "usuarios" (Administrador/Cocina) y, si no aplica, la tabla
     * "clientes".
     */
    public function login(Request $request): RedirectResponse
    {
        $email = trim((string) $request->input('email'));
        $password = trim((string) $request->input('password'));

        if ($email === '' || $password === '') {
            return $this->conAlerta('warning', 'Campos incompletos', 'Debe ingresar correo y contraseña');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->conAlerta('error', 'Correo inválido', 'Ingrese un correo electrónico válido');
        }

        // ── Tabla usuarios (Administrador / Cocina) ──
        $usuario = Usuario::where('correo', $email)->where('activo', true)->first();

        if ($usuario) {
            if (! Hash::check($password, $usuario->password_hash)) {
                return $this->conAlerta('error', 'Credenciales incorrectas', 'Correo o contraseña incorrectos');
            }

            Auth::guard('web')->login($usuario);
            $request->session()->regenerate();
            $usuario->forceFill(['ultimo_acceso' => now()])->save();

            return $this->redirigirStaff($usuario);
        }

        // ── Tabla clientes ──
        $cliente = Cliente::where('correo', $email)->where('activo', true)->first();

        if (! $cliente) {
            $inactivo = Cliente::where('correo', $email)->value('activo');

            if ($inactivo !== null && ! $inactivo) {
                return $this->conAlerta('error', 'Cuenta inactiva', 'Tu cuenta está inactiva. Contacta al administrador del asadero.');
            }

            return $this->conAlerta('error', 'Usuario no encontrado', 'Correo o contraseña incorrectos');
        }

        if (! Hash::check($password, $cliente->password_hash)) {
            return $this->conAlerta('error', 'Contraseña incorrecta', 'Verifique sus credenciales');
        }

        Auth::guard('cliente')->login($cliente);
        $request->session()->regenerate();
        $cliente->forceFill(['ultimo_acceso' => now()])->save();

        return redirect()->route('cliente.inicio');
    }

    public function logout(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('cliente')->check()) {
            Auth::guard('cliente')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirigirStaff(Usuario $usuario): RedirectResponse
    {
        return match ($usuario->rolNombre()) {
            'administrador' => redirect()->route('admin.dashboard'),
            'cocina' => redirect()->route('cocina.comandas'),
            default => $this->conAlerta('error', 'Rol no válido', 'No se pudo determinar el acceso del usuario'),
        };
    }

    private function conAlerta(string $icon, string $title, string $text): RedirectResponse
    {
        return redirect()->route('login')
            ->withInput()
            ->with('alert', ['icon' => $icon, 'title' => $title, 'text' => $text]);
    }
}
