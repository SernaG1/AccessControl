<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('numero_documento', 20)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['M', 'F'])->nullable();
            $table->string('rh', 5)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('nombre_contacto_emergencia', 100)->nullable();
            $table->string('telefono_contacto_emergencia', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('area', 100)->nullable();
            $table->boolean('estado')->default(true);
            $table->string('foto_webcam')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
