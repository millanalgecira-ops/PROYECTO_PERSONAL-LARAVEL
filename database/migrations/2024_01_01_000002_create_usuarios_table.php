<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla "usuarios": personal interno (Administrador y Cocina).
     * Los clientes registrados publicamente viven en la tabla "clientes".
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('correo', 150)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('password_hash');
            $table->foreignId('rol_id')->constrained('roles')->cascadeOnUpdate();
            $table->boolean('activo')->default(true);
            $table->dateTime('ultimo_acceso')->nullable();
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
