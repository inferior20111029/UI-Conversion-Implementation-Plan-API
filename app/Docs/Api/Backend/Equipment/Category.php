<?php

namespace App\Docs\Api\Backend\Equipment;

class Category
{
    /**
     * @OA\Get(
     *     path="/equipment/equipment/category",
     *     tags={"Equipment-Category 元件類別"},
     *     summary="元件類別 列表",
     *     description="元件類別 列表",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="父層"
     *                 ),
     *                 @OA\Property(
     *                     property="parent",
     *                     type="integer",
     *                     example=0
     *                 ),
     *                 @OA\Property(
     *                     property="level",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="company_id",
     *                     type="integer",
     *                     example=10
     *                 ),
     *                 @OA\Property(
     *                     property="branch",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="parent", type="integer"),
     *                         @OA\Property(property="level", type="integer"),
     *                         @OA\Property(property="company_id", type="integer")
     *                     ),
     *                     description="Nested branches under this node"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *      path="/equipment/equipment/category",
     *      tags={"Equipment-Category 元件類別"},
     *      summary="新增元件類別",
     *      description="新增元件類別",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"identity", "name"},
     *                  @OA\Property(property="identity", type="string", description="type:類別 system:系統", example="type"),
     *                  @OA\Property(property="name", type="string", description="類別值", example="1111"),
     *                  @OA\Property(property="parent_id", type="integer", description="是新增系統才需要parent_id"),
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
     * @OA\Patch(
     *      path="/equipment/equipment/category/{id}",
     *      tags={"Equipment-Category 元件類別"},
     *      summary="更新元件類別",
     *      description="更新元件類別",
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
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"name"},
     *                  @OA\Property(property="name", type="string", description="類別值", example="水電"),
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
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/equipment/equipment/category/{id}",
     *      tags={"Equipment-Category 元件類別"},
     *      summary="刪除元件類別",
     *      description="刪除元件類別",
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

    /**
     * @OA\Get(
     *      path="/equipment/equipment/category-merge",
     *      tags={"Equipment-Category 元件類別"},
     *     summary="元件類別合併資料",
     *     description="元件類別合併資料",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="company_id",
     *                     type="integer",
     *                     example=10
     *                 ),
     *                 @OA\Property(
     *                     property="comid",
     *                     type="integer",
     *                     example=899
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="父層"
     *                 ),
     *                 @OA\Property(
     *                     property="parent",
     *                     type="integer",
     *                     example=0
     *                 ),
     *                 @OA\Property(
     *                     property="level",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2024-04-30T05:44:52.000000Z"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2024-04-30T05:56:49.000000Z"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function mergeInfo()
    {
    }

    /**
     * @OA\Patch(
     *      path="/equipment/equipment/category-merge",
     *      tags={"Equipment-Category 元件類別"},
     *      summary="合併元件類別",
     *      description="合併元件類別",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"original", "target"},
     *                  @OA\Property(property="original", type="string", description="原始id", example="1"),
     *                  @OA\Property(property="target", type="string", description="目標id", example="2"),
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
    public function merge()
    {
    }
}
