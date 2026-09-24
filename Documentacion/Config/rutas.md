# 🛣️ Documentación de Configuración: Enrutamiento y Middleware (`routes/web.php`)

Esta sección describe la tabla de rutas, nombres de rutas, verbos HTTP, controladores asociados y capas de seguridad (*middleware*) implementadas en **La Parrilla**.

---

## 1. Arquitectura de Seguridad y Middleware

El sistema cuenta con un esquema de doble guard (*web* para staff y *cliente* para público):

1. **`auth:web`**: Protege rutas administrativas y de cocina.
2. **`auth:cliente`**: Protege rutas del panel del comprador (`/mi-cuenta`).
3. **`role:{rol}` (`App\Http\Middleware\EnsureRoleMatches`)**:
   - `role:administrador`: Restringe el acceso exclusivamente a usuarios con rol Administrador.
   - `role:cocina`: Restringe el acceso a personal de cocina.
4. **`redirectGuestsTo`**: Redirecciona a usuarios no autenticados hacia la ruta nombrada `login`.

---

## 2. Mapa Completo de Rutas del Sistema

### 🌐 A. Rutas Públicas (Catálogo y Compra)

| Método | URI | Nombre de Ruta | Controlador y Acción | Descripción |
|:---|:---|:---|:---|:---|
| `GET` | `/` | `home` | `Publico\HomeController@index` | Catálogo visual de productos con filtros por categoría. |
| `GET` | `/carrito` | `carrito` | `Publico\CarritoPageController@index` | Vista interactiva del carrito y checkout. |
| `POST` | `/carrito/procesar` | `carrito.procesar` | `Publico\CarritoController@procesar` | Procesa la orden transaccionalmente (`DB::transaction`). |
| `GET` | `/confirmacion` | `confirmacion` | `Publico\ConfirmacionController@show` | Pantalla de confirmación con resumen de la orden. |

---

### 🔑 B. Rutas de Autenticación

| Método | URI | Nombre de Ruta | Controlador y Acción | Descripción |
|:---|:---|:---|:---|:---|
| `GET` | `/login` | `login` | `Auth\AuthController@show` | Formulario unificado de acceso (Staff y Clientes). |
| `POST` | `/login` | `login.attempt` | `Auth\AuthController@login` | Valida credenciales contra tabla `usuarios` y `clientes`. |
| `POST` | `/logout` | `logout` | `Auth\AuthController@logout` | Cierra la sesión activa en cualquier guard. |
| `GET` | `/registro` | `registro` | `Auth\RegistroController@create` | Formulario de registro para nuevos clientes. |
| `POST` | `/registro` | `registro.store` | `Auth\RegistroController@store` | Registra e inicia sesión automáticamente al cliente. |
| `GET` | `/recuperar-password` | `password.form` | `Auth\PasswordRecoveryController@show` | Formulario para solicitar cambio de contraseña. |
| `POST` | `/recuperar-password` | `password.enviar` | `Auth\PasswordRecoveryController@enviarEnlace` | Genera token temporal de recuperación. |
| `POST` | `/recuperar-password/restablecer` | `password.restablecer` | `Auth\PasswordRecoveryController@restablecer` | Aplica la nueva contraseña en base de datos. |

---

### 👤 C. Panel de Cliente (`prefix: mi-cuenta`, `middleware: auth:cliente`)

| Método | URI | Nombre de Ruta | Controlador y Acción | Descripción |
|:---|:---|:---|:---|:---|
| `GET` | `/mi-cuenta` | `cliente.inicio` | `Cliente\DashboardController@inicio` | Inicio del panel de cliente. |
| `GET` | `/mi-cuenta/pedidos` | `cliente.pedidos` | `Cliente\DashboardController@pedidos` | Historial de pedidos realizados por el cliente. |
| `GET` | `/mi-cuenta/pedidos/{pedido}` | `cliente.detalle` | `Cliente\DashboardController@detalle` | Detalle y estado en tiempo real de un pedido específico. |

---

