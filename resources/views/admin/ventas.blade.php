@extends('layouts.staff', ['panel' => 'admin'])

@section('title', 'Ventas e Ingresos')
@section('topbar-title', 'Ventas e Ingresos')

@section('content')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Ventas totales hoy</div>
            <div class="stat-value">${{ number_format($resumen->total_vendido, 0, ',', '.') }}</div>
            <div class="stat-sub">{{ now()->format('d/m/Y') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pedidos completados hoy</div>
            <div class="stat-value">{{ $resumen->total_pedidos }}</div>
            <div class="stat-sub">Pagados o entregados</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ticket promedio hoy</div>
            <div class="stat-value">${{ number_format($resumen->ticket_promedio, 0, ',', '.') }}</div>
            <div class="stat-sub">Por pedido</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total en rango</div>
            <div class="stat-value">${{ number_format($totalRango, 0, ',', '.') }}</div>
            <div class="stat-sub">{{ $fechaInicio }} &ndash; {{ $fechaFin }}</div>
        </div>
    </div>

    <p class="section-title">Historial de ingresos</p>
    <form method="GET" class="filtro-form">
        <div class="filtro-field">
            <label>Desde</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}">
        </div>
        <div class="filtro-field">
            <label>Hasta</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}">
        </div>
        <button type="submit" class="btn-orange">Filtrar</button>
    </form>

    <div class="section-card">
        @if ($ingresos->isEmpty())
            <div class="empty-state">No hay ingresos registrados en este per&iacute;odo</div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripci&oacute;n</th>
                    <th>M&eacute;todo</th>
                    <th>Registrado por</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ingresos as $i)
                <tr>
                    <td style="color:var(--muted);font-size:12px">{{ $i->fecha->format('d/m/Y') }}</td>
                    <td>{{ $i->descripcion }}</td>
                    <td><span class="badge">{{ $i->metodo }}</span></td>
                    <td style="color:var(--muted)">{{ $i->registradoPor->nombre ?? 'Sistema' }}</td>
                    <td style="color:var(--orange);font-weight:700">${{ number_format($i->monto, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align:right;color:var(--label)">Total del per&iacute;odo:</td>
                <td style="color:var(--orange)">${{ number_format($totalRango, 0, ',', '.') }}</td>
            </tr>
            </tbody>
        </table>
        @endif
    </div>
@endsection
