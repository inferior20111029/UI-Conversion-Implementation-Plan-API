<?php

namespace App\Docs\Api\Frontend\RealEstateAgent;

class Verify
{
    /**
     * @OA\Patch(
     *      path="/frontend/real-estate-agent/external/verify",
     *      operationId="RealEstateAgentVerifyAccount",
     *      tags={"Real-Estate-Agent 房仲列表 (前台)"},
     *      summary="驗證房仲帳號",
     *      description="此 API 的 Token 取得，需透過 /send/verify 取得，信件中的網址會帶上 token 的 query string",
     *      security={{"Authorization":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="驗證成功"
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
