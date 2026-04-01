<?php

namespace App\Docs\Api\Backend\Selected;

class ParkingSpaceConfiguration
{
    /**
     * @OA\Get(
     *      path="/selected/parking-space-configuration",
     *      tags={"Selected 選項"},
     *      summary="取得車位配置下拉選項",
     *      description="取得車位配置下拉選項",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                   @OA\Property(
     *                       property="total",
     *                       type="integer",
     *                       example="3",
     *                       description="總車位數",
     *                   ),
     *                    @OA\Property(
     *                        property="car",
     *                        type="integer",
     *                        example="2",
     *                        description="汽車位",
     *                    ),
     *                     @OA\Property(
     *                        property="motorcycle",
     *                        type="integer",
     *                        example="1",
     *                        description="機車位",
     *                    ),
     *                     @OA\Property(
     *                        property="allocated_parking",
     *                        type="integer",
     *                        example="2",
     *                        description="已分配車位數",
     *                    ),
     *                     @OA\Property(
     *                        property="allocated_parking_car",
     *                        type="integer",
     *                        example="1",
     *                        description="已分配汽車位",
     *                    ),
     *                    @OA\Property(
     *                         property="allocated_parking_motorcycle",
     *                         type="integer",
     *                         example="1",
     *                         description="已分配機車位",
     *                  ),
     *                  @OA\Property(
     *                      property="building_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="甲棟")
     *                  ),
     *                  @OA\Property(
     *                      property="district_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="B")
     *                  ),
     *                  @OA\Property(
     *                      property="staircase_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="梯1")
     *                  ),
     *                  @OA\Property(
     *                      property="floor_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="11F")
     *                  ),
     *                  @OA\Property(
     *                      property="household_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="11B")
     *                  ),
     *                  @OA\Property(
     *                      property="parking_attribute",
     *                      type="array",
     *                      @OA\Items(type="string", example="保留中")
     *                  ),
     *                  @OA\Property(
     *                      property="parking_type",
     *                      type="array",
     *                      @OA\Items(type="string", example="升降機械式")
     *                  ),
     *                  @OA\Property(
     *                      property="use_direction",
     *                      type="array",
     *                      @OA\Items(type="string", example="共用")
     *                  )
     *              )
     *          )
     *      ),
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
}
