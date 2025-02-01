<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\StatisticsController;
use App\Http\Middleware\AdminMiddleware; // Ensure this class exists in the specified namespace
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'users'], function () {
    Route::post('/login', [UserController::class, 'login']); // Iniciar sesión
    Route::post('/', [UserController::class, 'createUser']); // Crear usuario

    // Rutas protegidas con middleware de autenticación
    Route::middleware([AuthMiddleware::class])->group(function () {
        Route::get('/', [UserController::class, 'getAllUsers']); // Obtener usuarios paginados
        Route::get('/{id}', [UserController::class, 'getUserById']); // Obtener usuario por ID
    });

    // Rutas protegidas con autenticación y permisos de administrador
    Route::middleware([AuthMiddleware::class, AdminMiddleware::class])->group(function () {
        Route::delete('/{id}', [UserController::class, 'deleteUser']); // Eliminar usuario
        Route::put('/{id}', [UserController::class, 'updateUser']); // Actualizar usuario
    });
});

// Grupo de rutas para estadísticas, accesibles solo por administradores
Route::group(['prefix' => 'statistics'], function () {
    Route::get('/', [StatisticsController::class, 'getStatistics'])->middleware(AdminMiddleware::class);
});
