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
        Schema::create('sh_asigna', function (Blueprint $table) {
            $table->id();
            $table->string('nota_venta', 50)->comment('Número de nota de venta');
            $table->string('solicita', 50)->comment('Usuario que asignó la instalación');
            $table->foreignId('asignado1')->nullable()->constrained('sh_instalador')->onDelete('set null')->comment('Instalador asignado 1');
            $table->foreignId('asignado2')->nullable()->constrained('sh_instalador')->onDelete('set null')->comment('Instalador asignado 2');
            $table->foreignId('asignado3')->nullable()->constrained('sh_instalador')->onDelete('set null')->comment('Instalador asignado 3');
            $table->foreignId('asignado4')->nullable()->constrained('sh_instalador')->onDelete('set null')->comment('Instalador asignado 4');
            $table->date('fecha_asigna')->comment('Fecha en que se asignó la instalación');
            $table->date('fecha_acepta')->nullable()->comment('Fecha en que se aceptó la instalación');
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada', 'en_proceso', 'completada'])->default('pendiente')->comment('Estado de la asignación');
            $table->text('observaciones')->nullable()->comment('Observaciones de la asignación');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('nota_venta');
            $table->index('solicita');
            $table->index('fecha_asigna');
            $table->index('fecha_acepta');
            $table->index('estado');
            $table->index(['asignado1', 'fecha_asigna']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sh_asigna');
    }
};