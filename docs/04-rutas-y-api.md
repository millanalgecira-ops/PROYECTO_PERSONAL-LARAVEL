# 04 - Directorio de Rutas y Middleware

## 🌐 Catálogo de Rutas HTTP

| Método | URI | Nombre de Ruta | Controlador / Método | Middleware / Guard |
|---|---|---|---|---|
| `GET` | `/` | `home` | `Publico\HomeController@index` | Público |
| `GET` | `/login` | `login` | `Auth\AuthController@show` | Público |
| `POST` | `/login` | `login.attempt` | `Auth\AuthController@login` | Público |
| `POST` | `/logout` | `logout` | `Auth\AuthController@logout` | Autenticado |
| `GET` | `/registro` | `registro` | `Auth\RegistroController@create` | Público |
| `POST` | `/registro` | `registro.store` | `Auth\RegistroController@store` | Público |
| `GET` | `/recuperar-password` | `password.form` | `Auth\PasswordRecoveryController@show` | Público |
| `POST` | `/recuperar-password` | `password.enviar` | `Auth\PasswordRecoveryController@enviarEnlace` | Público |
| `POST` | `/recuperar-password/restablecer` | `password.restablecer` | `Auth\PasswordRecoveryController@restablecer` | Público |
| `GET` | `/carrito` | `carrito` | `Publico\CarritoPageController@index` | Público |
| `POST` | `/carrito/procesar` | `carrito.procesar` | `Publico\CarritoController@procesar` | Público |
| `GET` | `/confirmacion` | `confirmacion` | `Publico\ConfirmacionController@show` | Público |

---

## 👤 Panel de Cliente (`/mi-cuenta`)

| Método | URI | Nombre de Ruta | Controlador / Método | Middleware / Guard |
|---|---|---|---|---|
| `GET` | `/mi-cuenta` | `cliente.inicio` | `Cliente\DashboardController@inicio` | `auth:cliente` |
| `GET` | `/mi-cuenta/pedidos` | `cliente.pedidos` | `Cliente\DashboardController@pedidos` | `auth:cliente` |
| `GET` | `/mi-cuenta/pedidos/{pedido}` | `cliente.detalle` | `Cliente\DashboardController@detalle` | `auth:cliente` |

---

## 👨‍🍳 Panel de Cocina (`/cocina`)

| Método | URI | Nombre de Ruta | Controlador / Método | Middleware / Guard |
|---|---|---|---|---|
| `GET` | `/cocina` | `cocina.comandas` | `Cocina\ComandaController@comandas` | `auth:web`, `role:cocina` |
| `GET` | `/cocina/productos` | `cocina.productos` | `Cocina\ComandaController@productos` | `auth:web`, `role:cocina` |
| `POST` | `/cocina/productos/{producto}/agotar` | `cocina.productos.agotar` | `Cocina\ComandaController@agotar` | `auth:web`, `role:cocina` |
| `POST` | `/cocina/productos/{producto}/activar` | `cocina.productos.activar` | `Cocina\ComandaController@activar` | `auth:web`, `role:cocina` |
| `POST` | `/cocina/pedidos/{pedido}/estado` | `cocina.pedidos.cambiarEstado` | `Staff\PedidoEstadoController@cambiarEstado` | `auth:web`, `role:cocina` |

---

## 👑 Panel Administrador (`/admin`)

| Método | URI | Nombre de Ruta | Controlador / Método | Middleware / Guard |
|---|---|---|---|---|
| `GET` | `/admin` | `admin.dashboard` | `Admin\DashboardController@index` | `auth:web`, `role:administrador` |
| `POST` | `/admin/usuarios` | `admin.usuarios.store` | `Admin\UsuarioController@store` | `auth:web`, `role:administrador` |
| `PUT` | `/admin/usuarios/{usuario}` | `admin.usuarios.update` | `Admin\UsuarioController@update` | `auth:web`, `role:administrador` |
| `PATCH` | `/admin/usuarios/{usuario}/estado` | `admin.usuarios.toggleEstado` | `Admin\UsuarioController@toggleEstado` | `auth:web`, `role:administrador` |
| `GET` | `/admin/productos` | `admin.productos.index` | `Admin\ProductoController@index` | `auth:web`, `role:administrador` |
| `POST` | `/admin/productos` | `admin.productos.store` | `Admin\ProductoController@store` | `auth:web`, `role:administrador` |
| `PUT` | `/admin/productos/{producto}` | `admin.productos.update` | `Admin\ProductoController@update` | `auth:web`, `role:administrador` |
| `DELETE` | `/admin/productos/{producto}` | `admin.productos.destroy` | `Admin\ProductoController@destroy` | `auth:web`, `role:administrador` |
| `PATCH` | `/admin/productos/{producto}/disponibilidad` | `admin.productos.toggleDisponible` | `Admin\ProductoController@toggleDisponible` | `auth:web`, `role:administrador` |
| `GET` | `/admin/pedidos` | `admin.pedidos.index` | `Admin\PedidoController@index` | `auth:web`, `role:administrador` |
| `POST` | `/admin/pedidos/{pedido}/cancelar` | `admin.pedidos.cancelar` | `Admin\PedidoController@cancelar` | `auth:web`, `role:administrador` |
| `POST` | `/admin/pedidos/{pedido}/estado` | `admin.pedidos.cambiarEstado` | `Staff\PedidoEstadoController@cambiarEstado` | `auth:web`, `role:administrador` |
| `GET` | `/admin/mesas` | `admin.mesas.index` | `Admin\MesaController@index` | `auth:web`, `role:administrador` |
| `POST` | `/admin/mesas/liberar-todas` | `admin.mesas.liberarTodas` | `Admin\MesaController@liberarTodas` | `auth:web`, `role:administrador` |
| `POST` | `/admin/mesas/{mesa}/liberar` | `admin.mesas.liberar` | `Admin\MesaController@liberar` | `auth:web`, `role:administrador` |
| `GET` | `/admin/ventas` | `admin.ventas.index` | `Admin\VentaController@index` | `auth:web`, `role:administrador` |

---

## 🛡️ Middlewares Personalizados

### `App\Http\Middleware\RoleMiddleware`
Verifica que el usuario autenticado bajo el guard `web` tenga asignado el rol correspondiente (`administrador` o `cocina`) mediante la relación `usuario->rol->nombre`. Si no cuenta con el rol, aborta la petición con respuesta HTTP `403 Prohibido`.
