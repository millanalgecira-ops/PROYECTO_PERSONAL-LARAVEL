<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->char('numero_orden', 8)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->nullOnDelete();
            $table->string('direccion_entrega')->nullable();
            $table->enum('tipo', ['En mesa', 'Para llevar']);
            $table->enum('estado', [
                'Recibido', 'En preparacion', 'Listo', 'Entregado', 'Pagado', 'Cancelado',
            ])->default('Recibido');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->foreignId('cancelado_por')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->index(['estado', 'creado_en'], 'idx_pedido_estado_fecha');
            $table->index('cliente_id', 'idx_pedido_cliente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
