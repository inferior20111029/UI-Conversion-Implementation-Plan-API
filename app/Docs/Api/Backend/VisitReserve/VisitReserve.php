<?php

namespace App\Docs\Api\Backend\VisitReserve;

class VisitReserve
{
    /**
     * @OA\Get(
     *      path="/visit/reserve",
     *      operationId="FetchAllVisitReserve",
     *      tags={"Visit Reserve 預約看房"},
     *      summary="取得全部預約看房紀錄",
     *      description="取得全部預約看房紀錄",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(name="page", description="頁數", required=false, in="query", @OA\Schema(type="integer", format="integer")),
     *      @OA\Parameter(name="perPage", description="呈現幾筆資料，如果無填寫，呈現 10 筆資料", required=false, in="query", @OA\Schema(type="integer", format="integer")),
     *      @OA\Parameter(
     *          name="searchDistrictName",
     *          description="搜尋區名",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchBuildingName",
     *          description="搜尋棟別",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchStaircaseName",
     *          description="搜尋梯間",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchFloorName",
     *          description="搜尋樓層",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchHouseholdName",
     *          description="搜尋戶別 / 公設名稱",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchDoorplate",
     *          description="搜尋門牌",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchRealEstateAgentName",
     *          description="搜尋帶看人員",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchReservationPerson",
     *          description="搜尋預約者",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchVisitorsName",
     *          description="搜尋訪客",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchAppointmentTime",
     *          description="搜尋預約時間",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="searchArrivalTime",
     *          description="搜尋抵達時間",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
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
     *                          "page": "當前頁",
     *                          "perPage": "呈現幾筆資料",
     *                          "total": "資料總數",
     *                          "lastPage": "最後頁數",
     *                          "nextUrl": "下一頁網址",
     *                          "prevUrl": "前一頁網址",
     *                          "list": {
     *                              "uuid": "看房紀錄 UUID",
     *                              "founder": "預約者名字",
     *                              "propertyUuid": "租售物件管理 UUID",
     *                              "appointmentTime": "預約時間",
     *                              "appointmentUnixTime": "預約時間-時間戳，主要提供前端，實現更多時間操作",
     *                              "arrivalTime": "抵達時間",
     *                              "arrivalTimeUnixTime": "抵達時間-時間戳，主要提供前端，實現更多時間操作",
     *                              "numberOfVisitors": "訪客人數",
     *                              "visitorsName": "訪客姓名",
     *                              "visitorsCellphone": "訪客手機",
     *                              "alreadyCheckIn": "是否已簽到，false:否，true:是",
     *                              "cancel": "是否已取消，false:否，true:是",
     *                              "signature": {
     *                                  "uuid": "簽名-檔案 UUID",
     *                                  "url": "簽名-圖檔網址",
     *                              },
     *                              "space": {
     *                                  "spaceId": "戶別 ID",
     *                                  "districtName": "區名名稱",
     *                                  "buildingName": "棟別名稱",
     *                                  "staircaseName": "梯間名稱",
     *                                  "floorName": "樓層名稱",
     *                                  "householdName": "戶別名稱",
     *                                  "doorplate": "門牌",
     *                              },
     *                              "realEstateAgent": {
     *                                  "uuid": "房仲-UUID",
     *                                  "name": "房仲-名字",
     *                                  "cellphone": "房仲-手機號碼",
     *                                  "contactNumbers": "房仲-聯絡電話",
     *                                  "companyName": "房仲-公司名稱"
     *                              }
     *                          },
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

    /**
     * @OA\Get(
     *      path="/visit/reserve/{visitReserveUuid}",
     *      operationId="FetchAloneVisitReserve",
     *      tags={"Visit Reserve 預約看房"},
     *      summary="取得單筆預約看房紀錄",
     *      description="取得單筆預約看房紀錄",
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
     *                          "page": "當前頁",
     *                          "perPage": "呈現幾筆資料",
     *                          "total": "資料總數",
     *                          "lastPage": "最後頁數",
     *                          "nextUrl": "下一頁網址",
     *                          "prevUrl": "前一頁網址",
     *                          "list": {
     *                              "uuid": "看房紀錄 UUID",
     *                              "founder": "預約者名字",
     *                              "propertyUuid": "租售物件管理 UUID",
     *                              "appointmentTime": "預約時間",
     *                              "appointmentUnixTime": "預約時間-時間戳，主要提供前端，實現更多時間操作",
     *                              "arrivalTime": "抵達時間",
     *                              "arrivalTimeUnixTime": "抵達時間-時間戳，主要提供前端，實現更多時間操作",
     *                              "numberOfVisitors": "訪客人數",
     *                              "visitorsName": "訪客姓名",
     *                              "visitorsCellphone": "訪客手機",
     *                              "alreadyCheckIn": "是否已簽到，false:否，true:是",
     *                              "cancel": "是否已取消，false:否，true:是",
     *                              "signature": {
     *                                  "uuid": "簽名-檔案 UUID",
     *                                  "url": "簽名-圖檔網址",
     *                              },
     *                              "space": {
     *                                  "spaceId": "戶別 ID",
     *                                  "districtName": "區名名稱",
     *                                  "buildingName": "棟別名稱",
     *                                  "staircaseName": "梯間名稱",
     *                                  "floorName": "樓層名稱",
     *                                  "householdName": "戶別名稱",
     *                                  "doorplate": "門牌",
     *                              },
     *                              "realEstateAgent": {
     *                                  "uuid": "房仲-UUID",
     *                                  "name": "房仲-名字",
     *                                  "cellphone": "房仲-手機號碼",
     *                                  "contactNumbers": "房仲-聯絡電話",
     *                                  "companyName": "房仲-公司名稱"
     *                              }
     *                          },
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
    public function show() {}

    /**
     * @OA\Post(
     *      path="/visit/reserve",
     *      operationId="CreateVisitReserve",
     *      tags={"Visit Reserve 預約看房"},
     *      summary="建立單筆預約看房",
     *      description="建立單筆預約看房",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"propertyUuid", "realEstateAgentUuid", "appointmentTime", "visitorsName", "visitorsCellphone"},
     *                  @OA\Property(property="propertyUuid", type="string", format="uuid", default="", description="租售物件 UUID"),
     *                  @OA\Property(property="realEstateAgentUuid", type="string", format="uuid", default="", description="帶看房仲 UUID"),
     *                  @OA\Property(property="appointmentTime", type="string", format="string", default="", description="預約時間，格式為 Y-m-d H:i:s"),
     *                  @OA\Property(property="numberOfVisitors", type="integer", format="integer", default="", description="訪客人數，最小值：0，最大值：65000"),
     *                  @OA\Property(property="visitorsName", type="string", format="string", default="", description="訪客姓名，最大字元：50"),
     *                  @OA\Property(property="visitorsCellphone", type="string", format="string", default="", description="訪客手機，最小字元：10，最大字元：15"),
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
     *      path="/visit/reserve/{visitReserveUuid}/check-in",
     *      operationId="VisitReserveCheckIn",
     *      tags={"Visit Reserve 預約看房"},
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

    /**
     * @OA\Patch(
     *      path="/visit/reserve/{visitReserveUuid}/cancel",
     *      operationId="VisitReserveCancel",
     *      tags={"Visit Reserve 預約看房"},
     *      summary="取消單筆預約看房",
     *      description="取消單筆預約看房",
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
     *      @OA\Response(
     *          response=200,
     *          description="取消成功"
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
    public function cancel() {}
}
