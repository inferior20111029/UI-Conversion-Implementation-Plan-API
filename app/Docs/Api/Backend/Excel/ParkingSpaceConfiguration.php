<?php

namespace App\Docs\Api\Backend\Excel;

class ParkingSpaceConfiguration
{
    /**
     * @OA\Get(
     *     path="/excel/parking-space-configuration/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="車位配置EXCEL 匯出",
     *     description="車位配置EXCEL 匯出",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="type",
     *          in="path",
     *          required=true,
     *          description="匯出類型 template:模板 material:資料 summary-table:總表",
     *         @OA\Schema(
     *              type="string",
     *              enum={"template", "material", "summary-table"}
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
     *     path="/excel/parking-space-configuration/{type}",
     *     tags={"Excel 匯入匯出"},
     *     summary="車位配置EXCEL 匯入",
     *     description="車位配置EXCEL 匯入",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="type",
     *          in="path",
     *          required=true,
     *          description="匯入類型 material:匯入資料  clear:清空匯入",
     *         @OA\Schema(
     *              type="string",
     *              enum={"material", "clear"}
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
}
