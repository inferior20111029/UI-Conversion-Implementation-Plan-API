<?php

namespace App\Docs\Api\Frontend\File;

class Destroy
{
    /**
     * @OA\Delete(
     *      path="/frontend/file/{uuid}",
     *      operationId="FrontendFileDelete",
     *      tags={"File 檔案"},
     *      summary="前台-刪除檔案",
     *      description="前台-刪除檔案",
     *      security={{"Authorization":{}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          description="檔案 UUID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="刪除成功"
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
    public function destroy() {}

    /**
     * @OA\Post(
     *      path="/frontend/file/multiple/delete",
     *      operationId="FrontendFileMultipleDelete",
     *      tags={"File 檔案"},
     *      summary="前台-刪除複數檔案",
     *      description="前台-刪除複數檔案",
     *      security={{"Authorization":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"target[0]"},
     *                  @OA\Property(property="target[0]", type="string", format="uuid", default="", description="檔案 UUID，如果要刪除多個檔案，後端預期收到陣列資料，例：[fileUuid1, fileUuid2, fileUuid3]"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="刪除成功"
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
    public function multiple() {}
}
