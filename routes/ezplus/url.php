<?php

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

Route::domain(env('HOME_URL'))->group(function () {
    // 指定 domain 為 https
    Route::group(['scheme' => 'https'], function () {
        // 前台
        Route::group(['prefix' => 'estate_vue', 'as' => 'estateVue.'], function () {
            // 驗證房仲帳號
            Route::get('verificationPage')->name('verificationPage');

            // 修改房仲密碼
            Route::get('PasswordChange')->name('passwordChange');
        });

        // laravel-push
        Route::group(['prefix' => 'laravel-push', 'as' => 'laravelPush.'], function () {
            // Oauth 驗證
            Route::post('oauth/verify')->name('oauthVerify');
        });

        // laravel-notify
        Route::group(['prefix' => 'notify', 'as' => 'notify.'], function () {
            Route::group(['prefix' => 'api/notify'], function () {
                // 發送 Email
                Route::post('mail')->name('email');

                // 發送簡訊
                Route::post('sms')->name('sms');

                // 發送推播
                Route::post('push')->name('push');
            });
        });

        // laravel reverb
        Route::group(['prefix' => 'laravel-reverb/api/v1', 'as' => 'reverb.'], function () {
            Route::group(['prefix' => 'leasehold'], function () {
                Route::post('real-estate-agent/verify/account')->name('realEstateAgentVerify');
            });
        });
    });
});
