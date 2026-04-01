<?php

namespace App\Docs\Api\Backend\Equipment;

class Space
{
    /**
     * @OA\Get(
     *     path="/equipment/space/{space_id}",
     *     tags={"Equipment 空間下元件"},
     *     summary="取得指定空間元件資料",
     *     description="取得指定空間元件資料",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="space_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
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
     *                     @OA\Property(property="id", type="integer", example=1, description="元件id"),
     *                     @OA\Property(property="name", type="string", example="桌子", description="元件名稱"),
     *                     @OA\Property(property="type_name", type="string", example="客", description="類別"),
     *                     @OA\Property(property="system_name", type="string", example="變", description="系統"),
     *                     @OA\Property(property="is_scrap", type="string", example="是否報廢 (TRUE 是)", description="TRUE"),
     *                     @OA\Property(
     *                         property="component",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1, description="構件 id"),
     *                             @OA\Property(property="name", type="string", example="閥體", description="構件 名稱"),
     *                             @OA\Property(property="type", type="string", example="主體部件", description="構件 類別"),
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="資源不存在"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/equipment/space/{space_id}",
     *     tags={"Equipment 空間下元件"},
     *     summary="新增空間元件資料",
     *     description="新增空間元件資料",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="space_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data[0][name]", type="string", description="單一設備用 群組不用給 元件名稱", example="桌子"),
     *                 @OA\Property(property="data[0][area]", type="string", description="單一設備用 群組不用給 區域", example="區域"),
     *                 @OA\Property(property="data[0][space]", type="string", description="單一設備用 群組不用給 空間", example="空間"),
     *                 @OA\Property(property="data[0][location]", type="string", description="單一設備用 群組不用給 位置", example="位置"),
     *                 @OA\Property(property="data[0][brand]", type="string", description="單一設備用 群組不用給 品牌", example="格陵"),
     *                 @OA\Property(property="data[0][model]", type="string", description="單一設備用 群組不用給 型號", example=""),
     *                 @OA\Property(property="data[0][count]", type="integer", description="單一設備用 群組不用給 數量", example=2),
     *                 @OA\Property(property="equipment_group_id", type="integer", description="data 資料不用給 設備群組 ID", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="建立成功"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Post(
     *     path="/equipment/multi-space",
     *     tags={"Equipment 空間下元件", "Batch-Setting 專有批次設定"},
     *     summary="批次配置設備群組",
     *     description="批次配置設備群組",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="space_id[0]", type="string", description="空間 id", example="8bf7556b-5618-4a83-ad8b-9c905af05327"),
     *                 @OA\Property(property="equipment_group_id", type="integer", description="設備群組 ID", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="建立成功"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function multiSpaceStore()
    {
    }

    /**
     * @OA\Delete(
     *     path="/equipment/space/{id}",
     *     tags={"Equipment 空間下元件"},
     *     summary="刪除指定空間中的元件或設備",
     *     description="根據類型 ID 刪除指定類型的元件或設備",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", description="要刪除的類型 (構件:component 元件:equipment)")
     *     ),
     *     @OA\Response(response=200, description="刪除成功"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=404, description="資源不存在"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *     path="/equipment/space-scrap",
     *     tags={"Equipment 空間下元件", "Batch-Setting 專有批次設定"},
     *     summary="報廢設備",
     *     description="報廢設備",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="id[0]", type="integer", description="元件 ID", example="1"),
     *                 @OA\Property(property="id[1]", type="integer", description="元件 ID", example="2"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="建立成功"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function scrapStore()
    {
    }
}