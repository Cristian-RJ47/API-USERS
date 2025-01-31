<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'/users'], function(){
    Route::get('/', [UserController::class, 'getAllUsers']); // Obtener usuarios paginados
    Route::post('/', [UserController::class, 'createUser']); // Crear usuario
    Route::get('/{id}', [UserController::class, 'getUserById']); // Obtener un usuario por ID
    Route::delete('/{id}', [UserController::class, 'deleteUser']); // Eliminar usuario
    Route::put('/{id}', [UserController::class, 'updateUser']); // Actualizar usuario
});

