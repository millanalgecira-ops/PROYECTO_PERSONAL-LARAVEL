@extends('layouts.staff', ['panel' => 'cocina'])

@section('title', 'Cocina')
@section('topbar-title', 'Comandas en curso')

@section('topbar-right')
    <div style="display:flex;align-items:center;gap:16px">
        <div class="live-badge"><span class="live-dot"></span>En vivo &mdash; {{ $pedidos->count() }} activo(s)</div>
        <a href="{{ route('cocina.comandas') }}" style="font-size:12px;color:var(--muted);text-decoration:none;border:1px solid var(--border);border-radius:6px;padding:6px 12px">&#8635; Actualizar</a>
    </div>
@endsection

@section('content')
    @php($colores = [
        'Recibido' => ['bg' => 'rgba(100,180,255,.12)', 'border' => 'rgba(100,180,255,.3)', 'color' => '#64b4ff'],
        'En preparacion' => ['bg' => 'rgba(255,200,0,.12)', 'border' => 'rgba(255,200,0,.3)', 'color' => '#ffc800'],
        'Listo' => ['bg' => 'rgba(100,220,130,.12)', 'border' => 'rgba(100,220,130,.3)', 'color' => '#64dc82'],
        'Entregado' => ['bg' => 'rgba(240,112,0,.12)', 'border' => 'rgba(240,112,0,.3)', 'color' => '#f07000'],
    ])

    <div class="filtros">
        <a href="{{ route('cocina.comandas') }}" class="btn-filtro {{ $filtro === 'todos' ? 'active' : '' }}">Todos <span class="count-badge">{{ $pedidos->count() }}</span></a>
        @foreach (['Recibido', 'En preparacion', 'Listo', 'Entregado'] as $e)
            <a href="{{ route('cocina.comandas', ['estado' => $e]) }}" class="btn-filtro {{ $filtro === $e ? 'active' : '' }}">
                {{ $e }}
                @if ($conteo->get($e))
                    <span class="count-badge">{{ $conteo->get($e) }}</span>
                @endif
            </a>
        @endforeach
    </div>

    @if ($lista->isEmpty())
        <div class="empty-state">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.2;margin-bottom:16px"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
            <h3>Sin comandas activas</h3>
            <p>Los nuevos pedidos aparecer&aacute;n aqu&iacute; autom&aacute;ticamente</p>
        </div>
    @else
    <div class="comandas-grid">
        @foreach ($lista as $p)
            @php($color = $colores[$p->estado] ?? $colores['Recibido'])
            @php($minutos = $p->creado_en->diffInMinutes(now()))
            <div class="comanda-card">
                <div class="comanda-header">
                    <div>
                        <div class="comanda-orden">#{{ $p->numero_orden }}</div>
                        <div class="comanda-tiempo">{{ $minutos < 1 ? 'Hace un momento' : ($minutos < 60 ? "Hace {$minutos} min" : $p->creado_en->format('H:i')) }}</div>
                    </div>
                    <span class="estado-badge" style="background:{{ $color['bg'] }};border:1px solid {{ $color['border'] }};color:{{ $color['color'] }}">{{ $p->estado }}</span>
                </div>
                <div class="comanda-meta">
                    <span class="meta-tag">{{ $p->tipo === 'En mesa' ? '🍽️' : '🥡' }} {{ $p->tipo }}</span>
                    @if ($p->mesa?->numero)
                        <span class="meta-tag">🪑 Mesa {{ $p->mesa->numero }}</span>
                    @endif
                    @if ($p->cliente?->nombre)
                        <span class="meta-tag">👤 {{ $p->cliente->nombre }}</span>
                    @endif
                </div>
                <div class="comanda-items">
                    @foreach ($p->items as $item)
                        <div class="comanda-item">
                            <div class="item-qty">{{ $item->cantidad }}x</div>
                            <div>
                                <div class="item-nombre">{{ $item->nombre_producto }}</div>
                                @if ($item->nota_especial)
                                    <div class="item-nota">⚠️ {{ $item->nota_especial }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if ($p->observaciones)
                        <div style="margin-top:10px;padding:8px 10px;background:rgba(255,200,0,.08);border:1px solid rgba(255,200,0,.2);border-radius:8px;font-size:12px;color:#ffc800">📝 {{ $p->observaciones }}</div>
                    @endif
                </div>
                <div class="comanda-footer">
                    @if ($p->estado === 'Recibido')
                        <form method="POST" action="{{ route('cocina.pedidos.cambiarEstado', $p) }}">
                            @csrf
                            <input type="hidden" name="estado" value="En preparacion">
                            <button type="submit" class="btn-estado btn-iniciar">🔥 Iniciar preparaci&oacute;n</button>
                        </form>
                    @elseif ($p->estado === 'En preparacion')
                        <form method="POST" action="{{ route('cocina.pedidos.cambiarEstado', $p) }}">
                            @csrf
                            <input type="hidden" name="estado" value="Listo">
                            <button type="submit" class="btn-estado btn-listo">&#9989; Marcar como listo</button>
                        </form>
                    @elseif ($p->estado === 'Listo')
                        <form method="POST" action="{{ route('cocina.pedidos.cambiarEstado', $p) }}">
                            @csrf
                            <input type="hidden" name="estado" value="Entregado">
                            <button type="submit" class="btn-estado btn-entregar">&#128276; Marcar como entregado</button>
                        </form>
                    @else
                        <button type="button" class="btn-estado btn-completado" disabled>&#10003; Entregado</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif
@endsection

@push('scripts')
<script>
setTimeout(() => location.reload(), 30000);
</script>
@endpush
