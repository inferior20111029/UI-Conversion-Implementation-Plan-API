<?php

namespace App\Docs\Api\Backend\Space;

class ParkingSpaceSelect
{
    /**
     * @OA\Get(
     *     path="/parking-space-select/",
     *     tags={"Parking-Space-Select 車位配置參數"},
     *     summary="車位配置參數列表",
     *     description="車位配置參數列表",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of parking configurations",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="取得成功"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="parking_attribute",
     *                     type="array",
     *                     description="車位屬性",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string", description=""),
     *                         @OA\Property(property="company_id", type="integer", description="company_id 為 0 不能刪 ，也不能編輯"),
     *                         @OA\Property(property="type", type="string"),
     *                         @OA\Property(property="value", type="string"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="created_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", nullable=true, format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="parking_type",
     *                     type="array",
     *                     description="車位類型",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="company_id", type="integer", description="company_id 為 0 不能刪 ，也不能編輯"),
     *                         @OA\Property(property="type", type="string"),
     *                         @OA\Property(property="value", type="string"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="created_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", nullable=true, format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="use_direction",
     *                     type="array",
     *                     description="使用方式",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="company_id", type="integer", description="company_id 為 0 不能刪 ，也不能編輯"),
     *                         @OA\Property(property="type", type="string"),
     *                         @OA\Property(property="value", type="string"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="created_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", nullable=true, format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="car_size",
     *                     type="array",
     *                     description="車位尺寸",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="company_id", type="integer", description="company_id 為 0 不能刪 ，也不能編輯"),
     *                         @OA\Property(property="type", type="string"),
     *                         @OA\Property(property="value", type="string"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="created_at", type="string", nullable=true, format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", nullable=true, format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *       @OA\Response(response=201, description="建立成功"),
     *       @OA\Response(response=301, description="網址跳轉"),
     *       @OA\Response(response=400, description="參數錯誤"),
     *       @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *       @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *       @OA\Response(response=404, description="資源不存在，查無資料"),
     *       @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *      path="/parking-space-select/",
     *      tags={"Parking-Space-Select 車位配置參數"},
     *      summary="新增車位配置參數",
     *      description="建立配置參數",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                   @OA\Property(property="type",
     *                                type="string",
     *                                description="參數類型 parking_attribute: 車位屬性, parking_type: 車位類型, use_direction: 使用方式, car_size: 車位尺寸",
     *                                enum={"parking_attribute", "parking_type", "use_direction", "car_size"}
     *                             ),
     *                    @OA\Property(property="value", type="string", description="坡道平面式"),
     *                )
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
    public function store()
    {
    }

    /**
     * @OA\Patch(
     *      path="/parking-space-select/{uuid}/",
     *      tags={"Parking-Space-Select 車位配置參數"},
     *      summary="更新車位配置參數",
     *      description="更新配置參數",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *       @OA\Parameter(
     *           name="uuid",
     *           in="path",
     *           required=true,
     *           @OA\Schema(
     *               type="integer"
     *           )
     *       ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                   @OA\Property(property="type",
     *                                type="string",
     *                                description="參數類型 parking_attribute: 車位屬性, parking_type: 車位類型, use_direction: 使用方式, car_size: 車位尺寸",
     *                                enum={"parking_attribute", "parking_type", "use_direction", "car_size"}
     *                             ),
     *                    @OA\Property(property="value", type="string", description="坡道平面式"),
     *                )
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
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/parking-space-select/{uuid}/",
     *      tags={"Parking-Space-Select 車位配置參數"},
     *      summary="刪除車位配置參數",
     *      description="刪除車位配置參數",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="uuid",
     *           in="path",
     *           required=true,
     *           description="",
     *           @OA\Schema(
     *               type="string",
     *           )
     *       ),
     *      @OA\Response(response=201, description="建立成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function destroy()
    {
    }
}
