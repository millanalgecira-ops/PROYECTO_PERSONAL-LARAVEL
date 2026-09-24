# 📋 Documentación del Controlador: `PedidoController`

**Namespace:** `App\Http\Controllers\Admin\PedidoController`  
**Rutas Asociadas:** `GET /admin/pedidos`, `POST /admin/pedidos/{pedido}/cancelar`  
**Middleware:** `['auth:web', 'role:administrador']`

---

## 1. Propósito y Alcance

Permite a los **Administradores** supervisar el flujo completo de comandas y órdenes del restaurante, filtrar pedidos por su estado actual y realizar cancelaciones autorizadas liberando automáticamente los recursos vinculados.

---

## 2. Métodos del Controlador

### `index(Request $request): View`
Recupera el listado cronológico de pedidos cargando mediante *eager loading* las relaciones `cliente` y `mesa` para prevenir el problema de rendimiento N+1.

- **Filtros por Estado soportados:**
  - `todos`: Todos los pedidos registrados.
  - `Recibido`: Pedidos recién creados en espera de atención.
  - `En preparación`: Órdenes en proceso de cocción.
  - `Listo`: Órdenes preparadas listas para entrega.
  - `Entregado`: Pedidos servidos en mesa o despachados.
  - `Pagado`: Pedidos con cobro formalizado.
  - `Cancelado`: Pedidos anulados.

---

### `cancelar(Pedido $pedido): RedirectResponse`
Ejecuta la cancelación formal de un pedido por parte del administrador:

1. **Validación de Regla de Negocio:** No permite anular pedidos que ya han sido `Pagados` o que ya estaban `Cancelados`.
2. **Actualización de Estado:** Marca el pedido como `Cancelado` y guarda el ID del administrador en `cancelado_por`.
3. **Registro en Historial:** Inserta un nuevo registro en `pedido_estados_historial` con la trazabilidad del cambio.
4. **Liberación de Mesa:** Si el pedido tenía una mesa asignada (`$pedido->mesa`), ejecuta el método `$pedido->mesa->liberar()` pasando su estado a `Disponible`.
