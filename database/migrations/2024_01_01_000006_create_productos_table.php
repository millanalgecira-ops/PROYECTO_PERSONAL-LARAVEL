<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate();
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->string('imagen_url')->nullable();
            $table->decimal('precio', 10, 2);
            $table->boolean('popular')->default(false);
            $table->boolean('disponible')->default(true);
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->index('categoria_id', 'idx_producto_categoria');
            $table->index('disponible', 'idx_producto_disponible');
            $table->index('popular', 'idx_producto_popular');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
