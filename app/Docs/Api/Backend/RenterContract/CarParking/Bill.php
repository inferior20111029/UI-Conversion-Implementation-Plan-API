<?php

namespace App\Docs\Api\Backend\RenterContract\CarParking;

class Bill
{
    /**
     * @OA\Post(
     *      path="/renter/car/parking/{carParkingId}/contract/{contractUuid}/bill",
     *      operationId="CreateCarParkingContractBill",
     *      tags={"Renter Contract 車位合約"},
     *      summary="建立單筆車位合約帳單",
     *      description="建立單筆車位合約帳單",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="carParkingId",
     *          description="車位 ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="contractUuid",
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
     *                  required={"startTime", "includeTax", "paid"},
     *                  @OA\Property(property="startTime", type="string", format="date", default="", description="帳單開始日期，格式為：Y-m-d"),
     *                  @OA\Property(property="endTime", type="string", format="date", default="", description="帳單結束日期，格式為：Y-m-d"),
     *                  @OA\Property(property="includeTax", type="integer", format="integer", default="", description="是否含稅 0:否,1:是"),
     *                  @OA\Property(property="paid", type="integer", format="integer", default="", description="是否已繳款 0:否,1:是"),
     *                  @OA\Property(property="billAmount[0][lineItem]", type="string", format="string", default="", description="客製化金額-項目名稱，最大字元：255"),
     *                  @OA\Property(property="billAmount[0][price]", type="integer", format="integer", default="", description="客製化金額-項目金額，最小值：-100000000，最大值：100000000"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="建立成功"
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

    /**
     * @OA\Patch(
     *      path="/renter/car/parking/{carParkingId}/contract/{contractUuid}/bill/{uuid}",
     *      operationId="UpdateAloneCarParkingContractBill",
     *      tags={"Renter Contract 車位合約"},
     *      summary="修改單筆車位合約帳單",
     *      description="修改單筆車位合約帳單",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="carParkingId",
     *          description="車位 ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="contractUuid",
     *          description="合約 UUID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="uuid",
     *          description="帳單 UUID",
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
     *                  required={"startTime", "includeTax", "paid"},
     *                  @OA\Property(property="startTime", type="string", format="date", default="", description="帳單開始日期，格式為：Y-m-d"),
     *                  @OA\Property(property="endTime", type="string", format="date", default="", description="帳單結束日期，格式為：Y-m-d"),
     *                  @OA\Property(property="includeTax", type="integer", format="integer", default="", description="是否含稅 0:否,1:是"),
     *                  @OA\Property(property="paid", type="integer", format="integer", default="", description="是否已繳款 0:否,1:是"),
     *                  @OA\Property(property="billAmount[0][lineItem]", type="string", format="string", default="", description="客製化金額-項目名稱，最大字元：255"),
     *                  @OA\Property(property="billAmount[0][price]", type="integer", format="integer", default="", description="客製化金額-項目金額，最小值：-100000000，最大值：100000000"),
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

    /**
     * @OA\Delete(
     *      path="/renter/car/parking/{carParkingId}/contract/{contractUuid}/bill/{uuid}",
     *      operationId="DeleteAloneCarParkingContractBill",
     *      tags={"Renter Contract 車位合約"},
     *      summary="刪除單筆車位合約帳單",
     *      description="刪除單筆車位合約帳單",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="carParkingId",
     *          description="車位 ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="contractUuid",
     *          description="合約 UUID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="uuid",
     *          description="帳單 UUID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="刪除成功"
     *       ),
     *      @OA\Response(
     *          response=301,
     *          description="網址跳轉"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="參數錯誤"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="無效的 Token、或是無法識別的資料、登入失敗"
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="資源不存在，查無資料"
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="程式錯誤"
     *       )
     * )
     */
    public function destroy() {}
}
