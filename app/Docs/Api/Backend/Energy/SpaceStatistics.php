<?php

namespace App\Docs\Api\Backend\Energy;

class SpaceStatistics
{
    /**
     * @OA\Get(
     *      path="/energy/{type}/space-statistics",
     *      tags={"Energy Statistics 能耗統計"},
     *      summary="能耗統計列表",
     *      description="能耗統計列表",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *             name="type",
     *             in="path",
     *             required=true,
     *             @OA\Schema(
     *                 type="string",
     *                 description="參數類型 electric: 電能 water: 水能",
     *                 enum={"electric", "water"},
     *             )
     *      ),
     *      @OA\Parameter(
     *            name="space_id",
     *            in="query",
     *            required=true,
     *            @OA\Schema(type="string", default="37a40c44-1017-456f-83de-480b63c5bd01")
     *        ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="string", example="254e1ad5-125d-4d69-b09d-80ef48d11edc"),
     *                      @OA\Property(property="label", type="string", example="8568"),
     *                      @OA\Property(
     *                          property="children",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id", type="string", example="011e03dd-cb69-4d0a-880d-ede3d4647022"),
     *                              @OA\Property(property="label", type="string", example="88"),
     *                              @OA\Property(
     *                                  property="children",
     *                                  type="array",
     *                                  @OA\Items(type="object")
     *                              )
     *                          )
     *                      )
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

    /**
     * @OA\Get(
     *      path="/energy/{type}/space-statistics/{fee_number_id}",
     *      tags={"Energy Statistics 能耗統計"},
     *      summary="能耗統計列表",
     *      description="能耗統計列表",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="type",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="參數類型 electric: 電能 water: 水能",
     *              enum={"electric", "water"},
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="fee_number_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uuid",
     *              description="水電編號 ID"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="space_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uuid",
     *              description="空間 ID",
     *              default="37a40c44-1017-456f-83de-480b63c5bd01"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=14),
     *                      @OA\Property(property="space_id", type="string", example="d545f95a-4817-4926-8087-99b00b63398a"),
     *                      @OA\Property(property="fee_number_id", type="string", example="011e03dd-cb69-4d0a-880d-ede3d4647022"),
     *                      @OA\Property(property="start_at", type="string", format="date-time", example="2023-04-01"),
     *                      @OA\Property(property="end_at", type="string", format="date-time", example="2023-04-30"),
     *                      @OA\Property(property="consumption", type="integer", example=1000),
     *                      @OA\Property(property="cost", type="integer", example=6000000),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-23"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-23")
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
    public function show()
    {
    }


    /**
     * @OA\Post(
     *      path="/energy/{type}/space-statistics",
     *      tags={"Energy Statistics 能耗統計"},
     *      summary="建立能耗水電",
     *      description="建立能耗水電",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *             name="type",
     *             in="path",
     *             required=true,
     *             @OA\Schema(
     *                 type="string",
     *                 description="參數類型 electric: 電能 water: 水能",
     *                 enum={"electric", "water"},
     *            )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="space_id", type="string", description="戶別 uuid", example="37a40c44-1017-456f-83de-480b63c5bd01"),
     *                  @OA\Property(property="fee_number_id", type="string", description="水電編號 uuid", example="37a40c44-1017-456f-83de-480b63c5bd01"),
     *                  @OA\Property(property="start_at", type="string", description="計費開始時間", example="2023-04-01"),
     *                  @OA\Property(property="end_at", type="string", description="計費結束時間", example="2023-04-30"),
     *                  @OA\Property(property="consumption", type="string", description="當期用(電,水)量", example="10000"),
     *                  @OA\Property(property="cost", type="string", description="當期用(電,水)費用", example="600"),
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
     *      path="/energy/{type}/space-statistics/{id}",
     *      tags={"Energy Statistics 能耗統計"},
     *      summary="編輯能耗水電",
     *      description="編輯能耗水電",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *             name="type",
     *             in="path",
     *             required=true,
     *             @OA\Schema(
     *                 type="string",
     *                 description="參數類型 electric: 電能 water: 水能",
     *                enum={"electric", "water"},
     *             )
     *     ),
     *     @OA\Parameter(
     *                name="id",
     *                in="path",
     *                required=true,
     *         ),
     *      @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(
     *                   @OA\Property(property="start_at", type="string", description="計費開始時間", example="2023-04-01"),
     *                   @OA\Property(property="end_at", type="string", description="計費結束時間", example="2023-04-30"),
     *                   @OA\Property(property="consumption", type="string", description="當期用(電,水)量", example="10000"),
     *                   @OA\Property(property="cost", type="string", description="當期用(電,水)費用", example="600"),
     *             )
     *           )
     *       ),
     *      @OA\Response(response=200, description="修改成功"),
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
}
