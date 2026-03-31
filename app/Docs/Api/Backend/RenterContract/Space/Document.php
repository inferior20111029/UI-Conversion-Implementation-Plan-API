<?php

namespace App\Docs\Api\Backend\RenterContract\Space;

class Document
{
    /**
     * @OA\Patch(
     *      path="/renter/space/{spaceId}/contract/{uuid}/document",
     *      tags={"Renter Contract 戶別合約"},
     *      summary="上傳合約文本",
     *      description="上傳合約文本",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="spaceId",
     *          description="戶別 ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="uuid",
     *          description="合約 UUID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"file_id"},
     *                  @OA\Property(property="file_id", type="string", description="合約文本 uuid 檔案", example="f5dc3def-70f7-494e-8972-8ba971bb41a4"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="修改成功"
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
    public function update() {}
}
