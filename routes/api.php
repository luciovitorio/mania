<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClothController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'admin'])
    ->group(function () {
        Route::get('/auth', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::resource('/branch', BranchController::class);
        Route::resource('/address', AddressController::class);
        Route::resource('/user', UserController::class);
        Route::resource('/plan', PlanController::class);
        Route::resource('/cloth', ClothController::class);
        Route::resource('/client', ClientController::class);
        Route::resource('/rol', RolController::class);
        Route::post('/send', [RolController::class, 'sendWzp']);
        Route::get('/erol/{uuid}', [RolController::class, 'showForm']);
    });


Route::post('/login', [AuthController::class, 'login']);
