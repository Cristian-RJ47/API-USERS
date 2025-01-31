<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMilddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'/users'], function(){
    Route::get('/', [UserController::class, 'getAllUsers'])->middleware(AuthMilddleware::class); // Obtener usuarios paginados
    Route::post('/', [UserController::class, 'createUser']);// Crear usuario
    Route::get('/{id}', [UserController::class, 'getUserById'])->middleware(AuthMilddleware::class); // Obtener un usuario por ID
    Route::delete('/{id}', [UserController::class, 'deleteUser'])->middleware(AuthMilddleware::class); // Eliminar usuario
    Route::put('/{id}', [UserController::class, 'updateUser'])->middleware(AuthMilddleware::class); // Actualizar usuario
    Route::post('/login', [UserController::class, 'login']);//Login
});

//Route::group(['prefix'=>'/statistics'], function(){
    
//});