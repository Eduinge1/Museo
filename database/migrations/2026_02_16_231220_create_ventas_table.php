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
    Schema::create('ventas', function (Blueprint $table) {
        $table->id();
        
        // Relaciones (FKs)
        $table->foreignId('id_empleado')->constrained('empleados')->onDelete('cascade');
        $table->foreignId('id_obra')->constrained('obras')->onDelete('cascade')->unique(); // 1:1 con obra
        $table->foreignId('id_factura')->constrained('facturas')->onDelete('cascade')->unique(); // 1:1 con factura
        $table->foreignId('id_comprador')->constrained('compradores')->onDelete('cascade');
        $table->foreignId('id_direccion_envio')->constrained('direcciones_envio')->onDelete('cascade')->unique(); // 1:1 con dirección de envío
        
        // Datos
        $table->string('estado');
        $table->date('fecha_venta');
        $table->date('fecha_concretacion')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
