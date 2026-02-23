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
    Schema::create('ceramicas', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('id_obra')->constrained('obras')->onDelete('cascade')->unique();
        
        $table->string('tipo_arcilla');
        $table->string('tecnica_coccion');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceramicas');
    }
};
