<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Parrilla &ndash; @yield('title', 'Acceso')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<div class="layout">

    <div class="branding">
        <svg class="flame-icon" viewBox="0 0 32 42" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 0C16 0 28 10 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 16 8 12 8 12C8 12 8 18 12 20C12 20 10 14 16 8C16 8 14 16 18 20C20 18 22 14 20 8C24 12 28 18 24 26C24 26 27 23 26 19C27.5 21 28 23 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 10 16 0 16 0Z" fill="#f07000"/>
        </svg>
        <div class="brand-body">
            <p class="sistema-label">Sistema de Gesti&oacute;n</p>
            <span class="brand-name-la">La</span>
            <span class="brand-name-parrilla">Parrilla</span>
            <p class="brand-subtitle">Asadero &amp; Restaurante</p>
            <p class="brand-desc">@yield('brand-desc', 'Plataforma de La Parrilla.')</p>
        </div>
        <p class="footer">&copy; {{ date('Y') }} La Parrilla</p>
    </div>

    <div class="form-panel">
        <div class="form-box">
            @yield('form')
        </div>
    </div>

</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '' : 'var(--orange)';
}
</script>
@stack('scripts')
</body>
</html>
