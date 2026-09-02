<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordRecoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordRecoveryController extends Controller
{
    public function __construct(private readonly PasswordRecoveryService $recovery) {}

    /**
     * Un unico formulario, con 4 posibles pasos segun la query string,
     * igual que Views/usuarios/recuperar.php en el proyecto original:
     * solicitar (por defecto) | restablecer | expirado | completado.
     */
    public function show(Request $request): View
    {
        $paso = $request->query('paso', 'solicitar');
        $token = $request->query('token');
        $tokenModel = null;

        if ($paso === 'restablecer' && $token) {
            $tokenModel = $this->recovery->tokenValido($token);

            if (! $tokenModel) {
                $paso = 'expirado';
            }
        }

        return view('auth.recuperar', [
            'paso' => $paso,
            'token' => $token,
            'tokenModel' => $tokenModel,
        ]);
    }

    public function enviarEnlace(Request $request): View|RedirectResponse
    {
        $correo = trim((string) $request->input('correo'));

        if (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return $this->mensaje('solicitar', 'error', 'Ingresa un correo electrónico válido.', $correo);
        }

        $tokenModel = $this->recovery->generarToken($correo);

        if (! $tokenModel) {
            return $this->mensaje('solicitar', 'error', 'Este correo no está registrado. Verifica los datos o crea una cuenta nueva.', $correo);
        }

        $link = route('password.form', ['paso' => 'restablecer', 'token' => $tokenModel->token]);

        return view('auth.recuperar', [
            'paso' => 'solicitar',
            'token' => null,
            'tokenModel' => null,
            'mensaje' => "Enlace de restablecimiento generado. <a href=\"{$link}\" style=\"color:var(--orange)\">Haz clic aquí para restablecer tu contraseña</a> (válido por 1 hora).",
            'tipo' => 'success',
            'correo' => $correo,
        ]);
    }

    public function restablecer(Request $request): View
    {
        $token = $request->input('token');
        $tokenModel = $token ? $this->recovery->tokenValido($token) : null;

        if (! $tokenModel) {
            return view('auth.recuperar', ['paso' => 'expirado', 'token' => $token, 'tokenModel' => null]);
        }

        $nueva = (string) $request->input('password');
        $confirma = (string) $request->input('confirmar');

        if (strlen($nueva) < 6) {
            return $this->mensajeRestablecer($tokenModel, $token, 'error', 'La contraseña debe tener al menos 6 caracteres.');
        }

        if ($nueva !== $confirma) {
            return $this->mensajeRestablecer($tokenModel, $token, 'error', 'Las contraseñas no coinciden.');
        }

        $this->recovery->restablecer($tokenModel, $nueva);

        return view('auth.recuperar', [
            'paso' => 'completado',
            'token' => $token,
            'tokenModel' => null,
            'mensaje' => 'Contraseña actualizada exitosamente. Ya puedes iniciar sesión.',
            'tipo' => 'success',
        ]);
    }

    private function mensaje(string $paso, string $tipo, string $texto, ?string $correo = null): View
    {
        return view('auth.recuperar', [
            'paso' => $paso,
            'token' => null,
            'tokenModel' => null,
            'mensaje' => $texto,
            'tipo' => $tipo,
            'correo' => $correo,
        ]);
    }

    private function mensajeRestablecer($tokenModel, ?string $token, string $tipo, string $texto): View
    {
        return view('auth.recuperar', [
            'paso' => 'restablecer',
            'token' => $token,
            'tokenModel' => $tokenModel,
            'mensaje' => $texto,
            'tipo' => $tipo,
        ]);
    }
}
