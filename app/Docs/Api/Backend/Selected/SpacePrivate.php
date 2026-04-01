<?php

namespace App\Docs\Api\Backend\Selected;

class SpacePrivate
{
    /**
     * @OA\Get(
     *      path="/selected/space/private",
     *      operationId="SelectedSpacePrivate",
     *      tags={"Selected 選項"},
     *      summary="取得戶別列表",
     *      description="取得戶別列表",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "取得成功",
     *                      "data": {
     *                          {
     *                              "spaceId": "戶別 ID",
     *                              "householdName": "戶別名稱",
     *                              "districtName": "區域名稱",
     *                              "buildingName": "棟別名稱",
     *                              "floorName": "樓層名稱",
     *                          }
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
    public function index() {}
}
