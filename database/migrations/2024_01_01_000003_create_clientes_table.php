<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('correo', 150)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('password_hash');
            $table->boolean('activo')->default(true);
            $table->boolean('correo_confirmado')->default(false);
            $table->dateTime('ultimo_acceso')->nullable();
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
