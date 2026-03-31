<?php

namespace App\Docs\Api\Frontend\RealEstateAgent\Send;

class SendChange
{
    /**
     * @OA\Post(
     *      path="/frontend/real-estate-agent/{uuid}/send/password/change",
     *      operationId="RealEstateAgentSendPasswordChange",
     *      tags={"Real-Estate-Agent 房仲列表 (前台)"},
     *      summary="發送密碼修改信",
     *      description="發送密碼修改信",
     *      security={{"Authorization":{}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          description="房仲 UUID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="發送成功"
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
