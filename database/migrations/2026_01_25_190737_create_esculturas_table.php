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
    Schema::create('esculturas', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('id_obra')->constrained('obras')->onDelete('cascade')->unique();
        
        $table->string('nombre_material');
        $table->double('peso');
        $table->integer('dimensiones_alto');
        $table->integer('dimensiones_largo');
        $table->integer('dimensiones_ancho');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esculturas');
    }
};
