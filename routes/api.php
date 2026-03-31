<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\HealthRecordController;
use App\Http\Controllers\Api\AffiliateController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\DashboardController;

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
    // 使用者資訊 (由 frontend authStore / services.me 呼叫)
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });

    // Dashboard (聚合數據)
    Route::get('/dashboard/pets/{pet}', [DashboardController::class, 'petDashboard']);

    // 寵物 CRUD
    Route::apiResource('pets', PetController::class);

    // 健康紀錄 CRUD
    Route::apiResource('pets.records', HealthRecordController::class)->shallow();

    // 活動紀錄 CRUD
    Route::apiResource('pets.activities', ActivityController::class)->shallow();

    // 保險推薦 (依寵物風險分數匹配)
    Route::get('pets/{pet}/affiliates', [AffiliateController::class, 'recommend']);
    Route::post('affiliates/{affiliate}/click', [AffiliateController::class, 'logClick']);
});

// 公開的合作夥伴清單（不需登入）
Route::get('/affiliates', [AffiliateController::class, 'index']);
