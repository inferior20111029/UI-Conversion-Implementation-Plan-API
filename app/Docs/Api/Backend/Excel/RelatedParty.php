<?php

namespace App\Docs\Api\Backend\Excel;

class RelatedParty
{
    /**
     * @OA\Get(
     *     path="/excel/related-party/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="關係人匯出",
     *     description="關係人匯出",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="type",
     *          in="path",
     *          required=true,
     *          description="組態類型 example:模板  material:資料",
     *         @OA\Schema(
     *              type="string",
     *              enum={"example", "material"}
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
     *     path="/excel/related-party/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="關係人匯入",
     *     description="關係人匯入",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *          @OA\Parameter(
     *           name="type",
     *           in="path",
     *           required=true,
     *           description="組態類型 import:匯入 clear-import:清空重匯",
     *          @OA\Schema(
     *               type="string",
     *               enum={"import", "clear-import"}
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
