<?php

namespace App\Docs\Api\Backend\Equipment;

class GroupEquipment
{
    /**
     * @OA\Get(
     *     path="/equipment/equipment/group",
     *     summary="設備群組列表",
     *     description="設備群組列表",
     *     tags={"Equipment-Group 設備群組"},
     *     security={{"Authorization": {}},{"Community-Id-Header": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=8),
     *                     @OA\Property(property="name", type="string", example="A型設備"),
     *                     @OA\Property(
     *                         property="list",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="equipment_name", type="string", example="桌子"),
     *                             @OA\Property(property="equipment_id", type="integer", example="2"),
     *                             @OA\Property(property="type_name", type="string", example="父層"),
     *                             @OA\Property(property="system_name", type="string", example=""),
     *                             @OA\Property(property="count", type="integer", example="100"),
     *                         )
     *                     ),
     *                     @OA\Property(property="count", type="integer", example=250, description="設備數量")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="建立成功"),
     *     @OA\Response(response=301, description="網址跳轉"),
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
     * @OA\Get(
     *     path="/equipment/equipment/group/create",
     *     summary="設備列表",
     *     description="新增和編輯 初始化設備列表資料",
     *     tags={"Equipment-Group 設備群組"},
     *     security={{"Authorization": {}},{"Community-Id-Header": {}}},
     *     @OA\Parameter(name="filter_key[name]", in="query", required=false, description="設備名稱",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(name="filter_key[type_name]", in="query", required=false, description="類別名稱",
     *         @OA\Schema(type="integer", default= 1)
     *     ),
     *     @OA\Parameter(name="filter_key[system_name]", in="query", required=false, description="系統名稱",
     *         @OA\Schema(type="integer", default= 1)
     *     ),
     *     @OA\Parameter(name="filter_key[brand]", in="query", required=false, description="品牌",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(name="filter_key[model]", in="query", required=false, description="型號",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(name="filter_key[area]", in="query", required=false, description="區域",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(name="filter_key[location]", in="query", required=false, description="位置",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(name="filter_key[space]", in="query", required=false, description="空間",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(name="filter_key[public_type]", in="query", required=false, description="空間屬性(L=大公, S=小公, P=專有)",
     *         @OA\Schema(
     *             type="string",
     *             enum={"L", "S", "P"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="桌子"),
     *                     @OA\Property(property="type_name", type="string", example="父層"),
     *                     @OA\Property(property="system_name", type="string", example="子層"),
     *                     @OA\Property(property="brand", type="string", example="品牌"),
     *                     @OA\Property(property="model", type="string", example="型號"),
     *                     @OA\Property(property="area", type="string", example="區域"),
     *                     @OA\Property(property="space", type="string", example="空間"),
     *                     @OA\Property(property="location", type="string", example="位置"),
     *                     @OA\Property(property="public_type", type="string", example="p")
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
    public function create()
    {
    }

    /**
     * @OA\Post(
     *     path="/equipment/equipment/group",
     *     tags={"Equipment-Group 設備群組"},
     *     summary="新增設備群組",
     *     description="新增設備群組",
     *     security={{"Authorization": {}},{"Community-Id-Header": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", description="群族名稱", example= "A群組"),
     *                  @OA\Property(property="equipment_list[0][id]", type="integer", description="設備id", example= "1"),
     *                  @OA\Property(property="equipment_list[0][count]", type="integer", description="設備數量", example= "100"),
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
     *     path="/equipment/equipment/group/{id}/edit",
     *     tags={"Equipment-Group 設備群組"},
     *     summary="編輯設備群組資料",
     *     description="新增設備群組資料",
     *     security={{"Authorization": {}},{"Community-Id-Header": {}}},
     *      @OA\Parameter(
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
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="name", type="string", example="A型設備", description="群組設備名稱"),
     *                 @OA\Property(
     *                     property="list",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="equipment_id", type="integer", example=2, description="設備id"),
     *                         @OA\Property(property="name", type="string", example="桌子", description="設備名稱"),
     *                         @OA\Property(property="type_name", type="string", example=2, description="類型"),
     *                         @OA\Property(property="system_name", type="string", example=2, description="系統"),
     *                         @OA\Property(property="area", type="string", example=2, description="區域"),
     *                         @OA\Property(property="space", type="string", example=2, description="空間"),
     *                         @OA\Property(property="brand", type="string", example=2, description="品牌"),
     *                         @OA\Property(property="model", type="string", example=2, description="型號"),
     *                         @OA\Property(property="location", type="string", example=2, description="型號"),
     *                         @OA\Property(property="public_type", type="string", example="P", description=""),
     *                         @OA\Property(property="count", type="integer", example=150, description="設備數量")
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

    public function edit()
    {
    }

    /**
     * @OA\Patch(
     *     path="/equipment/equipment/group/{id}",
     *     tags={"Equipment-Group 設備群組"},
     *     summary="編輯設備群組資料",
     *     description="Allows for the editing of an equipment group's details, including adding or removing equipment and adjusting quantities.",
     *     security={
     *         {"Authorization": {}},
     *         {"Community-Id-Header": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the equipment group to be edited.",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="群組名稱", example= "BB群組"),
     *                 @OA\Property(property="equipment_del[0]", type="integer", description="刪除的設備ID", example= "1"),
     *                 @OA\Property(
     *                     property="equipment_list[0][id]",
     *                     type="integer",
     *                     description="設備ID",
     *                     example= "3"
     *                 ),
     *
     *                 @OA\Property(
     *                     property="equipment_list[0][count]",
     *                     type="integer",
     *                     description="設備數量",
     *                     example= "100"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Successful creation of the equipment group."),
     *     @OA\Response(response=400, description="Bad request due to incorrect parameters."),
     *     @OA\Response(response=401, description="Unauthorized access due to invalid token or unrecognized data."),
     *     @OA\Response(response=403, description="Forbidden access using a banned token or attempting to access a resource for which the user lacks permissions."),
     *     @OA\Response(response=404, description="Resource not found."),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */

    public function update()
    {
    }

    /**
     * @OA\Delete(
     *       path="/equipment/equipment/group/{id}",
     *       tags={"Equipment-Group 設備群組"},
     *       summary="刪除設備群組",
     *       description="刪除設備群組",
     *       security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *       @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="",
     *          @OA\Schema(
     *             type="string",
     *          )
     *        ),
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
