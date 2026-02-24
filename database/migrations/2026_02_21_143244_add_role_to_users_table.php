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
  Schema::table('users', function (Blueprint $table) {
        // Agregamos la columna 'role'. Por defecto, será 'comprador'.
        // Los valores pueden ser 'comprador', 'empleado', 'admin'.
        $table->string('role')->default('comprador')->after('password');
  });
}
/**
 * Reverse the migrations.
 */
public function down(): void
{
  Schema::table('users', function (Blueprint $table) {
        // Si necesitamos revertir la migración, eliminamos la columna.
        $table->dropColumn('role');
  });
}
};

