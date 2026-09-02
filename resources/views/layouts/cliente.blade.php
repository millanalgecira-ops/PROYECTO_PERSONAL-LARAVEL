<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Parrilla &ndash; @yield('title', 'Mi cuenta')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <svg width="20" height="26" viewBox="0 0 32 42" fill="none"><path d="M16 0C16 0 28 10 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 16 8 12 8 12C8 12 8 18 12 20C12 20 10 14 16 8C16 8 14 16 18 20C20 18 22 14 20 8C24 12 28 18 24 26C24 26 27 23 26 19C27.5 21 28 23 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 10 16 0 16 0Z" fill="#f07000"/></svg>
        <div><div class="sidebar-brand-name">La Parrilla</div><div class="sidebar-brand-sub">Mi cuenta</div></div>
    </div>
    <nav class="sidebar-nav">
        <p class="nav-label">Principal</p>
        <a class="nav-item {{ request()->routeIs('cliente.inicio') ? 'active' : '' }}" href="{{ route('cliente.inicio') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Inicio
        </a>
        <a class="nav-item" href="{{ route('home') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Ver Men&uacute;
        </a>
        <a class="nav-item {{ request()->routeIs('cliente.pedidos') || request()->routeIs('cliente.detalle') ? 'active' : '' }}" href="{{ route('cliente.pedidos') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
            Mis Pedidos
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->guard('cliente')->user()->nombre, 0, 1)) }}</div>
            <div><div class="user-name">{{ auth()->guard('cliente')->user()->nombre }}</div><div class="user-role">Cliente</div></div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px">
            @csrf
            <button type="submit" class="btn-logout" style="width:100%;justify-content:center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Cerrar sesi&oacute;n
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <span class="topbar-title">@yield('topbar-title', 'Bienvenido')</span>
        <span style="font-size:13px;color:var(--muted)">{{ auth()->guard('cliente')->user()->nombre }}</span>
    </div>
    <div class="content">
        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
