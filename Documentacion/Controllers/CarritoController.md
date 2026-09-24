# 🛒 Documentación del Controlador: `CarritoController`

**Namespace:** `App\Http\Controllers\Publico\CarritoController`  
**Ruta Asociada:** `POST /carrito/procesar`  
**Middleware:** Público (acepta compras como cliente registrado o invitado)

---

## 1. Propósito y Alcance

Gestiona el procesamiento transaccional de compras realizadas desde el carrito de compras (cuyos ítems son administrados en el navegador mediante `localStorage`). Transforma la selección del cliente en registros atómicos en las tablas de negocio.

---

## 2. Transaccionalidad (`DB::transaction`)

Para garantizar la integridad total de los datos contables y operativos, todas las operaciones de guardado se ejecutan dentro de una transacción de base de datos. Si ocurre cualquier error o excepción, se realiza un *rollback* completo evitando registros huérfanos.

```
+--------------------------------------------------------------------------+
|                       DB::transaction Block                              |
|                                                                          |
|  1. Calcular subtotal y total de la orden                                |
|  2. Generar identificador único de orden (ej. 'F3A89C12')                |
|  3. Si es 'En mesa', actualizar estado de la mesa a 'Ocupada'            |
|  4. Insertar registro en tabla `pedidos`                                 |
|  5. Insertar cada ítem del carrito en `pedido_items`                     |
|  6. Registrar estado inicial 'Recibido' en `pedido_estados_historial`    |
|  7. Registrar comprobante en tabla `pagos`                               |
|  8. Registrar entrada contable en tabla `ingresos`                       |
+--------------------------------------------------------------------------+
```

---

## 3. Estructura de la Petición JSON

### Payload de Entrada
```json
{
  "cart": [
    {
      "nombre": "Punta de Anca 350g",
      "precio": 38000,
      "qty": 2
    },
    {
      "nombre": "Limonada Natural",
      "precio": 6000,
      "qty": 2
    }
  ],
  "tipo": "En mesa",
  "mesa_numero": 4,
  "nombre_cliente": "Carlos Mendoza",
  "metodo_pago": "Efectivo",
  "nota_especial": "Carne término tres cuartos, limonada sin azúcar"
}
```

### Respuesta Exitosa
```json
{
  "success": true,
  "numero_orden": "A9B3E421",
  "pedido_id": 15,
  "total": 88000
}
```

---

## 4. Métodos de Pago y Tipos Soportados

- **Tipos de Pedido:** `En mesa`, `Para llevar`.
- **Métodos de Pago:** `Efectivo`, `Tarjeta de Débito`, `Tarjeta de Crédito`, `Transferencia Bancaria`, `Nequi / Daviplata`.
