<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos')->cascadeOnDelete();
            $table->enum('metodo', ['Efectivo', 'Tarjeta debito', 'Tarjeta credito', 'Billetera digital']);
            $table->decimal('monto_recibido', 10, 2)->nullable();
            $table->decimal('cambio', 10, 2)->nullable();
            $table->decimal('total_pagado', 10, 2);
            $table->foreignId('registrado_por')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->dateTime('pagado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
