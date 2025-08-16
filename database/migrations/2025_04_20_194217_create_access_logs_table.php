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
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('users_incomes'); // Relación con los visitantes
            $table->timestamp('entry_time'); // Hora de entrada
            $table->timestamp('exit_time')->nullable(); // Hora de salida
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. 
     */
    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};
