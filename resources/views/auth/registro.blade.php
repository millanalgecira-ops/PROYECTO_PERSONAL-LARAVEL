@extends('layouts.auth')

@section('title', 'Crear cuenta')
@section('brand-desc', '&Uacute;nete como cliente de La Parrilla. Completa el formulario para crear tu cuenta y empezar a ordenar.')

@section('form')
    <h1 class="form-title">Crear cuenta</h1>
    <p class="form-subtitle">Completa tus datos para registrarte</p>

    @if (session('alert'))
        @php($__a = session('alert'))
        <div class="{{ $__a['icon'] === 'success' ? 'success-msg' : 'error-msg' }}">{{ $__a['text'] }}</div>
    @endif

    <form method="POST" action="{{ route('registro.store') }}">
        @csrf
        <div class="field">
            <label for="nombres">Nombres</label>
            <div class="input-wrap">
                <input type="text" id="nombres" name="nombres" placeholder="Tus nombres" value="{{ old('nombres') }}">
                <span class="input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
            </div>
        </div>

        <div class="field">
            <label for="apellidos">Apellidos</label>
            <div class="input-wrap">
                <input type="text" id="apellidos" name="apellidos" placeholder="Tus apellidos" value="{{ old('apellidos') }}">
                <span class="input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
            </div>
        </div>

        <div class="field">
            <label for="email">Correo electr&oacute;nico</label>
            <div class="input-wrap">
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                <span class="input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
            </div>
        </div>

        <div class="field">
            <label for="telefono">Tel&eacute;fono</label>
            <div class="input-wrap">
                <input type="tel" id="telefono" name="telefono" placeholder="300 123 4567" value="{{ old('telefono') }}">
                <span class="input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.1a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </span>
            </div>
        </div>

        <div class="field">
            <label for="password">Contrase&ntilde;a</label>
            <div class="input-wrap">
                <input type="password" id="password" name="password" placeholder="M&iacute;nimo 6 caracteres" class="has-pass-icons">
                <div class="pass-icons">
                    <button type="button" onclick="togglePass('password', this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                    <span class="lock-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="field">
            <label for="confirmar_password">Confirmar contrase&ntilde;a</label>
            <div class="input-wrap">
                <input type="password" id="confirmar_password" name="confirmar_password" placeholder="Repite tu contrase&ntilde;a" class="has-pass-icons">
                <div class="pass-icons">
                    <button type="button" onclick="togglePass('confirmar_password', this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                    <span class="lock-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary outline">Crear cuenta</button>
    </form>

    <div class="form-footer">
        <span>&iquest;Ya tienes cuenta?</span>
        <a href="{{ route('login') }}" class="btn-ghost">Inicia sesi&oacute;n</a>
    </div>
@endsection
