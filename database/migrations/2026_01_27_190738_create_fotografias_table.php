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
    Schema::create('fotografias', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('id_obra')->constrained('obras')->onDelete('cascade')->unique();
        
        $table->string('resolucion');
        $table->string('tipo_impresion');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotografias');
    }
};
