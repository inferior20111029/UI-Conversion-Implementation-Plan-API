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

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\Backend', 'middleware' => 'backend.auth', 'as' => 'leasehold'], function () {
    // 房仲列表
    Route::group(['prefix' => 'real-estate-agent', 'namespace' => 'RealEstateAgent'], function () {
        Route::group(['prefix' => 'authorize'], function () {
            Route::resource('', 'AuthorizeController')
                ->parameters([
                    '' => 'uuid'
                ])
                ->except(['create', 'edit', 'update'])
                ->whereUuid(['uuid']);

            // 批次刪除
            Route::post('multiple/delete', 'AuthorizeController@multipleDelete');
        });

        // 房仲委託
        Route::group(['prefix' => 'entrust', 'middleware' => 'only.community'], function () {
            Route::resource('{spaceId}', 'EntrustController')->only(['index', 'store'])->whereUuid('spaceId');
        });
    });

    // 承租人合約
    Route::group(['prefix' => 'renter', 'namespace' => 'RenterContract', 'middleware' => 'only.community'], function () {
        // 戶別合約
        Route::group(['prefix' => 'space/{spaceId}/contract', 'namespace' => 'Space'], function () {
            Route::resource('', 'ContractController')
                ->parameters([
                    '' => 'uuid'
                ])
                ->except(['create', 'edit'])
                ->whereUuid(['uuid']);

            // 帳單
            Route::resource('{contractUuid}/bill', 'BillController')
                ->parameters([
                    'bill' => 'uuid'
                ])
                ->only(['store', 'update', 'destroy'])
                ->whereUuid(['contractUuid', 'uuid']);

            // 停止租約 (合約)
            Route::patch('{uuid}/termination', 'TerminationController@update')->whereUuid('uuid');

            // 合約文檔上傳
            Route::patch('{uuid}/document', 'DocumentController@update')->whereUuid('uuid');

            // 租約簽名
            Route::patch('{uuid}/signature', 'SignatureController@update')->whereUuid('uuid');
        })->whereUuid('spaceId');

        // 驗收驗退紀錄
        Route::get('acceptance-rejection/{uuid}', 'AcceptanceRejectionController@show')->whereUuid('uuid');
        Route::post('acceptance-rejection/{uuid}', 'AcceptanceRejectionController@store')->whereUuid('uuid');
        Route::post('acceptance-rejection/{uuid}/document', 'AcceptanceRejectionController@document')->whereUuid('uuid');

        // 車位合約
        Route::group(['prefix' => 'car/parking/{carParkingId}/contract', 'namespace' => 'CarParking'], function () {
            Route::resource('', 'ContractController')
                ->parameters([
                    '' => 'uuid'
                ])
                ->except(['create', 'edit'])
                ->whereUuid(['uuid']);

            // 帳單
            Route::resource('{contractUuid}/bill', 'BillController')
                ->parameters([
                    'bill' => 'uuid'
                ])
                ->only(['store', 'update', 'destroy'])
                ->whereUuid(['contractUuid', 'uuid']);

            // 停止租約 (合約)
            Route::patch('{uuid}/termination', 'TerminationController@update')->whereUuid('uuid');

            // 合約文檔上傳
            Route::patch('{uuid}/document', 'DocumentController@update')->whereUuid('uuid');

            // 租約簽名
            Route::patch('{uuid}/signature', 'SignatureController@update')->whereUuid('uuid');
        })->whereUuid('carParkingId');
    });

    // 物件管理
    Route::group(['prefix' => 'property/manage', 'namespace' => 'PropertyManage', 'middleware' => 'only.community'], function () {
        // 戶別物件
        Route::group(['prefix' => 'space', 'namespace' => 'Space'], function () {
            Route::resource('/{spaceId}', 'PropertyController')
                ->parameters([
                    '{spaceId}' => 'uuid'
                ])
                ->only(['store'])
                ->whereUuid(['uuid']);

            Route::patch('{uuid}', 'PropertyController@update');
            Route::get('{id}/edit', 'PropertyController@edit');
            Route::get('{id}', 'PropertyController@show');
        });

        // 車位物件
        Route::group(['prefix' => 'car/parking', 'namespace' => 'CarParking'], function () {
            Route::resource('/{carParkingId}', 'PropertyController')
                ->parameters([
                    '{carParkingId}' => 'uuid'
                ])
                ->only(['store'])
                ->whereUuid(['uuid']);

            Route::patch('{uuid}', 'PropertyController@update');
            Route::get('{id}/edit', 'PropertyController@edit');
            Route::get('{id}', 'PropertyController@show');
        });

        Route::get('', 'PropertyCommonController@index');
        Route::get('list', 'PropertyCommonController@list');
        Route::get('register-area', 'PropertyCommonController@registerArea');
        Route::get('car/{type}', 'PropertyCommonController@carType');
    });

    // 預約看房紀錄
    Route::group(['prefix' => 'visit/reserve', 'namespace' => 'VisitReserve', 'middleware' => 'only.community'], function () {
        Route::resource('', 'VisitReserveController')
            ->parameters([
                '' => 'uuid'
            ])
            ->except(['create', 'edit'])
            ->whereUuid('uuid');

        // 簽到
        Route::patch('{uuid}/check-in', 'VisitReserveController@checkIn')->whereUuid('uuid');

        // 取消
        Route::patch('{uuid}/cancel', 'VisitReserveController@cancel')->whereUuid('uuid');
    });

    // 檔案
    Route::group(['prefix' => 'file', 'namespace' => 'File'], function () {
        // 檔案上傳
        Route::post('upload', 'UploadController@store');

        // 刪除檔案
        Route::delete('{uuid}', 'DestroyController@destroy')->whereUuid('uuid');

        // 刪除複數檔案
        Route::post('multiple/delete', 'DestroyController@multiple');
    });

    // Excel
    Route::group(['prefix' => 'excel', 'namespace' => 'Excel'], function () {
        // 產權面積
        Route::group(['prefix' => 'property/right/area', 'namespace' => 'PropertyRightArea'], function () {
            // 匯出
            Route::post('exports', 'ExportsController@store');

            // 匯入
            Route::post('imports', 'ImportsController@store');
        });
    });
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\Backend', 'as' => 'leasehold'], function () {
    // QRCODE 掃描登記
    Route::group(['prefix' => 'visit/check-in', 'namespace' => 'VisitReserve'], function () {
        Route::get('{uuid}', 'VisitCheckInController@index')->whereUuid('uuid');
        Route::patch('{uuid}', 'VisitCheckInController@checkIn')->whereUuid('uuid');
    });
});