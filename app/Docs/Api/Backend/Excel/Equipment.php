<?php

namespace App\Docs\Api\Backend\Excel;

class Equipment
{
    /**
     * @OA\Get(
     *     path="/excel/equipment/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="元件EXCEL 匯出",
     *     description="元件EXCEL 匯出",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="type",
     *          in="path",
     *          required=true,
     *          description="匯出類型 example-empty-space:設備總量模板 example-detail:設備細項模板 properties:詳細屬性編輯工具",
     *         @OA\Schema(
     *              type="string",
     *              enum={"example-empty-space", "example-detail", "example-detail", "properties"}
     *          )
     *      ),
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
    public function export()
    {
    }

    /**
     * @OA\Post(
     *     path="/excel/equipment/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="元件EXCEL 匯入",
     *     description="元件EXCEL 匯入",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="type",
     *          in="path",
     *          required=true,
     *          description="匯入類型 example-empty-space:總量匯入 example-detail:細項匯入 clear-example-empty-space:總量匯入-清空 clear-example-detail細項匯入-清空",
     *         @OA\Schema(
     *              type="string",
     *              enum={"example-empty-space", "example-detail", "clear-example-empty-space", "clear-example-detail"}
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="要匯入的檔案"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="建立成功"
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
    public function import()
    {
    }

    /**
     * @OA\Post(
     *     path="/excel/equipment-upload-zip",
     *     tags={"Excel 匯入匯出"},
     *     summary="元件檔案匯入",
     *     description="元件檔案匯入",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="zipFile",
     *                     type="string",
     *                     format="binary",
     *                     description="要匯入的檔案"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="建立成功"
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
    public function uploadZip()
    {
    }
}
