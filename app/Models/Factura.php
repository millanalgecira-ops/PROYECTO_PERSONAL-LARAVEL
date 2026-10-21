<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'detalle', // JSON field with line items
    ];

    protected $casts = [
        'detalle' => 'array',
    ];
}
