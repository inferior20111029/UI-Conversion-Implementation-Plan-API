<?php

namespace App\Docs\Api\Backend\Excel\PropertyRightArea;

class Export
{
    /**
     * @OA\Post(
     *      path="/excel/property/right/area/exports",
     *      operationId="AreaExcelExports",
     *      tags={"Excel 匯入匯出"},
     *      summary="匯出面積 Excel 模板",
     *      description="匯出面積 Excel 模板",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="匯出 Excel"
     *      ),
     *      @OA\Response(
     *          response=301,
     *          description="網址跳轉"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="參數錯誤"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="無效的 Token、或是無法識別的資料、登入失敗"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="資源不存在，查無資料"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="程式錯誤"
     *      )
     * )
     */
    public function store()
    {
    }
}
