@extends('layouts.staff', ['panel' => 'cocina'])

@section('title', 'Disponibilidad de Productos')
@section('topbar-title', 'Disponibilidad de Productos')

@section('content')
    <div class="prod-grid">
        @foreach ($productos as $prod)
            <div class="prod-card" style="border:1px solid {{ $prod->disponible ? 'var(--border)' : 'rgba(255,80,80,.3)' }}">
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div>
                        <div class="prod-name">{{ $prod->nombre }}</div>
                        <div class="prod-cat">{{ $prod->categoria->nombre ?? '' }}</div>
                    </div>
                    <span style="padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;background:{{ $prod->disponible ? 'rgba(100,220,130,.12)' : 'rgba(255,80,80,.12)' }};color:{{ $prod->disponible ? '#64dc82' : '#ff5050' }};border:1px solid {{ $prod->disponible ? 'rgba(100,220,130,.3)' : 'rgba(255,80,80,.3)' }}">
                        {{ $prod->disponible ? 'Disponible' : 'Agotado' }}
                    </span>
                </div>
                @if ($prod->disponible)
                    <form method="POST" action="{{ route('cocina.productos.agotar', $prod) }}"
                          onsubmit="return confirmarFormulario(event, this, '⚠️', '¿Reportar como agotado?', 'El producto se ocultará del catálogo del cliente de inmediato.')">
                        @csrf
                        <button type="submit" class="btn-agotar" style="width:100%;border:1px solid rgba(255,80,80,.25);cursor:pointer">&#9888;&#65039; Reportar agotado</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('cocina.productos.activar', $prod) }}"
                          onsubmit="return confirmarFormulario(event, this, '✅', '¿Marcar como disponible?', 'El producto volverá a aparecer en el catálogo del cliente.')">
                        @csrf
                        <button type="submit" class="btn-activar" style="width:100%;border:1px solid rgba(100,220,130,.25);cursor:pointer">&#9989; Marcar disponible</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endsection
