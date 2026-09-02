@extends('layouts.cliente')

@section('title', 'Detalle del Pedido')
@section('topbar-title', 'Detalle del Pedido')

@section('content')
    <a href="{{ route('cliente.pedidos') }}" class="btn-back">&larr; Volver a mis pedidos</a>
    <div class="detalle-card">
        <div style="margin-bottom:20px">
            <div style="font-family:'Bebas Neue',sans-serif;font-size:28px;color:var(--orange)">#{{ $pedido->numero_orden }}</div>
            <div style="font-size:12px;color:var(--muted)">{{ $pedido->creado_en->format('d/m/Y H:i') }}</div>
        </div>
        <div class="detalle-grid">
            <div class="detalle-item"><div class="detalle-label">Tipo</div><div class="detalle-value">{{ $pedido->tipo }}</div></div>
            @if ($pedido->mesa?->numero)
                <div class="detalle-item"><div class="detalle-label">Mesa</div><div class="detalle-value">Mesa {{ $pedido->mesa->numero }}</div></div>
            @endif
            <div class="detalle-item"><div class="detalle-label">Estado</div><div class="detalle-value">{{ $pedido->estado }}</div></div>
            <div class="detalle-item"><div class="detalle-label">M&eacute;todo de pago</div><div class="detalle-value">{{ $pedido->pago->metodo ?? 'Efectivo' }}</div></div>
        </div>
        <div style="background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:16px">
            @foreach ($pedido->items as $item)
                <div class="item-row">
                    <div>
                        <div style="font-size:14px;font-weight:600">{{ $item->nombre_producto }}</div>
                        <div style="font-size:12px;color:var(--muted)">x{{ $item->cantidad }} &times; ${{ number_format($item->precio_unitario, 0, ',', '.') }}</div>
                        @if ($item->nota_especial)
                            <div style="font-size:12px;color:#ffc800;margin-top:2px">⚠️ {{ $item->nota_especial }}</div>
                        @endif
                    </div>
                    <div style="color:var(--orange);font-weight:700">${{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach
            <div class="total-line"><span>Total</span><span>${{ number_format($pedido->total, 0, ',', '.') }}</span></div>
        </div>
    </div>
@endsection
