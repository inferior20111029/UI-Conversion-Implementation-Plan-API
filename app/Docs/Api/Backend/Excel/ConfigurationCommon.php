<?php

namespace App\Docs\Api\Backend\Excel;

class ConfigurationCommon
{
    /**
     * @OA\Get(
     *     path="/excel/configuration-common/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="公共空間配置匯出",
     *     description="公共空間配置匯出",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="type",
     *          in="path",
     *          required=true,
     *          description="組態類型 space-example:空間配置-模板 fee-number-example:水電號-模板 space-material:空間配置-資料 fee-number-material水電號-資料",
     *         @OA\Schema(
     *              type="string",
     *              enum={"space-example", "fee-number-example", "space-material", "fee-number-material"}
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
     *     path="/excel/configuration-common/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="公共空間配置匯入",
     *     description="公共空間配置匯入",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *           name="type",
     *           in="path",
     *           required=true,
     *           description="組態類型 space:空間配置-匯入 fee-number:水電號-匯入 clear-space:空間配置-清空匯入 clear-fee-number水電號-清空匯入",
     *          @OA\Schema(
     *               type="string",
     *               enum={"space", "fee-number", "clear-space", "clear-fee-number"}
     *           )
     *       ),
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
}