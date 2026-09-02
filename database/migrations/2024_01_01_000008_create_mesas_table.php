<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('numero')->unique();
            $table->enum('estado', ['Disponible', 'Ocupada', 'Reservada', 'Inactiva'])->default('Disponible');
            $table->dateTime('liberada_en')->nullable();
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->index('estado', 'idx_mesa_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
