<?php

// database\migrations\2025_08_14_025652_create_proyectos_table.php

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
    // Crear la tabla de proyectos
    Schema::create('proyectos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->date('fecha_inicio');
        $table->enum('estado', ['pendiente','en_progreso','finalizado']);
        $table->string('responsable');
        $table->decimal('monto', 12, 2);
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->timestamps();
    });
}

    /**
     * Revertir las migraciones
     */
    public function down(): void
    {
        // Eliminar la tabla de proyectos
        Schema::dropIfExists('proyectos');
    }
};
