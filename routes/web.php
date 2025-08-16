<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RutaTechSolutionsController;

// routes\web.php

// ---- Vistas simples de autenticación
Route::view('/login', 'auth.login')->name('login.view');
Route::view('/registro', 'auth.register')->name('register.view');

// ---- Home -> redirige a la lista
Route::get('/', fn () => redirect()->route('proyectos.lista'));

// ---- Recurso con NOMBRES EXACTOS que usan tus vistas
Route::resource('proyectos-tech-solutions', RutaTechSolutionsController::class)->names([
    'index'   => 'proyectos.lista',
    'create'  => 'proyectos.crear',
    'store'   => 'proyectos.guardar',
    'show'    => 'proyectos.mostrarDetalles',
    'edit'    => 'proyectos.editar',
    'update'  => 'proyectos.actualizar',
    'destroy' => 'proyectos.eliminar',
]);

// ---- Ruta extra para confirmar eliminación de un proyecto
Route::get(
    'proyectos-tech-solutions/{proyectos_tech_solution}/confirmar-eliminar',
    [RutaTechSolutionsController::class, 'confirmarEliminar']
)->name('proyectos.confirmarEliminar');

// ---- Alias de compatibilidad para enlaces que usan 'proyectos.listar'
// (evitamos conflicto de URI definiendo otra URL y apuntando al mismo método)
Route::get('proyectos-listar', [RutaTechSolutionsController::class, 'index'])
    ->name('proyectos.listar');
