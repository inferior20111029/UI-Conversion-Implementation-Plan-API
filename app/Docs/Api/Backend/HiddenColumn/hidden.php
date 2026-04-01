<?php

namespace App\Docs\Api\Backend\HiddenColumn;

class hidden
{
    /**
     * @OA\Get(
     *     path="/hidden-column",
     *     tags={"Hidden Columns 隱藏欄位"},
     *     summary="取得隱藏欄位",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="simple_column_hidden",
     *             description="隱藏欄位表的值"
     *         )
     *     ),
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
     *                 description="隱藏欄位參數",
     *                 @OA\Items(
     *                     type="array",
     *                     @OA\Items(type="string", example="district_name")
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
     *     path="/hidden-column",
     *     tags={"Hidden Columns 隱藏欄位"},
     *     summary="存檔 隱藏欄位",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"key"},
     *                 @OA\Property(
     *                     property="key",
     *                     type="string",
     *                     description="隱藏欄位表的值",
     *                     example="simple_column_hidden"
     *                 ),
     *                 @OA\Property(
     *                     property="hidden_column[0]",
     *                     type="string",
     *                     description="隱藏欄位參數",
     *                     example="simple_column_name"
     *                 ),
     *                 @OA\Property(
     *                     property="hidden_column[1]",
     *                     type="string",
     *                     description="隱藏欄位第二參數",
     *                     example="simple_second_column_name"
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
    public function store()
    {
    }
}