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
    Schema::create('empleados_administradores', function (Blueprint $table) {
        $table->id(); 
        
        // Relación 1:1 (Un empleado solo tiene una ficha de admin) -> LLEVA UNIQUE
        $table->foreignId('id_empleado')->constrained('empleados')->onDelete('cascade')->unique();
        
        $table->boolean('is_active');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados_administradores');
    }
};
