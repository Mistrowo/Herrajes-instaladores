<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sh_instalador', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 50)->unique()->comment('Usuario de acceso al sistema');
            $table->string('nombre', 100)->comment('Nombre completo del instalador');
            $table->string('telefono', 20)->nullable()->comment('Teléfono de contacto');
            $table->string('correo', 100)->unique()->comment('Correo electrónico');
            $table->string('rut', 12)->unique()->comment('RUT del instalador');
            $table->string('password')->comment('Contraseña encriptada');
            $table->enum('activo', ['S', 'N'])->default('S')->comment('Estado del instalador (S=Activo, N=Inactivo)');
            $table->enum('rol', ['admin', 'instalador', 'supervisor'])->default('instalador')->comment('Rol del usuario en el sistema');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('usuario');
            $table->index('correo');
            $table->index('rut');
            $table->index('activo');
            $table->index('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sh_instalador');
    }
};