<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sh_herraje_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('herraje_id')->index();
            $table->foreign('herraje_id')->references('id')->on('sh_herraje')->onDelete('cascade');

            // Ítem
            $table->string('codigo', 50)->nullable();
            $table->string('descripcion', 255);
            $table->string('unidad', 20)->default('UN'); // UN, MT, KIT, etc.

            $table->decimal('cantidad', 12, 2)->default(1);
            $table->decimal('precio', 12, 2)->nullable(); // si no manejas precio, puedes dejarlo null
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['codigo', 'descripcion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sh_herraje_items');
    }
};
