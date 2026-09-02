@extends('layouts.cliente')

@section('title', 'Mis Pedidos')
@section('topbar-title', 'Mis Pedidos')

@section('content')
    <div class="section-card">
        <div class="section-head"><h2>Historial de pedidos</h2></div>
        @if ($pedidos->isEmpty())
            <div class="empty-state">
                <p>A&uacute;n no tienes pedidos. &iexcl;Explora el cat&aacute;logo y haz tu primer pedido!</p>
                <a href="{{ route('home') }}" class="btn-orange">Ver Men&uacute; &rarr;</a>
            </div>
        @else
            <div class="filtro-bar">
                <select id="filtroEstado" onchange="filtrarPedidos()">
                    <option value="todos">Todos los estados</option>
                    <option value="Recibido">Recibido</option>
                    <option value="En preparacion">En preparaci&oacute;n</option>
                    <option value="Listo">Listo</option>
                    <option value="Entregado">Entregado</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
                <input type="date" id="filtroFecha" onchange="filtrarPedidos()">
                <button type="button" class="btn-limpiar" onclick="limpiarFiltros()">Limpiar filtros</button>
            </div>
            <table id="tablaPedidos">
                <thead><tr><th>Orden</th><th>Fecha</th><th>Tipo</th><th>Total</th><th>Estado</th><th>Detalle</th></tr></thead>
                <tbody>
                @php($badgeMap = ['Recibido'=>'badge-recibido','En preparacion'=>'badge-preparacion','Listo'=>'badge-listo','Entregado'=>'badge-entregado','Pagado'=>'badge-pagado','Cancelado'=>'badge-cancelado'])
                @foreach ($pedidos as $p)
                    <tr data-estado="{{ $p->estado }}" data-fecha="{{ $p->creado_en->format('Y-m-d') }}">
                        <td><span class="numero-orden">#{{ $p->numero_orden }}</span></td>
                        <td style="color:var(--muted);font-size:12px">{{ $p->creado_en->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->tipo }}{{ $p->mesa?->numero ? ' · Mesa '.$p->mesa->numero : '' }}</td>
                        <td style="color:var(--orange);font-weight:700">${{ number_format($p->total, 0, ',', '.') }}</td>
                        <td><span class="badge {{ $badgeMap[$p->estado] ?? '' }}">{{ $p->estado }}</span></td>
                        <td><a href="{{ route('cliente.detalle', $p) }}" class="btn-ver">Ver detalle</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function filtrarPedidos() {
    const estado = document.getElementById('filtroEstado').value;
    const fecha  = document.getElementById('filtroFecha').value;
    const filas  = document.querySelectorAll('#tablaPedidos tbody tr');
    let visibles = 0;
    filas.forEach(fila => {
        const matchEstado = estado === 'todos' || fila.dataset.estado === estado;
        const matchFecha  = !fecha || fila.dataset.fecha === fecha;
        const mostrar = matchEstado && matchFecha;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });
    let msg = document.getElementById('msgSinPedidos');
    if (!msg) {
        msg = document.createElement('tr');
        msg.id = 'msgSinPedidos';
        msg.innerHTML = '<td colspan="6" style="text-align:center;padding:40px;color:#8a8078">No hay pedidos con ese filtro</td>';
        document.querySelector('#tablaPedidos tbody').appendChild(msg);
    }
    msg.style.display = visibles === 0 ? '' : 'none';
}
function limpiarFiltros() {
    document.getElementById('filtroEstado').value = 'todos';
    document.getElementById('filtroFecha').value  = '';
    filtrarPedidos();
}
</script>
@endpush
