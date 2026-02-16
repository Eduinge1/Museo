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
    Schema::create('respuestas_seguridad', function (Blueprint $table) {
        $table->id();
        
        // Relación con Usuario y Pregunta
        $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
        $table->foreignId('id_pregunta')->constrained('preguntas_seguridad')->onDelete('cascade')->unique();
        
        $table->string('respuesta');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuestas_seguridad');
    }
};