### 🛡️ D. Panel de Administrador (`prefix: admin`, `middleware: [auth:web, role:administrador]`)

| Método | URI | Nombre de Ruta | Controlador y Acción | Descripción |
|:---|:---|:---|:---|:---|
| `GET` | `/admin` | `admin.dashboard` | `Admin\DashboardController@index` | Dashboard principal con métricas e ingresos. |
| `POST` | `/admin/usuarios` | `admin.usuarios.store` | `Admin\UsuarioController@store` | Creación de personal (Admin o Cocina). |
| `PUT` | `/admin/usuarios/{usuario}` | `admin.usuarios.update` | `Admin\UsuarioController@update` | Edición de datos y contraseña de personal. |
| `PATCH` | `/admin/usuarios/{usuario}/estado` | `admin.usuarios.toggleEstado` | `Admin\UsuarioController@toggleEstado` | Activar o suspender acceso a usuarios de staff. |
| `GET` | `/admin/productos` | `admin.productos.index` | `Admin\ProductoController@index` | Catálogo de gestión de productos. |
| `POST` | `/admin/productos` | `admin.productos.store` | `Admin\ProductoController@store` | Agregar nuevo producto. |
| `PUT` | `/admin/productos/{producto}` | `admin.productos.update` | `Admin\ProductoController@update` | Modificar datos o precio de producto. |
| `DELETE` | `/admin/productos/{producto}` | `admin.productos.destroy` | `Admin\ProductoController@destroy` | Eliminar producto del menú. |
| `PATCH` | `/admin/productos/{producto}/disponibilidad` | `admin.productos.toggleDisponible` | `Admin\ProductoController@toggleDisponible` | Conmutar disponibilidad de producto. |
| `GET` | `/admin/pedidos` | `admin.pedidos.index` | `Admin\PedidoController@index` | Lista general de pedidos con filtros por estado. |
| `POST` | `/admin/pedidos/{pedido}/cancelar` | `admin.pedidos.cancelar` | `Admin\PedidoController@cancelar` | Cancelar orden y liberar mesa asociada. |
| `POST` | `/admin/pedidos/{pedido}/estado` | `admin.pedidos.cambiarEstado` | `Staff\PedidoEstadoController@cambiarEstado` | Avanzar flujo de preparación y entrega. |
| `GET` | `/admin/mesas` | `admin.mesas.index` | `Admin\MesaController@index` | Mapa y administración de mesas del asadero. |
| `POST` | `/admin/mesas/{mesa}/liberar` | `admin.mesas.liberar` | `Admin\MesaController@liberar` | Liberar una mesa ocupada. |
| `POST` | `/admin/mesas/liberar-todas` | `admin.mesas.liberarTodas` | `Admin\MesaController@liberarTodas` | Liberar todas las mesas simultáneamente. |
| `GET` | `/admin/ventas` | `admin.ventas.index` | `Admin\VentaController@index` | Reporte y desglose financiero de ingresos. |

---

### 🍳 E. Panel de Cocina (`prefix: cocina`, `middleware: [auth:web, role:cocina]`)

| Método | URI | Nombre de Ruta | Controlador y Acción | Descripción |
|:---|:---|:---|:---|:---|
| `GET` | `/cocina` | `cocina.comandas` | `Cocina\ComandaController@comandas` | Comandas activas en cocina por orden de llegada. |
| `GET` | `/cocina/productos` | `cocina.productos` | `Cocina\ComandaController@productos` | Lista rápida para agotar / reactivar productos. |
| `POST` | `/cocina/productos/{producto}/agotar` | `cocina.productos.agotar` | `Cocina\ComandaController@agotar` | Notificar producto agotado en tiempo real. |
| `POST` | `/cocina/productos/{producto}/activar` | `cocina.productos.activar` | `Cocina\ComandaController@activar` | Marcar producto nuevamente disponible. |
| `POST` | `/cocina/pedidos/{pedido}/estado` | `cocina.pedidos.cambiarEstado` | `Staff\PedidoEstadoController@cambiarEstado` | Cambiar estado a `En preparación` o `Listo`. |
