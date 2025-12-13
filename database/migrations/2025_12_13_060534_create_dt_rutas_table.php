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
        Schema::create('dt_rutas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tipoE');
            $table->unsignedBigInteger('id_ruta');

            $table->primary(['id_tipoE', 'id_ruta']);
            $table->foreign('id_tipoE')->references('id_tipoE')->on('tipo_empleados');
            $table->foreign('id_ruta')->references('id_ruta')->on('rutas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_rutas');
    }
};
