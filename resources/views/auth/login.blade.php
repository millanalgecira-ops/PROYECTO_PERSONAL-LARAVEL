@extends('layouts.auth')

@section('title', 'Acceso al sistema')
@section('brand-desc', 'Plataforma de administraci&oacute;n interna para el equipo de La Parrilla. Acceso exclusivo para personal autorizado. Los clientes tambi&eacute;n inician sesi&oacute;n aqu&iacute; para ver su cuenta.')

@section('form')
    <h1 class="form-title">Acceso al sistema</h1>
    <p class="form-subtitle">Ingresa tus credenciales para continuar</p>

    @if (session('alert'))
        <div class="error-msg">{{ session('alert')['text'] }}</div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="field">
            <label for="email">Correo electr&oacute;nico</label>
            <div class="input-wrap">
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com"
                       value="{{ old('email') }}" autocomplete="username">
                <span class="input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
            </div>
        </div>

        <div class="field">
            <label for="password">Contrase&ntilde;a</label>
            <div class="input-wrap">
                <input type="password" id="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                       class="has-pass-icons" autocomplete="current-password">
                <div class="pass-icons">
                    <button type="button" onclick="togglePass('password', this)" title="Mostrar contrase&ntilde;a">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="lock-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary outline">Ingresar</button>
    </form>

    <div style="text-align:center;margin-top:16px">
        <a href="{{ route('password.form') }}" class="link-muted">&iquest;Olvidaste tu contrase&ntilde;a?</a>
    </div>

    <div class="form-footer">
        <span>&iquest;No tienes cuenta?</span>
        <a href="{{ route('registro') }}" class="btn-ghost">Reg&iacute;strate aqu&iacute;</a>
    </div>
@endsection
