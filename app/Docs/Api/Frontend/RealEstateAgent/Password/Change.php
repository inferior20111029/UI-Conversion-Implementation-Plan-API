<?php

namespace App\Docs\Api\Frontend\RealEstateAgent\Password;

class Change
{
    /**
     * @OA\Patch(
     *      path="/frontend/real-estate-agent/external/change/password",
     *      operationId="RealEstateAgentChangePassword",
     *      tags={"Real-Estate-Agent 房仲列表 (前台)"},
     *      summary="修改密碼",
     *      description="此 API 的 Token 取得，需透過 /send/password/change 取得，信件中的網址會帶上 token 的 query string",
     *      security={{"Authorization":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"original_password", "password", "password_confirmation"},
     *                  @OA\Property(property="original_password", type="string", format="string", default="", description="原始密碼"),
     *                  @OA\Property(property="password", type="string", format="string", default="", description="密碼，至少需要 6 字元"),
     *                  @OA\Property(property="password_confirmation", type="string", format="string", default="", description="確認密碼")
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
    public function store()
    {
    }
}
