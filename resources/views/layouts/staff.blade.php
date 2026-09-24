<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Parrilla &ndash; @yield('title', 'Panel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <svg width="20" height="26" viewBox="0 0 32 42" fill="none"><path d="M16 0C16 0 28 10 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 16 8 12 8 12C8 12 8 18 12 20C12 20 10 14 16 8C16 8 14 16 18 20C20 18 22 14 20 8C24 12 28 18 24 26C24 26 27 23 26 19C27.5 21 28 23 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 10 16 0 16 0Z" fill="#f07000"/></svg>
        <div>
            <div class="sidebar-brand-name">La Parrilla</div>
            <div class="sidebar-brand-sub">{{ $panel === 'cocina' ? 'Cocina' : 'Administrador' }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @if ($panel === 'admin')
            <p class="nav-label">Principal</p>
            <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <p class="nav-label">Gesti&oacute;n</p>
            <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Usuarios
            </a>
            <a class="nav-item {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}" href="{{ route('admin.productos.index') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Productos
            </a>
            <a class="nav-item {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}" href="{{ route('admin.pedidos.index') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
                Pedidos
            </a>
            <a class="nav-item {{ request()->routeIs('admin.mesas.*') ? 'active' : '' }}" href="{{ route('admin.mesas.index') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                Mesas
            </a>
            <a class="nav-item {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}" href="{{ route('admin.ventas.index') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Ventas e Ingresos
            </a>
        @else
            <p class="nav-label">Panel</p>
            <a class="nav-item {{ request()->routeIs('cocina.comandas') ? 'active' : '' }}" href="{{ route('cocina.comandas') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                Comandas
            </a>
            <a class="nav-item {{ request()->routeIs('cocina.productos') ? 'active' : '' }}" href="{{ route('cocina.productos') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Productos
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->guard('web')->user()->nombre, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->guard('web')->user()->nombre }}</div>
                <div class="user-role">{{ $panel === 'cocina' ? 'Cocina' : 'Administrador' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px">
            @csrf
            <button type="submit" class="btn-logout" style="width:100%;justify-content:center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Cerrar sesi&oacute;n
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="main">
    <div class="topbar">
        <span class="topbar-title">@yield('topbar-title', 'Panel')</span>
        @hasSection('topbar-right')
            @yield('topbar-right')
        @else
            <span style="font-size:13px;color:var(--muted)">Bienvenido, {{ auth()->guard('web')->user()->nombre }}</span>
        @endif
    </div>

    <div class="content">
        @if (session('alert'))
            @php($__alert = session('alert'))
            @php($__cls = $__alert['icon'] === 'success' ? 'alert-success' : ($__alert['icon'] === 'warning' ? 'alert-warning' : 'alert-error'))
            <div class="alert-box {{ $__cls }}">{{ $__alert['text'] }}</div>
        @endif

        @yield('content')
    </div>
</div>

@yield('modals')

@include('partials.confirm-modal')

<script src="{{ asset('js/dashboard.js') }}"></script>
@stack('scripts')
</body>
</html>
