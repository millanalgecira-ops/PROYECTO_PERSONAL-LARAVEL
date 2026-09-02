<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\TokenRecuperacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Recuperacion de contrasena compartida entre "usuarios" (staff) y
 * "clientes" (publico), tal como en el proyecto original: una unica tabla
 * de tokens (tokens_recuperacion) sirve a ambas tablas de autenticacion.
 */
class PasswordRecoveryService
{
    /**
     * Busca la cuenta activa (cliente o usuario) para el correo dado y
     * genera un token de recuperacion valido por 1 hora.
     *
     * @return TokenRecuperacion|null null si no existe ninguna cuenta activa con ese correo.
     */
    public function generarToken(string $correo): ?TokenRecuperacion
    {
        $cliente = Cliente::where('correo', $correo)->where('activo', true)->first();
        $usuario = $cliente ? null : Usuario::where('correo', $correo)->where('activo', true)->first();

        if (! $cliente && ! $usuario) {
            return null;
        }

        return TokenRecuperacion::create([
            'cliente_id' => $cliente?->id,
            'usuario_id' => $usuario?->id,
            'token' => Str::random(64),
            'expira_en' => now()->addHour(),
            'usado' => false,
        ]);
    }

    /**
     * Indica si existe (en cualquiera de las dos tablas) una cuenta
     * registrada con ese correo, y si esta activa.
     */
    public function estadoCuenta(string $correo): string
    {
        $cliente = Cliente::where('correo', $correo)->first();
        $usuario = $cliente ? null : Usuario::where('correo', $correo)->first();
        $cuenta = $cliente ?? $usuario;

        if (! $cuenta) {
            return 'no_encontrada';
        }

        return $cuenta->activo ? 'activa' : 'inactiva';
    }

    public function tokenValido(string $token): ?TokenRecuperacion
    {
        $tokenModel = TokenRecuperacion::where('token', $token)
            ->where('usado', false)
            ->where('expira_en', '>', now())
            ->first();

        return $tokenModel;
    }

    public function restablecer(TokenRecuperacion $tokenModel, string $nuevaPassword): void
    {
        $hash = Hash::make($nuevaPassword);

        if ($tokenModel->cliente_id) {
            Cliente::whereKey($tokenModel->cliente_id)->update(['password_hash' => $hash]);
        } else {
            Usuario::whereKey($tokenModel->usuario_id)->update(['password_hash' => $hash]);
        }

        $tokenModel->update(['usado' => true]);
    }
}
