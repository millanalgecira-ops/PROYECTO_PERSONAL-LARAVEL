<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoEstadoHistorial extends Model
{
    protected $table = 'pedido_estados_historial';

    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'estado',
        'cambiado_por',
    ];

    protected function casts(): array
    {
        return [
            'cambiado_en' => 'datetime',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cambiadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cambiado_por');
    }
}
