@extends('layouts.staff', ['panel' => 'admin'])

@section('title', 'Panel Administrador')
@section('topbar-title', 'Panel de Administraci&oacute;n')

@section('content')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total usuarios</div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-sub">Registrados en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Activos</div>
            <div class="stat-value">{{ $activos }}</div>
            <div class="stat-sub">Con acceso habilitado</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Inactivos</div>
            <div class="stat-value">{{ $inactivos }}</div>
            <div class="stat-sub">Sin acceso al sistema</div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-head">
            <h2>Usuarios del sistema</h2>
            <button type="button" class="btn-orange" onclick="abrirModal('modalCrear')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo usuario
            </button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($usuarios as $u)
                @php($__nombres = explode(' ', $u->nombre, 2))
                @php($__badgeRol = $u->rolNombre() === 'administrador' ? 'admin' : $u->rolNombre())
                <tr>
                    <td>{{ $u->nombre }}</td>
                    <td style="color:var(--muted)">{{ $u->correo }}</td>
                    <td><span class="badge badge-{{ $__badgeRol }}">{{ ucfirst($u->rolNombre()) }}</span></td>
                    <td>
                        <span class="badge {{ $u->activo ? 'badge-activo' : 'badge-inactivo' }}">
                            {{ $u->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="display:flex;gap:8px">
                        <button type="button" class="btn-cancel" style="padding:6px 12px;font-size:12px"
                            onclick='abrirEditar(@json($u->id), @json($__nombres[0] ?? ""), @json($__nombres[1] ?? ""), @json($u->correo), @json($u->rolNombre()))'>
                            Editar
                        </button>
                        <form method="POST" action="{{ route('admin.usuarios.toggleEstado', $u) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-cancel" style="padding:6px 12px;font-size:12px">
                                {{ $u->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('modals')
    {{-- MODAL CREAR --}}
    <div class="modal-overlay" id="modalCrear">
        <div class="modal">
            <h3>Nuevo usuario</h3>
            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf
                <div class="field"><label>Nombres</label><input type="text" name="nombres" required></div>
                <div class="field"><label>Apellidos</label><input type="text" name="apellidos" required></div>
                <div class="field"><label>Correo</label><input type="email" name="email" required></div>
                <div class="field"><label>Tel&eacute;fono</label><input type="tel" name="telefono"></div>
                <div class="field"><label>Contrase&ntilde;a</label><input type="password" name="password" required></div>
                <div class="field">
                    <label>Rol</label>
                    <select name="rol">
                        <option value="administrador">Administrador</option>
                        <option value="cocina" selected>Cocina</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="cerrarModales()">Cancelar</button>
                    <button type="submit" class="btn-orange">Crear usuario</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDITAR --}}
    <div class="modal-overlay" id="modalEditar">
        <div class="modal">
            <h3>Editar usuario</h3>
            <form method="POST" id="formEditarUsuario" action="{{ route('admin.usuarios.store') }}">
                @csrf
                @method('PUT')
                <div class="field"><label>Nombres</label><input type="text" name="nombres" id="edit_nombres" required></div>
                <div class="field"><label>Apellidos</label><input type="text" name="apellidos" id="edit_apellidos" required></div>
                <div class="field"><label>Correo</label><input type="email" name="email" id="edit_email" required></div>
                <div class="field"><label>Nueva contrase&ntilde;a <span style="color:var(--muted)">(dejar vac&iacute;o para no cambiar)</span></label><input type="password" name="password"></div>
                <div class="field">
                    <label>Rol</label>
                    <select name="rol" id="edit_rol">
                        <option value="administrador">Administrador</option>
                        <option value="cocina">Cocina</option>
                    </select>
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
function abrirEditar(id, nombres, apellidos, email, rol) {
    document.getElementById('formEditarUsuario').action = '{{ url('admin/usuarios') }}/' + id;
    document.getElementById('edit_nombres').value   = nombres;
    document.getElementById('edit_apellidos').value = apellidos;
    document.getElementById('edit_email').value    = email;
    document.getElementById('edit_rol').value      = rol || 'cocina';
    abrirModal('modalEditar');
}
</script>
@endpush
