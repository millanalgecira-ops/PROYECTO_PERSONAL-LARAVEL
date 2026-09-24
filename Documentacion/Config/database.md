# 🗄️ Documentación de Configuración: Base de Datos (`database.php` / `.env`)

Esta sección documenta la arquitectura de persistencia, conexiones compatibles, variables de entorno y estrategia de migraciones del sistema **La Parrilla** en Laravel.

---

## 1. Conexión y Variables de Entorno

El archivo de configuración principal se ubica en `config/database.php` y consume las variables definidas en el archivo `.env`.

### Configuración por Defecto (MySQL / MariaDB en Laragon)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3320
DB_DATABASE=asadero_laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Alternativa para Pruebas Rápidas (SQLite)

```env
DB_CONNECTION=sqlite
# Utiliza database/database.sqlite por defecto
```

---

## 2. Diagrama de Relaciones de la Base de Datos

```
+------------------+          +-----------------------+
|      roles       |          |       usuarios        |
+------------------+          +-----------------------+
| id (PK)          | <------- | id (PK)               |
| nombre           |   1:N    | rol_id (FK -> roles)  |
| descripcion      |          | nombre, correo, etc.  |
+------------------+          +-----------------------+

+------------------+          +-----------------------+
|    categorias    |          |       productos       |
+------------------+          +-----------------------+
| id (PK)          | <------- | id (PK)               |
| nombre           |   1:N    | categoria_id (FK)     |
| activa, orden    |          | nombre, precio, etc.  |
+------------------+          +-----------------------+

+------------------+          +-----------------------+          +----------------------+
|     clientes     |          |        pedidos        | -------> |        mesas         |
+------------------+          +-----------------------+          +----------------------+
| id (PK)          | <------- | id (PK)               |  N:1     | id (PK)              |
| nombre, correo   |   1:N    | cliente_id (FK, null) |          | numero, capacidad    |
| telefono, etc.   |          | mesa_id (FK, null)    |          | estado               |
+------------------+          | estado, total, etc.   |          +----------------------+
                              +-----------------------+
                                   |              |
                    +--------------+              +--------------+
                    | 1:N                                        | 1:N
                    v                                            v
         +--------------------+                       +---------------------+
         |    pedido_items    |                       |  pedido_historial   |
         +--------------------+                       +---------------------+
         | id (PK)            |                       | id (PK)             |
         | pedido_id (FK)     |                       | pedido_id (FK)      |
         | producto_id (FK)   |                       | estado, cambiado_por|
         | cantidad, subtotal |                       +---------------------+
         +--------------------+
```

---

## 3. Tablas y Migraciones del Sistema

El sistema implementa **18 migraciones** que definen el ciclo completo del negocio:

| # | Archivo de Migración | Tabla / Vista | Propósito |
|:---|:---|:---|:---|
| **01** | `0001_01_01_000000_create_sessions_table.php` | `sessions` | Almacenamiento de sesiones de usuarios en base de datos. |
| **02** | `0001_01_01_000001_create_cache_table.php` | `cache`, `cache_locks` | Sistema de caché de alto rendimiento. |
| **03** | `0001_01_01_000002_create_jobs_table.php` | `jobs`, `failed_jobs` | Colas de tareas asíncronas. |
| **04** | `2024_01_01_000001_create_roles_table.php` | `roles` | Catálogo de roles internos (`Administrador`, `Cocina`). |
| **05** | `2024_01_01_000002_create_usuarios_table.php` | `usuarios` | Personal operativo del restaurante (*Guard* `web`). |
| **06** | `2024_01_01_000003_create_clientes_table.php` | `clientes` | Clientes registrados del sistema (*Guard* `cliente`). |
| **07** | `2024_01_01_000004_create_tokens_recuperacion_table.php` | `tokens_recuperacion` | Tokens temporales para restablecimiento de clave. |
| **08** | `2024_01_01_000005_create_categorias_table.php` | `categorias` | Clasificación de platos y bebidas. |
| **09** | `2024_01_01_000006_create_productos_table.php` | `productos` | Catálogo de platos, precios y disponibilidad. |
| **10** | `2024_01_01_000007_create_producto_agotamientos_table.php` | `producto_agotamientos` | Registro de productos marcados como agotados por cocina. |
| **11** | `2024_01_01_000008_create_mesas_table.php` | `mesas` | Control de estado de mesas (`Disponible`, `Ocupada`, `Reservada`). |
| **12** | `2024_01_01_000009_create_carrito_items_table.php` | `carrito_items` | Persistencia auxiliar de ítems de carrito. |
| **13** | `2024_01_01_000010_create_pedidos_table.php` | `pedidos` | Órdenes registradas con totales y estado general. |
| **14** | `2024_01_01_000011_create_pedido_items_table.php` | `pedido_items` | Detalle de productos y cantidades por orden. |
| **15** | `2024_01_01_000012_create_pedido_estados_historial_table.php` | `pedido_estados_historial` | Trazabilidad de cambios de estado del pedido. |
| **16** | `2024_01_01_000013_create_pagos_table.php` | `pagos` | Registro del método de pago y monto abonado. |
| **17** | `2024_01_01_000014_create_ingresos_table.php` | `ingresos` | Flujo de caja y contabilidad diaria. |
| **18** | `2024_01_01_000015_create_reporting_views.php` | Vistas SQL | Vistas para métricas de ventas y reportes administrativos. |

---

## 4. Comandos de Gestión Artisan

```bash
# Ejecutar todas las migraciones
php artisan migrate

# Revertir y volver a ejecutar con datos iniciales (Seeders)
php artisan migrate:fresh --seed

# Verificar estado de migraciones
php artisan migrate:status

# Listar staff y clientes registrados
php artisan app:check-users
```
