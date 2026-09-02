<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->nullOnDelete();
            $table->string('descripcion');
            $table->enum('metodo', ['Efectivo', 'Tarjeta debito', 'Tarjeta credito', 'Billetera digital']);
            $table->decimal('monto', 10, 2);
            $table->foreignId('registrado_por')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->date('fecha');
            $table->dateTime('creado_en')->useCurrent();

            $table->index('fecha', 'idx_ingreso_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
