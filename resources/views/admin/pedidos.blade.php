@extends('layouts.staff', ['panel' => 'admin'])

@section('title', 'Pedidos')
@section('topbar-title', 'Gesti&oacute;n de Pedidos')

@section('content')
    @php($badgeMap = ['Recibido'=>'badge-recibido','En preparacion'=>'badge-preparacion','Listo'=>'badge-listo','Entregado'=>'badge-entregado','Pagado'=>'badge-pagado','Cancelado'=>'badge-cancelado'])

    <div class="filtros">
        <a href="{{ route('admin.pedidos.index') }}" class="btn-filtro {{ $filtro === 'todos' ? 'active' : '' }}">Todos</a>
        @foreach ($estados as $e)
            <a href="{{ route('admin.pedidos.index', ['estado' => $e]) }}" class="btn-filtro {{ $filtro === $e ? 'active' : '' }}">{{ $e }}</a>
        @endforeach
    </div>

    <div class="section-card">
        @if ($pedidos->isEmpty())
            <div class="empty-state"><p>No hay pedidos {{ $filtro !== 'todos' ? "con estado \"{$filtro}\"" : 'registrados' }}</p></div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Mesa</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acci&oacute;n</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($pedidos as $p)
                <tr>
                    <td><span class="numero-orden">#{{ $p->numero_orden }}</span></td>
                    <td>{{ $p->cliente->nombre ?? 'Invitado' }}</td>
                    <td>{{ $p->tipo }}</td>
                    <td>{{ $p->mesa?->numero ? 'Mesa '.$p->mesa->numero : '—' }}</td>
                    <td style="color:var(--orange);font-weight:700">${{ number_format($p->total, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $badgeMap[$p->estado] ?? '' }}">{{ $p->estado }}</span></td>
                    <td style="color:var(--muted);font-size:12px">{{ $p->creado_en->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center">
                        <form method="POST" action="{{ route('admin.pedidos.cambiarEstado', $p) }}">
                            @csrf
                            <select name="estado" class="select-estado" onchange="this.form.submit()">
                                @foreach ($estados as $e)
                                    <option value="{{ $e }}" {{ $p->estado === $e ? 'selected' : '' }}>{{ $e }}</option>
                                @endforeach
                            </select>
                        </form>
                        @if (! in_array($p->estado, ['Cancelado', 'Pagado'], true))
                        <form method="POST" action="{{ route('admin.pedidos.cancelar', $p) }}"
                              onsubmit="return confirmarFormulario(event, this, '🚫', '¿Cancelar pedido?', 'El pedido #{{ $p->numero_orden }} será cancelado y la mesa quedará libre.')">
                            @csrf
                            <button type="submit" style="padding:6px 10px;border-radius:6px;background:rgba(255,80,80,.1);border:1px solid rgba(255,80,80,.25);color:#ff5050;font-size:11px;white-space:nowrap;cursor:pointer;font-family:'Barlow',sans-serif">Cancelar</button>
                        </form>
                        @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endsection
