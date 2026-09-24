<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Carrito persistente por cliente (la app usa localStorage en el
     * navegador para el carrito; esta tabla queda disponible para una
     * futura sincronizacion entre dispositivos).
     */
    public function up(): void
    {
        Schema::create('carrito_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->unsignedSmallInteger('cantidad')->default(1);
            $table->text('nota_especial')->nullable();
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['cliente_id', 'producto_id'], 'uq_carrito_cliente_producto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrito_items');
    }
};
