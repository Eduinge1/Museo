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
    Schema::create('tarjetas_credito', function (Blueprint $table) {
        $table->id();
        // Relación 1:N (Un comprador puede tener varias tarjetas) 
        $table->foreignId('id_comprador')->constrained('compradores')->onDelete('cascade');
        
        $table->string('tipo_tarjeta'); // Visa, Mastercard...
        $table->string('nombre_asociado');
        $table->integer('mes_vencimiento');
        $table->integer('anio_vencimiento');
        $table->integer('cvv');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjetas_credito');
    }
};
