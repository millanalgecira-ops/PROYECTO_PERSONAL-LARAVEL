<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_estados_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->enum('estado', [
                'Recibido', 'En preparacion', 'Listo', 'Entregado', 'Pagado', 'Cancelado',
            ]);
            $table->foreignId('cambiado_por')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->dateTime('cambiado_en')->useCurrent();

            $table->index(['pedido_id', 'cambiado_en'], 'idx_hist_pedido_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_estados_historial');
    }
};
