@extends('layouts.staff', ['panel' => 'admin'])

@section('title', 'Mesas')
@section('topbar-title', 'Gesti&oacute;n de Mesas')

@section('content')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total mesas</div>
            <div class="stat-value">{{ $mesas->count() }}</div>
            <div class="stat-sub">Registradas en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Disponibles</div>
            <div class="stat-value" style="color:#64dc82">{{ $disponibles }}</div>
            <div class="stat-sub">Listas para ocupar</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ocupadas</div>
            <div class="stat-value" style="color:#ff5050">{{ $ocupadas }}</div>
            <div class="stat-sub">Con clientes activos</div>
        </div>
    </div>

    <div class="toolbar">
        <form method="POST" action="{{ route('admin.mesas.liberarTodas') }}"
              onsubmit="return confirmarFormulario(event, this, '🪑', '¿Liberar todas las mesas?', 'Todas las mesas cambiarán a estado Disponible y se registrará la acción.')">
            @csrf
            <button type="submit" class="btn-orange">Liberar todas las mesas</button>
        </form>
    </div>

    <div class="mesas-grid">
        @foreach ($mesas as $m)
            @php($cls = strtolower($m->estado))
            <div class="mesa-card {{ $cls }}"
                 @if ($m->estado === 'Ocupada' && $m->pedidos_activos > 0) onclick="verPedidoMesa({{ $m->id }}, {{ $m->numero }})" style="cursor:pointer" @endif>
                <div class="mesa-numero">{{ $m->numero }}</div>
                <div class="mesa-estado">{{ $m->estado }}</div>
                @if ($m->pedidos_activos > 0)
                    <div class="pedidos-badge">{{ $m->pedidos_activos }} pedido(s) activo(s)</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px">Clic para ver detalle</div>
                @endif
                @if ($m->estado === 'Ocupada')
                    <br>
                    <form method="POST" action="{{ route('admin.mesas.liberar', $m) }}"
                          onsubmit="event.stopPropagation(); return confirmarFormulario(event, this, '🪑', '¿Liberar mesa {{ $m->numero }}?', 'La mesa cambiará a Disponible y se registrará la fecha y hora.')"
                          onclick="event.stopPropagation()">
                        @csrf
                        <button type="submit" class="btn-liberar" style="border:none;cursor:pointer">Liberar</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endsection

@section('modals')
    <div id="modalMesa" class="modal-mesa-overlay">
        <div class="modal-mesa-box">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <h3 style="font-family:'Bebas Neue',sans-serif;font-size:22px" id="modalMesaTitulo">Mesa</h3>
                <button type="button" onclick="cerrarModalMesa()" style="background:none;border:none;color:#8a8078;font-size:20px;cursor:pointer">&#10005;</button>
            </div>
            <div id="modalMesaContenido" style="font-size:14px;color:#8a8078">Cargando...</div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const pedidosMesa = @json($pedidosPorMesa);

function verPedidoMesa(mesaId, mesaNum) {
    const modal = document.getElementById('modalMesa');
    const titulo = document.getElementById('modalMesaTitulo');
    const contenido = document.getElementById('modalMesaContenido');
    titulo.textContent = 'Mesa ' + mesaNum;
    const p = pedidosMesa[mesaId];
    if (p) {
        contenido.innerHTML = `
            <div style="background:#1c1a18;border:1px solid #2e2b27;border-radius:10px;padding:16px;margin-bottom:12px">
                <div style="font-family:'Bebas Neue',sans-serif;font-size:20px;color:#f07000;margin-bottom:8px">#${p.numero_orden}</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px">
                    <div><div style="font-size:11px;color:#8a8078;text-transform:uppercase;letter-spacing:1px">Estado</div><div style="font-size:13px;font-weight:600">${p.estado}</div></div>
                    <div><div style="font-size:11px;color:#8a8078;text-transform:uppercase;letter-spacing:1px">Tipo</div><div style="font-size:13px;font-weight:600">${p.tipo}</div></div>
                    <div><div style="font-size:11px;color:#8a8078;text-transform:uppercase;letter-spacing:1px">Total</div><div style="font-size:13px;font-weight:700;color:#f07000">$${parseInt(p.total).toLocaleString('es-CO')}</div></div>
                    <div><div style="font-size:11px;color:#8a8078;text-transform:uppercase;letter-spacing:1px">Hora</div><div style="font-size:13px">${p.hora}</div></div>
                </div>
                <div style="font-size:11px;color:#8a8078;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">&Iacute;tems</div>
                <div style="font-size:13px;color:#f0ece6">${p.items || 'Sin ítems'}</div>
            </div>`;
    } else {
        contenido.innerHTML = '<p>No se encontró información del pedido.</p>';
    }
    modal.style.display = 'flex';
}

function cerrarModalMesa() {
    document.getElementById('modalMesa').style.display = 'none';
}
document.getElementById('modalMesa').addEventListener('click', function (e) {
    if (e.target === this) cerrarModalMesa();
});
</script>
@endpush
