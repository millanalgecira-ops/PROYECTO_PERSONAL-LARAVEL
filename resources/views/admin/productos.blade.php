@extends('layouts.staff', ['panel' => 'admin'])

@section('title', 'Gesti&oacute;n de Productos')
@section('topbar-title', 'Gesti&oacute;n de Productos')

@section('content')

    <div class="toolbar">
        <div class="toolbar-left">
            <button type="button" class="btn-orange" onclick="abrirModal('modalCrear')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                A&ntilde;adir Producto
            </button>
        </div>
        <input type="text" class="search-input" id="searchInput" placeholder="Buscar producto..." onkeyup="filtrarTabla('tablaProductos','searchInput')">
    </div>

    <div class="section-card">
        @if ($productos->isEmpty())
            <div class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <p>No hay productos registrados a&uacute;n</p>
            </div>
        @else
        <table id="tablaProductos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categor&iacute;a</th>
                    <th>Precio</th>
                    <th>Popular</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($productos as $p)
                <tr>
                    <td>
                        <strong>{{ $p->nombre }}</strong>
                        @if ($p->descripcion)
                            <div style="font-size:12px;color:var(--muted);margin-top:2px">{{ Str::limit($p->descripcion, 60) }}</div>
                        @endif
                    </td>
                    <td><span class="categoria-tag">{{ $p->categoria->nombre ?? '—' }}</span></td>
                    <td><span class="precio">${{ number_format($p->precio, 0, ',', '.') }}</span></td>
                    <td>
                        @if ($p->popular)
                            <span class="badge badge-popular">&#11088; Popular</span>
                        @else
                            <span style="color:var(--muted);font-size:12px">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $p->disponible ? 'badge-disponible' : 'badge-agotado' }}">
                            {{ $p->disponible ? 'Disponible' : 'Agotado' }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <button type="button" class="btn-edit" onclick='abrirModalEditar(@json($p->id), @json($p->nombre), @json($p->descripcion ?? ""), @json((float) $p->precio), @json($p->categoria_id), @json($p->popular), @json($p->disponible), @json($p->imagen_url ?? ""))'>Editar</button>
                            <form method="POST" action="{{ route('admin.productos.toggleDisponible', $p) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-toggle">{{ $p->disponible ? 'Agotar' : 'Activar' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.productos.destroy', $p) }}"
                                  onsubmit="return confirmarFormulario(event, this, '🗑️', '¿Eliminar producto?', 'Esta acción no se puede deshacer. El producto será eliminado permanentemente.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endsection

@section('modals')
    {{-- MODAL CREAR --}}
    <div class="modal-overlay" id="modalCrear">
        <div class="modal">
            <h3>Nuevo producto</h3>
            <form method="POST" action="{{ route('admin.productos.store') }}">
                @csrf
                <div class="field">
                    <label>Nombre del producto *</label>
                    <input type="text" name="nombre" placeholder="Ej: Pollo asado entero" required>
                </div>
                <div class="field">
                    <label>Descripci&oacute;n</label>
                    <textarea name="descripcion" placeholder="Descripción del producto..."></textarea>
                </div>
                <div class="field">
                    <label>URL de imagen <span style="color:var(--muted)">(opcional)</span></label>
                    <input type="url" name="imagen_url" placeholder="https://...">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Precio *</label>
                        <input type="number" name="precio" placeholder="42000" min="0" step="100" required>
                    </div>
                    <div class="field">
                        <label>Categor&iacute;a *</label>
                        <select name="categoria_id" required>
                            <option value="">Seleccionar...</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field" style="display:flex;gap:24px">
                    <label class="field-check"><input type="checkbox" name="popular" value="1"> Popular</label>
                    <label class="field-check"><input type="checkbox" name="disponible" value="1" checked> Disponible</label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="cerrarModales()">Cancelar</button>
                    <button type="submit" class="btn-orange">Crear producto</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDITAR --}}
    <div class="modal-overlay" id="modalEditar">
        <div class="modal">
            <h3>Editar producto</h3>
            <form method="POST" id="formEditarProducto" action="{{ route('admin.productos.store') }}">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Nombre del producto *</label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                <div class="field">
                    <label>Descripci&oacute;n</label>
                    <textarea name="descripcion" id="edit_descripcion"></textarea>
                </div>
                <div class="field">
                    <label>URL de imagen <span style="color:var(--muted)">(opcional)</span></label>
                    <input type="url" name="imagen_url" id="edit_imagen_url" placeholder="https://...">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Precio *</label>
                        <input type="number" name="precio" id="edit_precio" min="0" step="100" required>
                    </div>
                    <div class="field">
                        <label>Categor&iacute;a *</label>
                        <select name="categoria_id" id="edit_categoria" required>
                            <option value="">Seleccionar...</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field" style="display:flex;gap:24px">
                    <label class="field-check"><input type="checkbox" name="popular" id="edit_popular" value="1"> Popular</label>
                    <label class="field-check"><input type="checkbox" name="disponible" id="edit_disponible" value="1"> Disponible</label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="cerrarModales()">Cancelar</button>
                    <button type="submit" class="btn-orange">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function abrirModalEditar(id, nombre, descripcion, precio, categoria_id, popular, disponible, imagen_url) {
    document.getElementById('formEditarProducto').action = '{{ url('admin/productos') }}/' + id;
    document.getElementById('edit_nombre').value       = nombre;
    document.getElementById('edit_descripcion').value  = descripcion;
    document.getElementById('edit_precio').value       = precio;
    document.getElementById('edit_categoria').value    = categoria_id;
    document.getElementById('edit_popular').checked    = !!popular;
    document.getElementById('edit_disponible').checked = !!disponible;
    document.getElementById('edit_imagen_url').value   = imagen_url || '';
    abrirModal('modalEditar');
}
</script>
@endpush
