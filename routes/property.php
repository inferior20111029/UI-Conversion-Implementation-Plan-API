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

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\Backend', 'middleware' => ['backend.auth', 'only.community'], 'as' => 'property'], function () {
    // 空間資料
    Route::group(['namespace' => 'Space'], function () {
        // 空間規則
        Route::resource('rule', 'RuleController');

        // 空間配置
        Route::resource('configuration', 'ConfigurationController');
        Route::post('configuration/batch-delete', 'ConfigurationController@destroyAll');

        Route::resource('configuration-common', 'ConfigurationCommonController');
        Route::resource('configuration-common-space', 'ConfigurationCommonSpaceController');

        // 車位配置
        Route::prefix('parking-space-configuration')->group(function () {
            Route::resource('', 'ParkingSpaceConfigurationController')
                ->parameters([
                    '' => 'uuid',
                ]);

            Route::post('cancel/{uuid}', 'ParkingSpaceConfigurationController@cancel'); //取消配置
            Route::post('configuration/{uuid}', 'ParkingSpaceConfigurationController@configuration'); //配置車位
        });

        Route::get('parking-space/{space_id}', 'ParkingSpaceController@show');
        Route::post('parking-space/{space_id}', 'ParkingSpaceController@store'); // 新增
        Route::delete('parking-space/{uuid}', 'ParkingSpaceController@destroy'); // 取消車位配置

        // 車位參數管理
        Route::resource('parking-space-select', 'ParkingSpaceSelectController')
            ->parameters([
                '' => 'uuid',
            ])
            ->except(['create', 'show', 'edit']);

        //格局設定
        Route::prefix('space-layout-setting')->group(function () {
            Route::resource('', 'LayoutSettingController')
                ->parameters([
                    '' => 'uuid',
                ])
                ->except(['create', 'show']);

            Route::post('batch-destroy', 'LayoutSettingController@batchDestroy'); //批次刪除
        });

        // 批次設定
        Route::group(['prefix' => 'batch-setting', 'namespace' => 'BatchSetting'], function () {
            Route::post('layout', 'LayoutSettingController@store'); // 格局
            Route::post('transfer-house', 'TransferHouseController@store'); // 列入資產
            Route::post('transfer-owner-title', 'TransferOwnerTitleController@store'); // 批次過戶
            Route::post('transfer-warranty-date', 'TransferWarrantyDateController@store'); // 批次設定交屋日期
        });

        // 保固選單
        Route::resource('warranty', 'WarrantySelectController')
            ->except(['create', 'show', 'edit']);

        // 公有
        Route::group(['prefix' => 'public', 'namespace' => 'Public'], function () {
            // 基本資訊
            Route::resource('base-info', 'BaseInfoController')
                ->parameters([
                    '' => 'id',
                ])
                ->only(['index','edit', 'show', 'store', 'update']);
        });
    });

    // 產權配置-戶別列表
    Route::group(['prefix' => 'property/rights', 'namespace' => 'PropertyRights', 'middleware' => 'only.community'], function () {
        Route::resource('', 'PropertyRightsController')
            ->parameters([
                '' => 'spaceId'
            ])
            ->only(['index', 'show', 'update'])
            ->whereUuid(['spaceId']);

        // 面積總覽
        Route::get('area', 'PropertyRightsController@area');
    });

    // 元件庫
    Route::group(['namespace' => 'Equipment'], function () {
        Route::prefix('equipment')->group(function () {
            // 元件列表
            Route::resource('', 'EquipmentController')
                ->parameters([
                    '' => 'id',
                ]);

            // 元件批次設定
            Route::patch('batch/update', 'EquipmentBatchController@update');
            Route::delete('batch/delete', 'EquipmentBatchController@destroy');

            // 戶別下的元件資料
            Route::get('space/{spaceId}', 'SpaceController@index')->whereUuid('uuid');
            Route::post('space/{spaceId}', 'SpaceController@store');
            Route::post('multi-space', 'SpaceController@multiSpaceStore');
            Route::delete('space/{id}', 'SpaceController@destroy');
            Route::post('space-scrap', 'SpaceController@scrap'); // 報廢元件

            // 類別管理
            Route::resource('equipment/category', 'CategoryController')
                ->parameters([
                    '' => 'id',
                ])
                ->except(['create', 'show', 'edit']);

            Route::get('equipment/category-merge', 'CategoryController@mergeInfo');
            Route::patch('equipment/category-merge', 'CategoryController@merge');

            // 群組管理
            Route::resource('equipment/group', 'GroupController')
                ->parameters([
                    '' => 'id',
                ]);

            // 構件
            Route::resource('equipment/component', 'ComponentController')
                ->parameters([
                    '' => 'id',
                ])->only(['index', 'store', 'update']);
        });

        // 提報修繕
        Route::group(['prefix' => 'repair-request'], function () {
            Route::resource('', 'RepairRequestController')
                ->parameters([
                    '' => 'id',
                ]);
        });
    });

    // 租金售價
    Route::group(['namespace' => 'RentalSaleAmounts'], function () {
        Route::prefix('rental-sale-amounts')->group(function () {
            // 單價設定
            Route::resource('unit-price', 'UnitPriceController')
                ->parameters([
                    '' => 'id',
                ])
                ->only(['index', 'store']);

            // 售價平均差
            Route::resource('average-price', 'AveragePriceVarianceController')
                ->parameters([
                    '' => 'id',
                ])
                ->only(['index', 'store']);

            Route::get('average-price-calculate','AveragePriceVarianceController@calculate');
        });
    });

    // Excel 匯入匯出
    Route::group(['prefix' => 'excel', 'namespace' => 'Excel'], function () {
        // 空間規則
        Route::get('rule/{identity}', 'RuleController@export');
        Route::post('rule', 'RuleController@import');

        // 空間配置
        Route::get('configuration/{identity}', 'ConfigurationController@export');
        Route::post('configuration/{identity}', 'ConfigurationController@import');

        // 空間配置-公設
        Route::get('configuration-common/{identity}', 'ConfigurationCommonController@export');
        Route::post('configuration-common/{identity}', 'ConfigurationCommonController@import');

        // 元件設備
        Route::get('equipment/{identity}', 'EquipmentController@export');
        Route::post('equipment/{identity}', 'EquipmentController@import');
        Route::post('equipment-upload-zip', 'EquipmentController@uploadZip');

        // 車位配置
        Route::get('parking-space-configuration/{identity}', 'ParkingSpaceConfigurationController@export');
        Route::post('parking-space-configuration/{identity}', 'ParkingSpaceConfigurationController@import');

        // 關係人
        Route::get('related-party/{identity}', 'RelatedPartyController@export');
        Route::post('related-party/{identity}', 'RelatedPartyController@import');
    });

    // 戶別
    Route::group(['prefix' => 'household-type/{spaceId}', 'namespace' => 'HouseholdType'], function () {

        // 立約人資料
        Route::resource('', 'ContractingPartyController')
            ->parameters([
                '' => 'id',
            ]);

        // 身份證字號取得立約人資料
        Route::get('identity/number', 'ContractingPartyController@identityNumber');
    })->whereUuid('spaceId');

    Route::group(['prefix' => 'customer-management', 'namespace' => 'HouseholdType'], function () {
        // 客戶總覽
        Route::resource('', 'CustomerManagementController')
            ->parameters([
                '' => 'id',
            ])
            ->only('index');
    });

    // 認證標章
    Route::group(['prefix' => 'certification', 'namespace' => 'Certification'], function () {
        Route::resource('building-space', 'BuildingSpaceController')
            ->parameters([
                '' => 'id',
            ])
            ->only(['index', 'store', 'update', 'destroy']);

        Route::post('batch', 'BuildingSpaceController@batch');
    });

    // 能耗
    Route::group(['prefix' => 'energy', 'namespace' => 'Energy'], function () {
        // 統計紀錄
        Route::resource('{type}/space-statistics', 'SpaceStatisticsController')
            ->parameters([
                'id' => 'id',
            ])
            ->only(['index', 'show', 'store', 'update']);
    });

    // 隱藏欄位
    Route::group(['prefix' => 'hidden-column', 'namespace' => 'HiddenColumn'], function () {
        Route::get('', 'HiddenController@index');
        Route::post('', 'HiddenController@update');
    });
});
