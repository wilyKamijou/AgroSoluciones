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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('nombrePr', 30);
            $table->string('nombreTecnico', 30);
            $table->string('descripcionPr', 60);
            $table->string('compocicionQuimica', 60);
            $table->string('consentracionQuimica', 60);
            $table->date('fechaFabricacion');
            $table->date('fechaVencimiento');
            $table->string('unidadMedida', 30);
            $table->float('precioPr');

            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
