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
        Schema::create('visitor_fingerprints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visitor_id');
            $table->text('fingerprint_template')->nullable()->comment('Plantilla de huella digital');
            $table->string('finger_type', 20)->nullable()->comment('Tipo de dedo');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('visitor_id')
                  ->references('id')
                  ->on('users_incomes')
                  ->onDelete('cascade');
            
            $table->index('visitor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors_fingerprint');
    }
};
