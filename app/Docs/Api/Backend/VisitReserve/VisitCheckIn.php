<?php

namespace App\Docs\Api\Backend\VisitReserve;

class VisitCheckIn
{
    /**
     * @OA\Get(
     *     path="/visit/check-in/{visitReserveUuid}",
     *     tags={"Visit check-in"},
     *     summary="獲取單筆預約紀錄",
     *     description="獲取單筆預約紀錄",
     *     @OA\Parameter(
     *         name="visitReserveUuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", description="UUID")
     *     ),
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
     *                 @OA\Property(property="propertyUuid", type="string", example="租售物件管理 UUID"),
     *                 @OA\Property(property="appointmentTime", type="string", example="預約時間"),
     *                 @OA\Property(property="appointmentUnixTime", type="integer", example="預約時間-時間戳，主要提供前端，實現更多時間操作"),
     *                 @OA\Property(property="arrivalTime", type="string", example="抵達時間"),
     *                 @OA\Property(property="arrivalTimeUnixTime", type="integer", example="抵達時間-時間戳，主要提供前端，實現更多時間操作"),
     *                 @OA\Property(property="numberOfVisitors", type="integer", example="訪客人數"),
     *                 @OA\Property(property="visitorsName", type="string", example="訪客姓名"),
     *                 @OA\Property(property="visitorsCellphone", type="string", example="訪客手機"),
     *                 @OA\Property(property="alreadyCheckIn", type="boolean", example="是否已簽到，false:否，true:是"),
     *                 @OA\Property(property="cancel", type="boolean", example="是否已取消，false:否，true:是"),
     *                 @OA\Property(property="household_name", type="string", example="戶別"),
     *                 @OA\Property(property="comname", type="string", example="社區名稱")
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
    public function index() {}


    /**
     * @OA\Patch(
     *      path="/visit/check-in/{visitReserveUuid}",
     *      tags={"Visit check-in"},
     *      summary="簽到單筆預約看房",
     *      description="簽到單筆預約看房",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="visitReserveUuid",
     *          description="看房紀錄 UUID",
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
     *                  @OA\Property(property="signature", type="string", format="byte", default="", description="簽名 base64，必須是 base64 圖片"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="簽到成功"
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
    public function checkIn() {}
}
