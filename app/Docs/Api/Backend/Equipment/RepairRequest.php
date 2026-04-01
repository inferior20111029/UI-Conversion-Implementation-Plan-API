<?php

namespace App\Docs\Api\Backend\Equipment;

class RepairRequest
{
    /**
     * @OA\Get(
     *     path="/repair-request",
     *     tags={"Repair-Request 提報修繕"},
     *     summary="提報修繕 列表",
     *     description="提報修繕 列表",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="space_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[space]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=false)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[equipment_name]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[description]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *          name="filter_key[status]",
     *          in="query",
     *          required=false,
     *         @OA\Schema(
     *                 type="string",
     *                 description="狀態選項",
     *                 enum={"未提報", "已提報(尚未修繕)", "修繕中", "待驗收", "修繕完成", "未結案"},
     *           )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="perPage", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=1),
     *                 @OA\Property(property="lastPage", type="integer", example=1),
     *                 @OA\Property(property="next_url", type="string", example=null),
     *                 @OA\Property(property="prev_url", type="string", example=null),
     *                 @OA\Property(
     *                     property="list",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string", example="客廳sbp6"),
     *                         @OA\Property(property="space", type="string", example="客廳sbp6"),
     *                         @OA\Property(property="equipment_id", type="integer", example=2),
     *                         @OA\Property(property="equipment_name", type="string", example="桌子2"),
     *                         @OA\Property(property="description", type="string", example="sssss"),
     *                         @OA\Property(property="maintain", type="string", example="1222222"),
     *                         @OA\Property(property="status", type="string", example="已提報(尚未修繕)")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=301,
     *         description="網址跳轉"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token、或是無法識別的資料、登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="資源不存在，查無資料"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="程式錯誤"
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Get(
     *     path="/repair-request/create",
     *     tags={"Repair-Request 提報修繕"},
     *     summary="提報修繕 創建資料",
     *     description="提報修繕 創建資料",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="space_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="equipment",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="space", type="string", example="空間"),
     *                         @OA\Property(
     *                             property="equipment",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=1),
     *                                 @OA\Property(property="name", type="string", example="桌子")
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="maintain",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="value", type="string", example="test"),
     *                         @OA\Property(property="label", type="string", example="test"),
     *                         @OA\Property(
     *                             property="children",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=2),
     *                                 @OA\Property(property="value", type="string", example="test2"),
     *                                 @OA\Property(property="label", type="string", example="test2"),
     *                                 @OA\Property(
     *                                     property="children",
     *                                     type="array",
     *                                     @OA\Items(
     *                                         type="object",
     *                                         @OA\Property(property="id", type="integer", example=3),
     *                                         @OA\Property(property="value", type="string", example="test3"),
     *                                         @OA\Property(property="label", type="string", example="test3")
     *                                     )
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=301,
     *         description="網址跳轉"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token、或是無法識別的資料、登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="資源不存在，查無資料"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="程式錯誤"
     *     )
     * )
     */
    public function create()
    {
    }

    /**
     * @OA\Post(
     *      path="/repair-request",
     *      tags={"Repair-Request 提報修繕"},
     *      summary="新增提報修繕",
     *      description="新增提報修繕",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="space", type="string", description="空間", example="客廳"),
     *                  @OA\Property(property="equipment_id", type="string", description="類別值", example="中島"),
     *                  @OA\Property(property="maintain", type="string", description="異常肇因id", example="1,2"),
     *                  @OA\Property(property="description", type="string", description="異常描述", example="不正常"),
     *                  @OA\Property(property="space_id", type="string", description="戶別id", example="6d5aa138-974a-4bc2-bf42-51b82104ca2b"),
     *                  @OA\Property(property="video", type="string", description="影片uuid", example="2568df93-cc5e-4532-9333-4cb29360551e"),
     *                  @OA\Property(property="image[0]", type="string", description="圖片uuid", example="29ec6691-0a90-4700-a890-bf8c59f437bf"),
     *                  @OA\Property(property="type", type="string", description="public 公有 privacy 專有 ", example="privacy", enum={"public", "privacy"}),
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
    public function store()
    {
    }

    /**
     * @OA\Get(
     *     path="/repair-request/{id}/edit",
     *     tags={"Repair-Request 提報修繕"},
     *     summary="提報修繕 檢視編輯資料",
     *     description="提報修繕 檢視編輯資料",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="",
     *          @OA\Schema(
     *             type="string",
     *          )
     *        ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="space", type="string", example="客廳"),
     *                 @OA\Property(property="equipment_id", type="integer", example=1),
     *                 @OA\Property(property="equipment_name", type="string", example="桌子"),
     *                 @OA\Property(property="description", type="string", example="sssss"),
     *                 @OA\Property(property="maintain", type="string", example="1222222"),
     *                 @OA\Property(
     *                     property="file",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="type", type="integer", example=1, description="0:影片 1:圖片"),
     *                         @OA\Property(property="file_uuid", type="string", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                         @OA\Property(property="url", type="string", example="laravel-leasehold.test/storage/leasehold/893/6ygFJwM97F8k9LcLora62PPEx2qu2iucvw348loJ.xlsm")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=301,
     *         description="網址跳轉"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token、或是無法識別的資料、登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="資源不存在，查無資料"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="程式錯誤"
     *     )
     * )
     */
    public function edit()
    {
    }

    /**
     * @OA\Patch(
     *      path="/repair-request/{id}",
     *      tags={"Repair-Request 提報修繕"},
     *      summary="更新提報修繕",
     *      description="更新提報修繕",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *     @OA\Parameter(
     *            name="id",
     *            in="path",
     *            required=true,
     *            @OA\Schema(
     *                type="integer"
     *            )
     *      ),
     *      @OA\RequestBody(
     *           required=true,
     *           @OA\MediaType(
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(
     *                   @OA\Property(property="space", type="string", description="空間", example="客廳"),
     *                   @OA\Property(property="equipment_id", type="string", description="類別值", example="中島"),
     *                   @OA\Property(property="maintain", type="string", description="異常肇因id", example="1,2"),
     *                   @OA\Property(property="description", type="string", description="異常描述", example="不正常"),
     *                   @OA\Property(property="video", type="string", description="給有新上傳的影片uuid", example="2568df93-cc5e-4532-9333-4cb29360551e"),
     *                   @OA\Property(property="image[0]", type="string", description="給有新上傳的圖片uuid", example="29ec6691-0a90-4700-a890-bf8c59f437bf"),
     *               )
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
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/repair-request/{id}",
     *      tags={"Repair-Request 提報修繕"},
     *      summary="刪除提報修繕",
     *      description="刪除提報修繕",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="id",
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
