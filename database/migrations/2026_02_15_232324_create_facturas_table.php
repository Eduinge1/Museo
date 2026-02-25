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
    Schema::create('facturas', function (Blueprint $table) {
        $table->id();
        // FK hacia el administrador que emite la factura [cite: 204, 333]
        $table->foreignId('id_usuario_administrador')->constrained('empleados_administradores')->onDelete('cascade');
        
        
        $table->string('nombre_obra');
        $table->string('genero_obra');
        $table->double('precio_obra');
        $table->double('iva');
        $table->double('precio_venta');
        $table->double('porcentaje_ganancia');
        $table->date('fecha_facturacion');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
