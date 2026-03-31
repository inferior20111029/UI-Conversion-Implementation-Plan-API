<?php

namespace App\Docs\Api\Backend\RentalSaleAmounts;

class AveragePriceVariance
{
    /**
     * @OA\Get(
     *     path="/rental-sale-amounts/average-price",
     *     summary="租售金額_售價平均差資料",
     *     description="租售金額_售價平均差資料",
     *     tags={"Average-Price 售價平均差"},
     *     security={
     *           {"Authorization": {}},
     *           {"Community-Id-Header": {}}
     *        },
     *     @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1, description="編號"),
     *                  @OA\Property(property="total_floor", type="integer", example=15, description="總樓層"),
     *                  @OA\Property(property="middle_floor", type="integer", example=5, description="中位層"),
     *                  @OA\Property(property="median_amount", type="integer", example=10000, description="中位層金額"),
     *                  @OA\Property(property="downward_mean_deviation", type="integer", example=100, description="向下平均差"),
     *                  @OA\Property(property="upward_mean_deviation", type="integer", example=120, description="向上平均差"),
     *                  @OA\Property(
     *                      property="equipment_group",
     *                      type="array",
     *                      @OA\Items(type="string", example="1")
     *                  ),
     *                  @OA\Property(
     *                      property="floor_amount",
     *                      type="array",
     *                      description="個樓層金額",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="name", type="string", example="棟別2"),
     *                          @OA\Property(
     *                              property="floor",
     *                              type="array",
     *                              @OA\Items(
     *                                  type="object",
     *                                  @OA\Property(property="name", type="string", example="1樓"),
     *                                  @OA\Property(
     *                                      property="node",
     *                                      type="array",
     *                                      @OA\Items(
     *                                          type="object",
     *                                          @OA\Property(property="name", type="string", example="租屋使用"),
     *                                          @OA\Property(property="suggest", type="integer", example=10001),
     *                                          @OA\Property(property="default", type="integer", example=20480),
     *                                          @OA\Property(property="equipment_group_id", type="string", nullable=true, example=null),
     *                                          @OA\Property(property="equipment_group_name", type="string", example="A群組"),
     *                                          @OA\Property(property="layout_setting_name", type="string", example="一房一廳一衛浴")
     *                                      )
     *                                  )
     *                              )
     *                          )
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="equipment_group_option",
     *                      type="array",
     *                      description="設備群組選項",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="equipment_group_id", type="integer", example=1),
     *                          @OA\Property(property="equipment_group_name", type="string", example="A群組")
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=201, description="建立成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/rental-sale-amounts/average-price",
     *     summary="存檔售價平均差資料",
     *     description="存檔售價平均差資料",
     *     tags={"Average-Price 售價平均差"},
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"id", "middle_floor", "total_floor", "median_amount", "downward_mean_deviation", "upward_mean_deviation"},
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="售價平均差 ID, null to create new.",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="middle_floor",
     *                     type="integer",
     *                     description="中位層",
     *                     example=5
     *                 ),
     *                 @OA\Property(
     *                     property="total_floor",
     *                     type="integer",
     *                     description="總樓層",
     *                     example=15
     *                 ),
     *                 @OA\Property(
     *                     property="median_amount",
     *                     type="integer",
     *                     description="中位層金額",
     *                     example=10000
     *                 ),
     *                 @OA\Property(
     *                     property="downward_mean_deviation",
     *                     type="integer",
     *                     description="向下平均差",
     *                     example=100
     *                 ),
     *                 @OA\Property(
     *                     property="upward_mean_deviation",
     *                     type="integer",
     *                     description="向上平均差",
     *                     example=120
     *                 ),
     *                 @OA\Property(
     *                     property="equipment_group[0]",
     *                     type="integer",
     *                     description="設備群組ID",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][name]",
     *                     type="string",
     *                     description="棟別名稱",
     *                     example="棟別2"
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][building_value]",
     *                     type="string",
     *                     description="建築值",
     *                     example="building.3"
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][name]",
     *                     type="string",
     *                     description="樓層名稱",
     *                     example="1樓"
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][floor_value]",
     *                     type="string",
     *                     description="樓層值",
     *                     example="floor.1"
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][node][0][name]",
     *                     type="string",
     *                     description="單價名稱",
     *                     example="租屋使用"
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][node][0][suggest]",
     *                     type="integer",
     *                     description="建議價格",
     *                     example=10001
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][node][0][default]",
     *                     type="integer",
     *                     description="預設價格",
     *                     example=20480
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][node][0][equipment_group_id]",
     *                     type="integer",
     *                     description="設備群組ID",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][node][0][equipment_group_name]",
     *                     type="string",
     *                     description="設備群組名稱",
     *                     example="A群組"
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][node][0][layout_setting_name]",
     *                     type="string",
     *                     description="配置設置名稱",
     *                     example="一房一廳一衛浴"
     *                 ),
     *                 @OA\Property(
     *                     property="floor_amount[0][floor][0][node][0][layout_setting_id]",
     *                     type="integer",
     *                     description="配置設置ID",
     *                     example=1
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的Token"),
     *     @OA\Response(response=403, description="訪問被禁止"),
     *     @OA\Response(response=404, description="未找到資源"),
     *     @OA\Response(response=500, description="內部伺服器錯誤"),
     *  )
     */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *     path="/rental-sale-amounts/average-price-calculate",
     *     summary="計算售價平均差",
     *     description="計算售價平均差.",
     *     tags={"Average-Price 售價平均差"},
     *     security={
     *         {"Authorization": {}},
     *         {"Community-Id-Header": {}}
     *     },
     *     @OA\Parameter(
     *         name="middle_floor",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="total_floor",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Parameter(
     *         name="median_amount",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=10000)
     *     ),
     *     @OA\Parameter(
     *         name="downward_mean_deviation",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=100)
     *     ),
     *     @OA\Parameter(
     *         name="upward_mean_deviation",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=120)
     *     ),
     *     @OA\Parameter(
     *         name="equipment_group[0]",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功獲取計算結果",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="棟別2"),
     *                     @OA\Property(property="building_value", type="string", example="building.3"),
     *                     @OA\Property(
     *                         property="floor",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="name", type="string", example="1樓"),
     *                             @OA\Property(property="floor_value", type="string", example="floor.1"),
     *                             @OA\Property(
     *                                 property="node",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     @OA\Property(property="name", type="string", example="租屋使用"),
     *                                     @OA\Property(property="suggest", type="integer", example=10001),
     *                                     @OA\Property(property="default", type="integer", example=20480),
     *                                     @OA\Property(property="equipment_group_id", type="integer", example=1),
     *                                     @OA\Property(property="equipment_group_name", type="string", example="A群組"),
     *                                     @OA\Property(property="layout_setting_name", type="string", example="一房一廳一衛浴"),
     *                                     @OA\Property(property="layout_setting_id", type="integer", example=1),
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的Token"),
     *     @OA\Response(response=403, description="訪問被禁止"),
     *     @OA\Response(response=404, description="未找到資源"),
     *     @OA\Response(response=500, description="內部伺服器錯誤")
     * )
     */
    public function calculateAveragePrice()
    {
    }
}