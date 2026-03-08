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
    Schema::create('obras', function (Blueprint $table) {
        $table->id(); 
        
        
        $table->foreignId('id_genero')->constrained('generos')->onDelete('cascade');
        $table->foreignId('id_artista')->constrained('artistas')->onDelete('cascade');
        
        
        $table->string('estado');
        $table->string('titulo');
        $table->double('precio_venta');
        $table->date('fecha_creacion');
        $table->string('image_url');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras');
    }
};
