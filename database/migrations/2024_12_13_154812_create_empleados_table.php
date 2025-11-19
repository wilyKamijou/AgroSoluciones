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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('id_empleado');
            $table->string('nombreEm', 60);
            $table->string('apellidosEm', 60);
            $table->integer('sueldoEm');
            $table->integer('telefonoEm')->nullable();
            $table->string('direccion', 60)->nullable();

            $table->unsignedBigInteger('id_tipoE');
            $table->foreign('id_tipoE')->references('id_tipoE')->on('tipo_empleados');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
