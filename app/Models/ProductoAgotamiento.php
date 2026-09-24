<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoAgotamiento extends Model
{
    protected $table = 'producto_agotamientos';

    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'reportado_por',
        'motivo',
    ];

    protected function casts(): array
    {
        return [
            'reportado_en' => 'datetime',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function reportadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'reportado_por');
    }
}
