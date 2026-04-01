<?php

namespace App\Docs\Api\Backend\Space;

class CrmWarrantySelect
{
    /**
     * @OA\Get(
     *     path="/warranty",
     *     tags={"Warranty 保固類型"},
     *     summary="獲取保固類型列表",
     *     description="取得保固類型的完整列表",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
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
     *                     @OA\Property(property="value", type="string", example="機械")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token 或無法識別的資料"),
     *     @OA\Response(response=403, description="無權限訪問此項目"),
     *     @OA\Response(response=404, description="資源不存在"),
     *     @OA\Response(response=500, description="伺服器錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/warranty",
     *     tags={"Warranty 保固類型"},
     *     summary="新增保固選單",
     *     description="新增保固選單",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="value", type="string", description="保固選單值", example="牆壁類"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="批量設定成功"),
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
     * @OA\Patch (
     *     path="/warranty/{id}",
     *     tags={"Warranty 保固類型"},
     *     summary="編輯保固選單",
     *     description="新增保固選單",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *            name="id",
     *            in="path",
     *            required=true,
     *            description="保固 ID",
     *            @OA\Schema(
     *                type="integer",
     *                format="integer"
     *           )
     *       ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="value", type="string", description="保固選單值", example="牆壁類"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="批量設定成功"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/warranty/{id}",
     *      tags={"Warranty 保固類型"},
     *      summary="刪除保固類型",
     *      description="刪除保固類型",
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
     *     @OA\Response(response=201, description="批量設定成功"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * * )
 */
    public function destroy()
    {
    }
}

