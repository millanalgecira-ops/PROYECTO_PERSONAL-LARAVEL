# 🥩 Documentación del Controlador: `ProductoController`

**Namespace:** `App\Http\Controllers\Admin\ProductoController`  
**Rutas Asociadas:** `GET /admin/productos`, `POST /admin/productos`, `PUT /admin/productos/{producto}`, `DELETE /admin/productos/{producto}`, `PATCH /admin/productos/{producto}/disponibilidad`  
**Middleware:** `['auth:web', 'role:administrador']`

---

## 1. Propósito y Alcance

Permite el **CRUD completo** (Crear, Leer, Actualizar, Eliminar) del catálogo de platos, carnes a la parrilla, acompañamientos y bebidas del restaurante, además de controlar su disponibilidad y visibilidad como producto destacado.

---

## 2. Métodos del Controlador

### `index(): View`
Lista los productos ordenados por categoría y alfabéticamente, junto con la lista de categorías activas para los modales de creación y edición.

---

### `store(Request $request): RedirectResponse`
Inserta un nuevo producto en la base de datos tras validar las reglas de negocio:
- `nombre`: Obligatorio, texto, máx. 120 caracteres.
- `precio`: Obligatorio, numérico, mayor o igual a 0.
- `categoria_id`: Obligatorio, debe existir en la tabla `categorias`.
- `descripcion`: Opcional, texto descriptivo de los ingredientes o preparación.
- `imagen_url`: Opcional, URL válida de imagen.
- `popular`: Booleano (destacado en menú principal).
- `disponible`: Booleano (disponible para ordenar).

---

### `update(Request $request, Producto $producto): RedirectResponse`
Aplica las modificaciones enviadas desde el modal de edición de producto validando la integridad referencial de los datos.

---

### `destroy(Producto $producto): RedirectResponse`
Elimina el producto seleccionado de la base de datos.

---

### `toggleDisponible(Producto $producto): RedirectResponse`
Conmuta rápidamente el campo `disponible` entre `true` y `false`. Cuando un producto pasa a no disponible, el frontend público deshabilita el botón de agregar al carrito e informa al cliente que está agotado.
