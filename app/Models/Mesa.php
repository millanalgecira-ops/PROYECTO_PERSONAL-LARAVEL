<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesa extends Model
{
    protected $table = 'mesas';

    const CREATED_AT = 'creado_en';

    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'numero',
        'estado',
        'liberada_en',
    ];

    protected function casts(): array
    {
        return [
            'liberada_en' => 'datetime',
        ];
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function pedidosActivos(): HasMany
    {
        return $this->pedidos()->whereNotIn('estado', ['Pagado', 'Cancelado']);
    }

    public function liberar(): void
    {
        $this->update(['estado' => 'Disponible', 'liberada_en' => now()]);
    }
}
