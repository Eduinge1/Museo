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
    Schema::create('compradores', function (Blueprint $table) {
        $table->id(); 
        
        
        $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade')->unique();
        
        // Relación con Seguridad y Membresía (FKs normales)
        // Uso 'codigos_seguridad' (plural) porque así se llama la tabla que creamos
        $table->foreignId('id_codigo_seguridad')->constrained('codigos_seguridad')->onDelete('cascade')->unique();
        $table->foreignId('id_membresia')->constrained('membresias')->onDelete('cascade')->unique();
        
        $table->string('telefono')->unique(); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compradores');
    }
};
