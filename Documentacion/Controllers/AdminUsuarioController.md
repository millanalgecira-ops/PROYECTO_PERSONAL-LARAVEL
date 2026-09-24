# 👥 Documentación del Controlador: `AdminUsuarioController`

**Namespace:** `App\Http\Controllers\Admin\UsuarioController`  
**Rutas Asociadas:** `POST /admin/usuarios`, `PUT /admin/usuarios/{usuario}`, `PATCH /admin/usuarios/{usuario}/estado`  
**Middleware:** `['auth:web', 'role:administrador']`

---

## 1. Propósito y Alcance

Permite al **Administrador del Asadero** gestionar el personal interno del establecimiento (staff operativo), que incluye cuentas de Administradores y Cocina. Las cuentas de tipo cliente no se administran aquí, ya que cuentan con su propia tabla (`clientes`) y registro autónomo.

---

## 2. Métodos y Funcionalidades

### `store(Request $request): RedirectResponse`
Registra un nuevo miembro del staff en la base de datos con contraseña cifrada mediante `Hash::make()`.

- **Validaciones aplicadas:**
  - `nombres`: Obligatorio, texto, máx. 100 caracteres.
  - `apellidos`: Obligatorio, texto, máx. 100 caracteres.
  - `email`: Obligatorio, formato de correo válido, único en tabla `usuarios`.
  - `password`: Obligatorio, mín. 6 caracteres.
  - `rol`: Obligatorio, únicamente valores permitidos: `'administrador'` o `'cocina'`.
  - `telefono`: Opcional, máx. 20 caracteres.

---

### `update(Request $request, Usuario $usuario): RedirectResponse`
Actualiza la información básica, rol asignado o contraseña de un usuario existente.

- **Comportamiento:**
  - Actualiza nombre completo concatenado (`nombres` + `apellidos`), correo y rol.
  - Si el campo `password` es provisto (no vacío), regenera el hash seguro en base de datos.
  - Mantiene intacta la clave actual si el campo `password` se deja en blanco.

---

### `toggleEstado(Usuario $usuario): RedirectResponse`
Invierte el estado del usuario (`activo = true/false`).

- **Efecto en el sistema:**
  - Si un usuario es desactivado, el controlador `AuthController` impedirá inmediatamente cualquier inicio de sesión posterior con ese usuario.

---

## 3. Matriz de Códigos de Rol

| Rol | Identificador en BD | Acceso Permitido |
|:---|:---|:---|
| `administrador` | `1` | Acceso total al panel `/admin` (productos, pedidos, staff, mesas, ventas). |
| `cocina` | `2` | Acceso restringido al panel `/cocina` (comandas y control de agotados). |
