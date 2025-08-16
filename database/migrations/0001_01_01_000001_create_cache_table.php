<?php

// database\migrations\0001_01_01_000001_create_cache_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones
     */
    public function up(): void
    {
        // Crear la tabla de cache
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // Crear la tabla de bloqueos de cache
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    /**
     * Revertir las migraciones
     */
    public function down(): void
    {
        // Eliminar la tabla de bloqueos de cache
        Schema::dropIfExists('cache_locks');
    }
};
