<?php

namespace App\Docs\Api\Backend\Selected;

class Configuration
{
    /**
     * @OA\Get(
     *      path="/selected/configuration/{type}",
     *      tags={"Selected 選項"},
     *      summary="空間列表 下拉選單",
     *      description="取得空間列表 下拉選單",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="type",
     *           in="path",
     *           required=true,
     *           description="專有:privacy 公有:public",
     *          @OA\Schema(
     *               type="string",
     *               enum={"public", "privacy"}
     *           )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="building_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="甲棟")
     *                  ),
     *                  @OA\Property(
     *                      property="district_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="B")
     *                  ),
     *                  @OA\Property(
     *                      property="staircase_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="")
     *                  ),
     *                  @OA\Property(
     *                      property="floor_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="11F")
     *                  ),
     *                  @OA\Property(
     *                      property="household_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="18B")
     *                  ),
     *                  @OA\Property(
     *                      property="doorplate",
     *                      type="array",
     *                      @OA\Items(type="string", example="dooe")
     *                  ),
     *                  @OA\Property(
     *                      property="block_id",
     *                      type="array",
     *                      @OA\Items(type="string", example="123654")
     *                  )
     *              )
     *          )
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
    public function index()
    {
    }
}
