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
    Schema::create('direcciones_envio', function (Blueprint $table) {
        $table->id();
        
        
        
        $table->string('pais');
        $table->string('estado_provincia');
        $table->string('ciudad');
        $table->string('parroquia');
        $table->string('calle');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones_envio');
    }
};
