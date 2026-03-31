<?php

namespace App\Docs\Api\Backend\Selected;

class SpacePagination
{
    /**
     * @OA\Get(
     *      path="/selected/space/pagination",
     *      tags={"Selected 選項"},
     *      summary="取得戶別分頁列表",
     *      description="取得戶別分頁列表",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="page",
    *         in="query",
    *         required=false,
    *         @OA\Schema(type="integer", default=1)
    *      ),
    *      @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         required=false,
    *         @OA\Schema(type="integer", default=10)
    *       ),
     *      @OA\Parameter(
     *           name="filter_key[household_name]",
     *           in="query",
     *           required=false,
     *           @OA\Schema(type="string", description="戶別過濾", example="03F")
     *       ),
     *       @OA\Parameter(
     *            name="filter_key[district_name]",
     *            in="query",
     *            required=false,
     *            @OA\Schema(type="string", description="區過濾", example="A")
     *        ),
     *      @OA\Parameter(
     *             name="filter_key[building_name]",
     *             in="query",
     *             required=false,
     *             @OA\Schema(type="string", description="棟過濾", example="甲")
     *         ),
     *     @OA\Parameter(
     *              name="filter_key[floor_name]",
     *              in="query",
     *              required=false,
     *              @OA\Schema(type="string", description="樓層過濾", example="1F")
     *          ),
     *     @OA\Parameter(
     *               name="filter_key[staircase_name]",
     *               in="query",
     *               required=false,
     *               @OA\Schema(type="string", description="梯間過濾", example="")
     *           ),
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
     *                              "staircaseName": "梯間名稱",
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
