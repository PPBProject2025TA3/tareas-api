<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

Route::get('/tareas', [TareaController::class, 'index']);
Route::get('/tareas/{tarea}', [TareaController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tareas', [TareaController::class, 'store']);
    // Agregar más rutas (update, delete) luego...
});