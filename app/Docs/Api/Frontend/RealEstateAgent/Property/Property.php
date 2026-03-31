<?php

namespace App\Docs\Api\Frontend\RealEstateAgent\Property;

class Property
{
    /**
     * @OA\Get(
     *      path="/frontend/real-estate-agent/property",
     *      operationId="FrontendRealEstateAgentProperty",
     *      tags={"前台-房仲房屋物件"},
     *      summary="取得房仲擁有的房屋物件",
     *      description="取得房仲擁有的房屋物件",
     *      security={{"Authorization":{}}},
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
     *                              "uuid": "物件 UUID",
     *                              "title": "物件標題",
     *                              "creator": "上架者名字",
     *                              "districtName": "區名名稱",
     *                              "buildingName": "棟別名稱",
     *                              "staircaseName": "梯間名稱",
     *                              "floorName": "樓層名稱",
     *                              "householdName": "戶別名稱",
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
