<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->string('imagen_url')->nullable();
            $table->boolean('activa')->default(true);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->index(['activa', 'orden', 'nombre'], 'idx_categoria_activa_orden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
