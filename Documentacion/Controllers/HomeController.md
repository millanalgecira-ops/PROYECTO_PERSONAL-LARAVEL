# 🏠 Documentación del Controlador: `HomeController`

**Namespace:** `App\Http\Controllers\Publico\HomeController`  
**Ruta Asociada:** `GET /`  
**Nombre de Ruta:** `home`  
**Middleware:** Público

---

## 1. Propósito y Alcance

Es el punto de entrada visual principal del restaurante para los clientes. Genera el catálogo dinámico de productos, la botonera de filtros interactivos por categoría y la visualización de platos populares y precios.

---

## 2. Métodos del Controlador

### `index(): View`
Consulta la base de datos mediante Eloquent aplicando optimizaciones de conteo y filtros de disponibilidad:

1. **Categorías Activas (`$categoriasMenu`):**
   - Filtra únicamente categorías con `activa = true`.
   - Ordena por `orden` y alfabéticamente por `nombre`.
   - Utiliza `withCount()` para calcular eficientemente en una sola consulta:
     - `total_productos`: Total de platos registrados en la categoría.
     - `disponibles`: Cantidad de productos en la categoría con `disponible = true`.

2. **Productos del Menú (`$todosProductos`):**
   - Recupera todos los platos cuyas categorías padre estén activas (`whereHas('categoria')`).
   - Prioriza en el orden de visualización los productos destacados (`orderByDesc('popular')`).
   - Carga imágenes personalizadas o asigna imágenes gastronómicas de respaldo (`IMAGENES_DEFECTO`) si no se definió URL.

---

## 3. Vista Asociada: `resources/views/publico/home.blade.php`

- Renderiza el encabezado hero con llamado a la acción.
- Pestañas de categorías con contadores numéricos en vivo.
- Tarjetas de producto con botón interactivo para agregar al carrito (`localStorage`).
- Modal interactivo de detalle de producto.
