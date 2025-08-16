<?php
// routes\api.php
// Rutas API

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectoController;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Rutas protegidas con JWT
Route::middleware('jwt.verify')->group(function () {
    Route::get('/ping', fn () => response()->json(['ok' => true]));
    Route::get('/proyectos',  [ProyectoController::class, 'index']);
    Route::post('/proyectos', [ProyectoController::class, 'store']);
});

