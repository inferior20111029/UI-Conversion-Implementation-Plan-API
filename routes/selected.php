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

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\Backend', 'middleware' => 'backend.auth', 'as' => 'selected'], function () {
    // 選項資料
    Route::group(['prefix' => 'selected', 'namespace' => 'Selected'], function () {
        // 社區
        Route::get('community', 'CommunityController@index');

        // 國家
        Route::get('country', 'CountryController@index')->withoutMiddleware('backend.auth');

        // 租金包含項目
        Route::get('rent/included/item', 'RentIncludedItemController@index');

        // 房屋用途
        Route::get('house/application', 'HouseApplicationController@index');

        // 時間週期
        Route::get('time/cycle', 'TimeCycleController@index');

        // 合約-人員類型
        Route::get('contract/person/type', 'ContractPersonTypeController@index');

        // 合約-提醒類型
        Route::get('contract/notify/type', 'ContractNotifyTypeController@index');

        // 合約-停車位類型
        Route::get('contract/parking/type', 'ContractParkingTypeController@index');

        // 裝潢類型
        Route::get('decoration/type', 'DecorationTypeController@index');

        // 裝潢時間
        Route::get('decoration/time', 'DecorationTimeController@index');

        // 房屋規劃類型
        Route::get('house/planning/type', 'HousePlanningTypeController@index');

        // 房屋規劃
        Route::get('house/planning', 'HousePlanningController@index');

        // 屋況
        Route::get('house/state', 'HouseStateController@index');

        // 元件列表
        Route::get('equipment/list', 'EquipmentListController@index');

        // 空間列表
        Route::get('configuration/{type}', 'ConfigurationController@index');

        // 車位列表
        Route::get('parking-space-configuration', 'ParkingSpaceConfigurationController@index');

        // 銀行列表
        Route::get('bank', 'BankController@index')->withoutMiddleware('backend.auth');

        Route::group(['middleware' => 'only.community'], function () {
            // 戶別列表
            Route::get('space/private', 'SpacePrivateController@index');

            // 戶別分頁列表
            Route::get('space/pagination', 'SpacePaginationController@index');

            // 房屋物件
            Route::get('property', 'PropertyController@index');

            // 取得特定房屋的委託仲介
            Route::get('space/{spaceId}/entrust/real-estate-agent', 'SpaceEntrustRealEstateAgentController@index')
                ->whereUuid('spaceId');

            // 取得可分配產權的停車位
            Route::get('distribute/car/parking', 'DistributeCarParkingController@index');
        });
    });
});
