# 🔐 Documentación del Controlador: `AuthController`

**Namespace:** `App\Http\Controllers\Auth\AuthController`  
**Rutas Asociadas:** `GET /login`, `POST /login`, `POST /logout`  
**Middleware:** Público / Híbrido

---

## 1. Propósito y Alcance

Centraliza la autenticación del sistema mediante un **formulario de acceso unificado** que atiende de forma transparente tanto al personal interno de staff (`usuarios`, guard `web`) como a los clientes compradores (`clientes`, guard `cliente`).

---

## 2. Flujo de Autenticación de Doble Guard

```mermaid
flowchart TD
    A[Usuario ingresa correo y clave en /login] --> B{Validar campos y formato}
    B -- Inválido --> C[Retornar error con SweetAlert]
    B -- Válido --> D{Buscar en tabla usuarios}
    D -- Encontrado y Activo --> E{Verificar Hash Clave}
    E -- Correcto --> F[Auth::guard('web')->login]
    F --> G{Verificar Rol}
    G -- Administrador --> H[Redirigir a /admin]
    G -- Cocina --> I[Redirigir a /cocina]
    E -- Incorrecto --> C
    D -- No Encontrado --> J{Buscar en tabla clientes}
    J -- Encontrado y Activo --> K{Verificar Hash Clave}
    K -- Correcto --> L[Auth::guard('cliente')->login]
    L --> M[Redirigir a /mi-cuenta]
    K -- Incorrecto --> C
    J -- Inactivo --> N[Alerta: Cuenta inactiva]
    J -- No Encontrado --> C
```

---

## 3. Métodos del Controlador

### `show(): View|RedirectResponse`
Muestra el formulario `auth.login`. Si el usuario ya cuenta con una sesión activa, lo redirige automáticamente al panel correspondiente:
- Staff Administrador: `/admin`
- Staff Cocina: `/cocina`
- Cliente: `/mi-cuenta`

---

### `login(Request $request): RedirectResponse`
Procesa las credenciales enviadas:
1. Valida campos obligatorios y formato de correo electrónico.
2. Comprueba primero la tabla `usuarios` (staff).
3. Si no existe en `usuarios`, comprueba la tabla `clientes`.
4. Regenera el ID de sesión con `$request->session()->regenerate()` para prevenir ataques de *Session Fixation*.
5. Actualiza la marca de tiempo `ultimo_acceso` en la base de datos.

---

### `logout(Request $request): RedirectResponse`
Cierra cualquier sesión activa en ambos guards (`web` y `cliente`), invalida la sesión HTTP, regenera el token CSRF y redirige a la pantalla de login.
