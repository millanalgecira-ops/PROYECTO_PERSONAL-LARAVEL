# 🛡️ Documentación de Controladores de Usuarios: `UsuarioControllers`

Esta sección abarca los controladores dedicados a la gestión, ciclo de vida y paneles de los diferentes perfiles de usuario del sistema: **Administradores**, **Cocineros** y **Clientes**.

---

## 1. Mapeo de Controladores de Usuario

| Perfil de Usuario | Guard Utilizado | Controlador Principal | Ruta del Panel |
|:---|:---|:---|:---|
| **Administrador** | `web` | `App\Http\Controllers\Admin\UsuarioController` | `/admin` |
| **Cocina** | `web` | `App\Http\Controllers\Cocina\ComandaController` | `/cocina` |
| **Cliente** | `cliente` | `App\Http\Controllers\Cliente\DashboardController` | `/mi-cuenta` |

---

## 2. Controlador de Clientes: `Cliente\DashboardController`

**Namespace:** `App\Http\Controllers\Cliente\DashboardController`  
**Middleware:** `auth:cliente`

### Métodos:

1. **`inicio(): View`**
   - Muestra la bienvenida personalizada al cliente comprador, resumen de su perfil y acceso directo al menú y sus últimos pedidos.
2. **`pedidos(): View`**
   - Lista cronológicamente todos los pedidos realizados por el cliente autenticado (`Auth::guard('cliente')->user()->pedidos()`).
3. **`detalle(Pedido $pedido): View`**
   - Muestra la comanda detallada del pedido: ítems comprados, cantidades, método de pago, mesa / dirección de despacho y la línea de tiempo de estados (`pedido_estados_historial`).
   - Aplica validación de propiedad: si el pedido no pertenece al cliente autenticado, devuelve un error `403 Forbidden`.

---

## 3. Matriz de Permisos por Rol y Guard

```
                                    +------------------------------------------+
                                    |              Sistema La Parrilla         |
                                    +------------------------------------------+
                                           /                           \
                                          /                             \
                                         v                               v
                        +-------------------------------+   +-----------------------------+
                        |     Guard 'web' (Staff)       |   |   Guard 'cliente' (Público) |
                        +-------------------------------+   +-----------------------------+
                        | Tabla: `usuarios`             |   | Tabla: `clientes`           |
                        +-------------------------------+   +-----------------------------+
                                /               \                          |
                               /                 \                         v
                              v                   v                 - Ver su historial
                      [Administrador]          [Cocina]             - Ver estados en vivo
                      - Gestionar Menú         - Ver Comandas       - Modificar perfil
                      - Gestionar Staff        - Marcar Agotados
                      - Gestionar Mesas        - Avanzar Estados
                      - Reportes Financieros
```
