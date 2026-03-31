<?php

namespace App\Docs\Api\Backend\RentalSaleAmounts;

class UnitPrice
{
    /**
     * @OA\Get(
     *     path="/rental-sale-amounts/unit-price",
     *     summary="租售金額_單價設定資料",
     *     description="租售金額_單價設定資料",
     *     tags={"Unit-Price 單價設定"},
     *     security={
     *           {"Authorization": {}},
     *           {"Community-Id-Header": {}}
     *       },
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="community", type="string", example="奇妙社區"),
     *                 @OA\Property(
     *                     property="equipment_group",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=8),
     *                         @OA\Property(property="name", type="string", example="A型設備")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="layout_setting",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="一房一廳六衛浴")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="unit_price",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=4),
     *                         @OA\Property(property="company_id", type="integer", example=10),
     *                         @OA\Property(property="comid", type="integer", example=899),
     *                         @OA\Property(property="name", type="string", example="租屋使用"),
     *                         @OA\Property(property="crm_layout_setting_id", type="integer", example=1),
     *                         @OA\Property(property="crm_equipment_group_id", type="integer", example=9),
     *                         @OA\Property(property="default", type="integer", example=10000),
     *                         @OA\Property(property="suggest", type="integer", example=10001),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-08T02:31:49.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-08T02:31:49.000000Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
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
     *      path="/rental-sale-amounts/unit-price",
     *      tags={"Unit-Price 單價設定"},
     *      summary="編輯單價設定",
     *      description="Allows editing and creating new unit price settings for different equipment groups and layout configurations.",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="data[0][id]",
     *                      type="integer",
     *                      description="單價設定id",
     *                      example=1
     *                  ),
     *                 @OA\Property(
     *                       property="data[0][name]",
     *                       type="string",
     *                       description="單價名稱",
     *                       example="租屋使用"
     *                   ),
     *                  @OA\Property(
     *                      property="data[0][equipment_group]",
     *                      type="integer",
     *                      description="設備群組id",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="data[0][layout_setting]",
     *                      type="integer",
     *                      description="格局設id",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="data[0][default]",
     *                      type="integer",
     *                      description="預設單價",
     *                      example=1000
     *                  ),
     *                  @OA\Property(
     *                      property="data[0][suggest]",
     *                      type="integer",
     *                      description="建議售價",
     *                      example=1001
     *                  ),
     *                  @OA\Property(
     *                      property="del[0]",
     *                      type="integer",
     *                      description="刪除單價設定",
     *                      example=1
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=201, description="Successfully created or updated unit price settings."),
     *      @OA\Response(response=301, description="Redirect to a different URL."),
     *      @OA\Response(response=400, description="Bad request due to incorrect parameters."),
     *      @OA\Response(response=401, description="Unauthorized due to invalid token or unrecognized data."),
     *      @OA\Response(response=403, description="Forbidden access using a banned token or insufficient permissions."),
     *      @OA\Response(response=404, description="Resource not found."),
     *      @OA\Response(response=500, description="Internal server error.")
     * )
     */
    public function store()
    {
    }

}
