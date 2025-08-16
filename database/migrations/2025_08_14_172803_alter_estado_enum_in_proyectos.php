<?php

// database\migrations\2025_08_14_172803_alter_estado_enum_in_proyectos.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Ejecuta las migraciones
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `proyectos`
            MODIFY `estado` ENUM('pendiente','en_progreso','completado','pausado','cancelado') NOT NULL
        ");
    }

    /**
     * Revertir las migraciones
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE `proyectos`
            MODIFY `estado` ENUM('pendiente','en_progreso','completado','pausado') NOT NULL
        ");
    }
};
