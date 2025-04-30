<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas públicas
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // Perfil del usuario autenticado
    Route::get('/user', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/user', [ProfileController::class, 'update'])->name('api.profile.update');
    Route::post('/user/photo', [ProfileController::class, 'updatePhoto'])->name('api.profile.updatePhoto');
    Route::put('/user/password', [ProfileController::class, 'updatePassword'])->name('api.profile.updatePassword');

    // Gestión de todos los Usuarios (requiere permisos adicionales del admin)
    Route::apiResource('users', UserController::class)->names('api.users');
        // ->middleware('role:administrator');
});

// Ruta inexistente
Route::fallback(function(){
    return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');
