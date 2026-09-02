# La Parrilla – Sistema de Pedidos Autoservicio (Laravel)

Este proyecto es la migración a **Laravel** del sistema de pedidos "La Parrilla" (Asadero El Carbón). Conserva el diseño oscuro original, el flujo de negocio completo (catálogo público → carrito → checkout → cocina → caja) y las credenciales del staff, reescrito con Eloquent, Blade, rutas nombradas, middleware, migraciones y seeders.

---

## 🚀 Inicio Rápido

### En Windows (Script automático):
Simplemente ejecuta haciendo doble clic o desde la terminal:
```cmd
.\serve.bat
```

### O de forma manual:
```bash
# 1. Copiar archivo de entorno
cp .env.example .env

# 2. Instalar dependencias
composer install
npm install

# 3. Generar clave de aplicación y migrar/sembrar base de datos
php artisan key:generate
php artisan migrate --seed

# 4. Iniciar servidor
php artisan serve
```

Abre [http://127.0.0.1:8000](http://127.0.0.1:8000) en tu navegador.

---

## 🔑 Credenciales de Prueba

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | `millanalgecira@gmail.com` | `camilo123` |
| Administrador | `admin@asaderoelcarbon.test` | `password` |
| Cocina | `cocina@asaderoelcarbon.test` | `password` |

*Los clientes se registran libremente desde `/registro`.*

---

## 📐 Estructura del Sistema

- **Catálogo público** (`/`): Menú con filtro por categoría y carrito en `localStorage`.
- **Carrito y checkout** (`/carrito`): Arma el pedido y lo procesa transaccionalmente (`DB::transaction`).
- **Confirmación** (`/confirmacion?orden=...`): Resumen del pedido recien creado.
- **Mi cuenta** (`/mi-cuenta`, guard `cliente`): Historial de pedidos y detalle.
- **Panel Administrador** (`/admin`, guard `web` + rol `administrador`): Gestión de staff, productos, pedidos, mesas e ingresos.
- **Panel Cocina** (`/cocina`, guard `web` + rol `cocina`): Comandas en curso y disponibilidad de productos.

---

## 🛡️ Autenticación de Doble Guard

El sistema cuenta con dos guards nativos de Laravel:
- `web` → Modelo `Usuario` (Administrador / Cocina).
- `cliente` → Modelo `Cliente` (Público comprador).

El formulario en `/login` autentica según el rol del usuario en la base de datos.
