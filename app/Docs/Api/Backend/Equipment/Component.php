<?php

namespace App\Docs\Api\Backend\Equipment;

class Component
{
    /**
     * @OA\Get(
     *     path="/equipment/equipment/component",
     *     tags={"Equipment-Component 元件構件"},
     *     summary="元件構件 列表",
     *     description="取得元件構件的列表",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
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
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="crm_equipment_id", type="integer", example=0),
     *                     @OA\Property(property="name", type="string", example="閥體2"),
     *                     @OA\Property(property="type", type="string", example="主體部件"),
     *                     @OA\Property(property="manufacturer", type="string", example="AquaMaster Inc."),
     *                     @OA\Property(property="model", type="string", example="AMV-1000"),
     *                     @OA\Property(property="serial_number", type="string", example="AM-10002234"),
     *                     @OA\Property(property="installation_date", type="string", format="date", example="2023-07-01"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-21"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-21")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *     @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *     @OA\Response(response=404, description="資源不存在，查無資料"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *      path="/equipment/equipment/component",
     *      tags={"Equipment-Component 元件構件"},
     *      summary="新增元件構件",
     *      description="新增元件構件 ID 用來之後元件新增編輯使用",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", description="元件名稱", example="閥體"),
     *                  @OA\Property(property="type", type="string", description="元件類型", example="主體部件"),
     *                  @OA\Property(property="manufacturer", type="string", description="製造商", example="AquaMaster Inc."),
     *                  @OA\Property(property="model", type="string", description="型號", example="AMV-1000"),
     *                  @OA\Property(property="serial_number", type="string", description="序列號", example="AM-10002234"),
     *                  @OA\Property(property="installation_date", type="string", format="date", description="安裝日期", example="2023-07-01")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully created equipment component.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=201),
     *              @OA\Property(property="message", type="string", example="元件構件新增成功"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="component_id", type="integer", example=1, description="構件 id"),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token"),
     *      @OA\Response(response=403, description="權限不足"),
     *      @OA\Response(response=500, description="伺服器錯誤")
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Patch(
     *      path="/equipment/equipment/component/{id}",
     *      tags={"Equipment-Component 元件構件"},
     *      summary="更新元件類別",
     *      description="更新元件類別",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\Parameter(
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
     *                   @OA\Property(property="name", type="string", description="元件名稱", example="閥體"),
     *                   @OA\Property(property="type", type="string", description="元件類型", example="主體部件"),
     *                   @OA\Property(property="manufacturer", type="string", description="製造商", example="AquaMaster Inc."),
     *                   @OA\Property(property="model", type="string", description="型號", example="AMV-1000"),
     *                   @OA\Property(property="serial_number", type="string", description="序列號", example="AM-10002234"),
     *                   @OA\Property(property="installation_date", type="string", format="date", description="安裝日期", example="2023-07-01")
     *               )
     *           )
     *       ),
     *      @OA\Response(response=200, description="更新成功"),
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