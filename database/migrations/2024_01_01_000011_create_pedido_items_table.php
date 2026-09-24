<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnUpdate();
            $table->string('nombre_producto', 120);
            $table->unsignedSmallInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->text('nota_especial')->nullable();

            $table->index('pedido_id', 'idx_item_pedido');
            $table->index('producto_id', 'idx_item_producto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};
