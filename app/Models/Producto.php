<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';

    const CREATED_AT = 'creado_en';

    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'imagen_url',
        'precio',
        'popular',
        'disponible',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'popular' => 'boolean',
            'disponible' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function pedidoItems(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function agotamientos(): HasMany
    {
        return $this->hasMany(ProductoAgotamiento::class);
    }
}
