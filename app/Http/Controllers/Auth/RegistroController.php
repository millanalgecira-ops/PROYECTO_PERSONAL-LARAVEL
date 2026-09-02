<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegistroController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('cliente')->check()) {
            return redirect()->route('cliente.inicio');
        }

        return view('auth.registro');
    }

    public function store(Request $request): RedirectResponse
    {
        $nombres = trim((string) $request->input('nombres'));
        $apellidos = trim((string) $request->input('apellidos'));
        $email = trim((string) $request->input('email'));
        $password = trim((string) $request->input('password'));
        $confirmar = trim((string) $request->input('confirmar_password'));

        if ($nombres === '' || $apellidos === '' || $email === '' || $password === '' || $confirmar === '') {
            return $this->conAlerta('warning', 'Campos incompletos', 'Debe completar todos los campos');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->conAlerta('error', 'Correo inválido', 'Ingrese un correo válido');
        }

        if ($password !== $confirmar) {
            return $this->conAlerta('error', 'Error', 'Las contraseñas no coinciden');
        }

        if (strlen($password) < 6) {
            return $this->conAlerta('warning', 'Contraseña inválida', 'La contraseña debe tener al menos 6 caracteres');
        }

        if (Cliente::where('correo', $email)->exists()) {
            return $this->conAlerta('error', 'Correo existente', 'Este correo ya está registrado');
        }

        $cliente = Cliente::create([
            'nombre' => trim("{$nombres} {$apellidos}"),
            'correo' => $email,
            'password_hash' => Hash::make($password),
            'activo' => true,
        ]);

        // CL-001: registro exitoso -> inicia sesion automaticamente.
        Auth::guard('cliente')->login($cliente);
        $request->session()->regenerate();

        return redirect()->route('cliente.inicio');
    }

    private function conAlerta(string $icon, string $title, string $text): RedirectResponse
    {
        return redirect()->route('registro')
            ->withInput()
            ->with('alert', ['icon' => $icon, 'title' => $title, 'text' => $text]);
    }
}
