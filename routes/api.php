<?php

use App\Http\Controllers\TareaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiOauthValidation;

Route::get('/status', fn() => response()->json(['status' => 'OK']));

Route::get('/tareas', [TareaController::class, 'index']);
Route::get('/tareas/{id}', [TareaController::class, 'show']);

Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

Route::get('/comentarios', [ComentarioController::class, 'index']);
Route::get('/comentarios/{id}', [ComentarioController::class, 'show']);

Route::middleware(ApiOauthValidation::class)->group(function() {

    Route::post('/tareas', [TareaController::class, 'store']);
    Route::put('/tareas/{id}', [TareaController::class, 'update']);
    Route::delete('/tareas/{id}', [TareaController::class, 'destroy']);
    
    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);

    Route::post('/comentarios', [ComentarioController::class, 'store']);
    Route::put('/comentarios/{id}', [ComentarioController::class, 'update']);
    Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy']);
});