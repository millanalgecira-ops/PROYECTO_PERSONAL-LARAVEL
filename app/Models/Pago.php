<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pagos';

    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'metodo',
        'monto_recibido',
        'cambio',
        'total_pagado',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'monto_recibido' => 'decimal:2',
            'cambio' => 'decimal:2',
            'total_pagado' => 'decimal:2',
            'pagado_en' => 'datetime',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por');
    }
}
