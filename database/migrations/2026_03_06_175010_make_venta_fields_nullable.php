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
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('id_factura')->nullable()->change();
            $table->foreignId('id_direccion_envio')->nullable()->change();
            $table->foreignId('id_empleado')->nullable()->change(); // Al reservar no hay empleado aun
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('id_factura')->nullable(false)->change();
            $table->foreignId('id_direccion_envio')->nullable(false)->change();
            $table->foreignId('id_empleado')->nullable(false)->change();
        });
    }
};
