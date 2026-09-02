<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Clientes registrados publicamente desde el catalogo (rol "cliente").
 */
class Cliente extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'clientes';

    const CREATED_AT = 'creado_en';

    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'password_hash',
        'activo',
        'correo_confirmado',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'correo_confirmado' => 'boolean',
            'ultimo_acceso' => 'datetime',
            'creado_en' => 'datetime',
            'actualizado_en' => 'datetime',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function carritoItems(): HasMany
    {
        return $this->hasMany(CarritoItem::class);
    }
}
