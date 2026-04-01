<?php

namespace App\Docs\Api\Frontend\File;

class Upload
{
    /**
     * @OA\Post(
     *      path="/frontend/file/upload",
     *      operationId="FrontendFileUpload",
     *      tags={"File 檔案"},
     *      summary="前台-檔案上傳",
     *      description="前台檔案分塊上傳 使用 pionl/laravel-chunk-upload 進行開發",
     *      security={{"Authorization":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"file"},
     *                  @OA\Property(property="file", type="string", format="binary", description="檔案"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="上傳成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "上傳成功",
     *                      "data": {
     *                         "uuid": "檔案 UUID",
     *                      }
     *                  }
     *              )
     *          }
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
    public function store() {}
}
