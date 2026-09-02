<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_agotamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('reportado_por')->constrained('usuarios')->cascadeOnDelete();
            $table->string('motivo')->nullable();
            $table->dateTime('reportado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_agotamientos');
    }
};
