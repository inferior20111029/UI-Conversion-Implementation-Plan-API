<?php

namespace App\Docs\Api\Backend\RenterContract;

class AcceptanceRejection
{
    /**
     * @OA\Post(
     *      path="/renter/acceptance-rejection/{uuid}",
     *      tags={"Acceptance-Rejection 驗收 退驗"},
     *      summary="線上驗收 / 退驗",
     *      description="線上驗收 / 退驗",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
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
     *                  required={"type", "signature"},
     *                  @OA\Property(property="id[0]", type="integer", default="", description="元件id"),
     *                  @OA\Property(property="type", type="integer", description="type 0 驗收 1 驗退"),
     *                  @OA\Property(property="remark", type="string", default="",description="備註"),
     *                  @OA\Property(property="signature", type="string", format="byte", default="", description="簽名 base64"),
     *
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(response=200, description="修改成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function store() {}

    /**
     * @OA\Post(
     *      path="/renter/acceptance-rejection/{uuid}/document",
     *      tags={"Acceptance-Rejection 驗收 退驗"},
     *      summary="上傳線上驗收 / 退驗清單",
     *      description="線上驗收 / 退驗清單",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
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
     *                  required={"type", "document"},
     *                  @OA\Property(property="type", type="integer", description="type 0 驗收 1 驗退"),
     *                  @OA\Property(property="document", type="string", description="驗收/驗退 清單", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(response=200, description="修改成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function document() {}

    /**
     * @OA\Get(
     *      path="/renter/acceptance-rejection/{uuid}",
     *      tags={"Acceptance-Rejection 驗收 退驗"},
     *      summary="檢視(線上) 已驗收/驗退 確認單",
     *      description="檢視(線上) 已驗收/驗退 確認單",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", description="租戶的UUID")
     *     ),
     *      @OA\Parameter(
     *            name="type",
     *            in="query",
     *            required=true,
     *            description="驗收:0 驗退:1",
     *            @OA\Schema(type="integer", default=1)
     *        ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="signature", type="string", example="簽名檔"),
     *                 @OA\Property(property="remark", type="string", example="備註"),
     *                 @OA\Property(
     *                     property="attachedEquipment",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example="元件編號"),
     *                         @OA\Property(property="name", type="string", example="元件名稱"),
     *                         @OA\Property(property="state", type="integer", example="確認打勾 0 沒打 1 有打勾"),
     *                         @OA\Property(property="isScrap", type="boolean", example="是否報廢:true報廢false 購置"),
     *                         @OA\Property(property="scrapAt", type="string",example="購置時間"),
     *                         @OA\Property(property="purchaseAt", type="string", example="報廢使間")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token 或無法識別的資料"),
     *     @OA\Response(response=403, description="無權限訪問此項目"),
     *     @OA\Response(response=404, description="資源不存在"),
     *     @OA\Response(response=500, description="伺服器錯誤")
     * )
     */
    public function show() {}
}
