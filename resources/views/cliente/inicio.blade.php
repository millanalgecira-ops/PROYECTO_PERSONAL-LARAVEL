@extends('layouts.cliente')

@section('title', 'Mi cuenta')
@section('topbar-title', 'Bienvenido')

@section('content')
    <div class="welcome-card">
        <div class="welcome-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <h2>Hola, {{ explode(' ', auth()->guard('cliente')->user()->nombre)[0] }}!</h2>
        <p>Explora nuestro men&uacute; y realiza tu pedido f&aacute;cilmente. Tambi&eacute;n puedes consultar tus pedidos anteriores.</p>
        <a href="{{ route('home') }}" class="btn-orange">Ver Men&uacute; &rarr;</a>
    </div>
@endsection
