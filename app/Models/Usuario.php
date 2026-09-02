<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Personal interno: Administrador y Cocina.
 * Los clientes registrados publicamente viven en el modelo Cliente.
 */
class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    const CREATED_AT = 'creado_en';

    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'password_hash',
        'rol_id',
        'activo',
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
            'ultimo_acceso' => 'datetime',
            'creado_en' => 'datetime',
            'actualizado_en' => 'datetime',
        ];
    }

    /**
     * Laravel usa esto (en lugar de la columna "password") para verificar
     * la contraseña, ya que el proyecto original nombra la columna
     * "password_hash".
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class);
    }

    public function pedidosCancelados(): HasMany
    {
        return $this->hasMany(Pedido::class, 'cancelado_por');
    }

    public function productosAgotados(): HasMany
    {
        return $this->hasMany(ProductoAgotamiento::class, 'reportado_por');
    }

    public function esAdministrador(): bool
    {
        return $this->rolNombre() === 'administrador';
    }

    public function esCocina(): bool
    {
        return $this->rolNombre() === 'cocina';
    }

    public function rolNombre(): string
    {
        return strtolower($this->rol?->nombre ?? '');
    }
}
