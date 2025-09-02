<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectoController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('jwt.verify')->group(function () {
    Route::get   ('/proyectos',              [ProyectoController::class, 'index']);
    Route::post  ('/proyectos',              [ProyectoController::class, 'store']);
    Route::get   ('/proyectos/{id}',         [ProyectoController::class, 'show']);
    Route::match(['put','patch'], '/proyectos/{id}', [ProyectoController::class, 'update']);
    Route::delete('/proyectos/{id}',         [ProyectoController::class, 'destroy']);
});
