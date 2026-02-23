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
    Schema::create('orfebrerias', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('id_obra')->constrained('obras')->onDelete('cascade')->unique();
        
        $table->string('metal_principal');
        $table->double('peso_gramos');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orfebrerias');
    }
};
