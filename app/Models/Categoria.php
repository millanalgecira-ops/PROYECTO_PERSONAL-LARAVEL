<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categorias';

    const CREATED_AT = 'creado_en';

    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen_url',
        'activa',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('activa', true)->orderBy('orden')->orderBy('nombre');
    }
}
