<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupervisedUserController;
use App\Http\Controllers\Api\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para obtener la contraseña de un usuario supervisado
Route::middleware('auth:web')->get('/supervised-users/{supervisedUser}/password', [SupervisedUserController::class, 'getPassword']);

Route::middleware('auth:sanctum')->group(function () {
    // Rutas protegidas aquí
});

// Rutas públicas
Route::get('/events-by-category/{category?}', [EventController::class, 'getEventsByCategory']);
