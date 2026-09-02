<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenRecuperacion extends Model
{
    protected $table = 'tokens_recuperacion';

    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'token',
        'expira_en',
        'usado',
    ];

    protected function casts(): array
    {
        return [
            'usado' => 'boolean',
            'expira_en' => 'datetime',
            'creado_en' => 'datetime',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function vigente(): bool
    {
        return ! $this->usado && $this->expira_en->isFuture();
    }
}
