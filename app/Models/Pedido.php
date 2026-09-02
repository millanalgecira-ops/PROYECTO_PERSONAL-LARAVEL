<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    protected $table = 'pedidos';

    const CREATED_AT = 'creado_en';

    const UPDATED_AT = 'actualizado_en';

    /**
     * Estados posibles de un pedido, en el orden en que ocurren normalmente.
     */
    const ESTADOS = ['Recibido', 'En preparacion', 'Listo', 'Entregado', 'Pagado', 'Cancelado'];

    const ESTADOS_ACTIVOS = ['Recibido', 'En preparacion', 'Listo', 'Entregado'];

    const METODOS_PAGO = ['Efectivo', 'Tarjeta debito', 'Tarjeta credito', 'Billetera digital'];

    protected $fillable = [
        'numero_orden',
        'cliente_id',
        'mesa_id',
        'direccion_entrega',
        'tipo',
        'estado',
        'subtotal',
        'total',
        'observaciones',
        'cancelado_por',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }

    public function canceladoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cancelado_por');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function historialEstados(): HasMany
    {
        return $this->hasMany(PedidoEstadoHistorial::class);
    }

    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class);
    }

    public function ingresos(): HasMany
    {
        return $this->hasMany(Ingreso::class);
    }

    public function scopeActivos($query)
    {
        return $query->whereNotIn('estado', ['Pagado', 'Cancelado']);
    }
}
