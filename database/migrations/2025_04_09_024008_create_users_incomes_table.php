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
        Schema::create('users_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_documento');
            $table->string('nombres');
            $table->string('apellidos');
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['M', 'F'])->nullable();
            $table->string('rh')->nullable();
            $table->string('telefono')->nullable();
            $table->string('nombre_contacto_emergencia')->nullable();
            $table->string('telefono_contacto_emergencia')->nullable();
            $table->string('direccion')->nullable();
            $table->string('area')->nullable();
            $table->string('foto_webcam')->nullable();  // Para guardar la ruta de la foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_incomes');
    }
};
