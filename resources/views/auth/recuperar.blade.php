@extends('layouts.auth')

@section('title', 'Recuperar contrase&ntilde;a')
@section('brand-desc', 'Recupera el acceso a tu cuenta de forma segura.')

@section('form')
    @if ($paso === 'completado')
        <h1 class="form-title">&iexcl;Listo!</h1>
        <p class="form-subtitle">Tu contrase&ntilde;a fue actualizada</p>
        <div class="alert alert-success">{!! $mensaje !!}</div>
        <div class="form-footer">
            <a href="{{ route('login') }}" class="btn-ghost">Ir al login</a>
        </div>

    @elseif ($paso === 'expirado')
        <h1 class="form-title">Enlace expirado</h1>
        <p class="form-subtitle">El enlace ya no es v&aacute;lido</p>
        <div class="alert alert-error">{{ $mensaje ?? 'Este enlace ha expirado o ya fue usado.' }}</div>
        <div class="form-footer">
            <span>Solicita uno nuevo:</span>
            <a href="{{ route('password.form') }}" class="btn-ghost">Recuperar contrase&ntilde;a</a>
        </div>

    @elseif ($paso === 'restablecer' && $tokenModel)
        <h1 class="form-title">Nueva contrase&ntilde;a</h1>
        <p class="form-subtitle">Ingresa tu nueva contrase&ntilde;a</p>
        @if (! empty($mensaje))
            <div class="alert alert-{{ $tipo }}">{{ $mensaje }}</div>
        @endif
        <form method="POST" action="{{ route('password.restablecer') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field">
                <label>Nueva contrase&ntilde;a</label>
                <input type="password" name="password" class="plain" placeholder="M&iacute;nimo 6 caracteres" required>
            </div>
            <div class="field">
                <label>Confirmar contrase&ntilde;a</label>
                <input type="password" name="confirmar" class="plain" placeholder="Repite tu contrase&ntilde;a" required>
            </div>
            <button type="submit" class="btn-primary outline">Guardar nueva contrase&ntilde;a</button>
        </form>

    @else
        <h1 class="form-title">Recuperar contrase&ntilde;a</h1>
        <p class="form-subtitle">Ingresa tu correo para continuar</p>
        @if (! empty($mensaje))
            <div class="alert alert-{{ $tipo }}">{!! $mensaje !!}</div>
        @endif
        <form method="POST" action="{{ route('password.enviar') }}">
            @csrf
            <div class="field">
                <label>Correo electr&oacute;nico</label>
                <input type="email" name="correo" class="plain" placeholder="correo@ejemplo.com" value="{{ $correo ?? old('correo') }}" required>
            </div>
            <button type="submit" class="btn-primary outline">Enviar enlace</button>
        </form>
        <div class="form-footer">
            <span>&iquest;Recordaste tu contrase&ntilde;a?</span>
            <a href="{{ route('login') }}" class="btn-ghost">Iniciar sesi&oacute;n</a>
        </div>
    @endif
@endsection
