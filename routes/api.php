<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\HealthRecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 公開路由
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 需認證路由
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 寵物 CRUD
    Route::apiResource('pets', PetController::class);

    // 健康紀錄 CRUD
    Route::apiResource('pets.records', HealthRecordController::class)->shallow();
});
