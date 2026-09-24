<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Parrilla &ndash; Pedido Confirmado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/confirmacion.css') }}">
</head>
<body>
<nav>
    <a class="nav-brand" href="{{ route('home') }}">
        <svg width="18" height="24" viewBox="0 0 32 42" fill="none"><path d="M16 0C16 0 28 10 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 16 8 12 8 12C8 12 8 18 12 20C12 20 10 14 16 8C16 8 14 16 18 20C20 18 22 14 20 8C24 12 28 18 24 26C24 26 27 23 26 19C27.5 21 28 23 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 10 16 0 16 0Z" fill="#f07000"/></svg>
        <div><div class="nav-brand-name">La Parrilla</div><div class="nav-brand-sub">Asadero &amp; Restaurante</div></div>
    </a>
</nav>

<div class="confirm-box">
    <div class="check-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#00c864" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>

    <h1 class="confirm-title">&iexcl;Pedido Recibido!</h1>
    <p class="confirm-sub">Tu pedido fue enviado a cocina exitosamente</p>

    <div class="estado-badge">
        <span class="dot-pulse"></span>
        En preparaci&oacute;n
    </div>

    <div class="orden-badge">#{{ $pedido->numero_orden }}</div>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Tipo</div>
            <div class="info-value">{{ $pedido->tipo }}</div>
        </div>
        @if ($pedido->mesa?->numero)
        <div class="info-item">
            <div class="info-label">Mesa</div>
            <div class="info-value">Mesa {{ $pedido->mesa->numero }}</div>
        </div>
        @endif
        <div class="info-item">
            <div class="info-label">M&eacute;todo de pago</div>
            <div class="info-value">{{ $pedido->pago->metodo ?? 'Efectivo' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Estado</div>
            <div class="info-value" style="color:#64b4ff">{{ $pedido->estado }}</div>
        </div>
    </div>

    <div class="items-list">
        <div class="items-list-header">Detalle del pedido</div>
        @foreach ($pedido->items as $item)
        <div class="item-row">
            <div>
                <div class="item-row-name">{{ $item->nombre_producto }}</div>
                <div class="item-row-qty">x{{ $item->cantidad }}</div>
            </div>
            <div class="item-row-price">${{ number_format($item->subtotal, 0, ',', '.') }}</div>
        </div>
        @endforeach
    </div>

    <div class="total-section">
        <div class="total-line"><span>Subtotal</span><span>${{ number_format($pedido->subtotal, 0, ',', '.') }}</span></div>
        <div class="total-line bold"><span>Total</span><span>${{ number_format($pedido->total, 0, ',', '.') }}</span></div>
    </div>

    @if ($pedido->observaciones)
    <div style="background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:24px;text-align:left">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Nota especial</div>
        <div style="font-size:14px">{{ $pedido->observaciones }}</div>
    </div>
    @endif

    <a href="{{ route('home') }}" class="btn-volver">&larr; Volver al men&uacute;</a>
</div>
</body>
</html>
