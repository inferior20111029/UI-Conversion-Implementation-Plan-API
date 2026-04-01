<?php

namespace App\Docs\Api\Backend\Excel\PropertyRightArea;

class Import
{
    /**
     * @OA\Post(
     *      path="/excel/property/right/area/imports",
     *      operationId="AreaExcelImports",
     *      tags={"Excel 匯入匯出"},
     *      summary="匯入面積 Excel",
     *      description="匯入面積 Excel",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"excel"},
     *                  @OA\Property(property="excel", type="string", format="binary", description="面積模板 Excel"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="匯入成功"
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
