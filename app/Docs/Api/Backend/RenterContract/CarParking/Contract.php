<?php

namespace App\Docs\Api\Backend\RenterContract\CarParking;

class Contract
{
    /**
     * @OA\Get(
     *      path="/renter/car/parking/{carParkingId}/contract",
     *      operationId="AllCarParkingContract",
     *      tags={"Renter Contract 車位合約"},
     *      summary="取得全部車位合約資料",
     *      description="取得全部車位合約資料",
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
     *                          "uuid": "合約 UUID",
     *                          "name": "承租人名字",
     *                          "cellphone": "手機號碼",
     *                          "birthday": "生日",
     *                          "remark": "合約備註",
     *                          "parkingNumber": "車位編號",
     *                          "application": "車位法定名稱",
     *                          "nationalIdNumber": "身分證字號",
     *                          "startTime": "合約開始日期",
     *                          "endTime": "合約結束日期",
     *                          "startTimeUnixTime": "合約開始日期-時間戳",
     *                          "endTimeUnixTime": "合約結束日期-時間戳",
     *                          "allowDeclare": "是否同意承租人申報 0:否,1:是",
     *                          "allowEarlyTermination": "允許提前終止合約 0:否,1:是",
     *                          "allowSublease": "允許轉租 0:否,1:是",
     *                          "signature": {
     *                              "uuid": "簽名檔案 UUID",
     *                              "url": "簽名圖檔 URL",
     *                          },
     *                          "fees": {
     *                              "price": "合約金額",
     *                              "deposit": "押金",
     *                              "depositTotalMonth": "押金月份，如果為 0，代表選擇了固定金額"
     *                          },
     *                          "paymentCycle": {
     *                              "type": "週期類型：weekly 每週、monthly 每月、yearly 每年",
     *                              "month": "月份",
     *                              "dayOfWeek": "每週-星期",
     *                              "dayOfMonth": "每月-日",
     *                          },
     *                          "person": {
     *                              {
     *                                  "uuid": "人員 UUID",
     *                                  "type": "人員類型：surety 保證人、housemate 同住人",
     *                                  "name": "名字",
     *                                  "cellphone": "電話號碼",
     *                                  "birthday": "生日",
     *                                  "nationalIdNumber": "身分證字號",
     *                              }
     *                          },
     *                          "notify": {
     *                              {
     *                                  "uuid": "提醒 UUID",
     *                                  "type": "類型: customization 自行輸入、monthly 每一個月、beforeEnd 合約結束前一個月",
     *                                  "triggerUnixTime": "觸發時間 (時間戳)"
     *                              }
     *                          },
     *                          "document": {
     *                              {
     *                                  "uuid": "檔案 UUID",
     *                                  "extension": "檔案副檔名",
     *                                  "fileOriginalName": "檔案原始名字",
     *                                  "fileName": "檔案名字",
     *                                  "mimeType": "mime type",
     *                                  "url": "檔案 URL",
     *                              }
     *                          },
     *                          "terminationState": "終止狀態 0:未終止,1:已終止，如果合約到期也會是 1",
     *                          "terminationReason": "終止合約原因",
     *                          "terminationDate": "終止日期，使用按鈕進行終止才會有此項",
     *                          "terminationUnixTime": "終止時間戳，使用按鈕進行終止才會有此項，主要提供前端，實現更多時間操作",
     *                          "bill": {
     *                              {
     *                                  "uuid": "帳單 UUID",
     *                                  "paid": "已繳款 0:否,1:是",
     *                                  "startTime": "帳單開始日期",
     *                                  "endTime": "帳單結束日期",
     *                                  "createAt": "帳單建立時間",
     *                                  "createAtUnixTime": "帳單建立時間戳，主要提供前端，實現更多時間操作",
     *                                  "includeTax": "是否含稅 0:否,1:是",
     *                                  "amount": {
     *                                      {
     *                                          "price": "金額",
     *                                          "customization": "是否為自訂 0:否,1:是",
     *                                          "lineItem": "帳單項目名稱：contractPrice 房屋租金、carparkPrice 車位金額、managementFee 管理費"
     *                                      }
     *                                  },
     *                              }
     *                          },
     *                          "fromMutual": "是否為共同合約，false 否, true 是",
     *                          "mutualSourceContract": {
     *                              "uuid": "共同合約來源-合約 UUID",
     *                              "spaceId": "共同合約來源-戶別 ID"
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

    /**
     * @OA\Post(
     *      path="/renter/car/parking/{carParkingId}/contract",
     *      operationId="CreateCarParkingContract",
     *      tags={"Renter Contract 車位合約"},
     *      summary="建立單筆車位合約資料",
     *      description="建立單筆車位合約資料",
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
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"name", "nationalIdNumber", "cellphone", "birthday", "startTime", "endTime", "allowDeclare", "allowEarlyTermination", "allowSublease", "paymentCycle[type]", "fees[price]", "signature", "cacheState"},
     *                  @OA\Property(property="name", format="string", default="", description="承租人名字，最大字元：255"),
     *                  @OA\Property(property="nationalIdNumber", format="string", default="", description="身分證字號，最大字元：10，有基本的驗證"),
     *                  @OA\Property(property="cellphone", format="string", default="", description="手機號碼，最大字元：15"),
     *                  @OA\Property(property="birthday", format="date", default="", description="生日，格式為：Y-m-d"),
     *                  @OA\Property(property="startTime", format="date", default="", description="合約開始時間，格式為：Y-m-d"),
     *                  @OA\Property(property="endTime", format="date", default="", description="合約結束時間，格式為：Y-m-d"),
     *                  @OA\Property(property="allowDeclare", type="integer", format="integer", default="", description="是否同意承租人申報 0:否,1:是"),
     *                  @OA\Property(property="allowEarlyTermination", format="integer", default="", description="允許提前終止合約 0:否,1:是"),
     *                  @OA\Property(property="allowSublease", format="integer", default="", description="允許轉租 0:否,1:是"),
     *                  @OA\Property(property="remark", format="string", default="", description="合約備註"),
     *                  @OA\Property(property="document[0]", format="string", default="", description="檔案 UUID，如果有多個檔案。那麼後端接收到的結果大概會長這樣 ['uuid-1', 'uuid-2', 'uuid-3']"),
     *                  @OA\Property(property="associatedPersons[0][type]", format="string", default="", description="合約相關人員類型：surety 保證人"),
     *                  @OA\Property(property="associatedPersons[0][name]", format="string", default="", description="合約相關人員名字，最大字元：255"),
     *                  @OA\Property(property="associatedPersons[0][nationalIdNumber]", format="string", default="", description="合約相關人員身分證字號"),
     *                  @OA\Property(property="associatedPersons[0][cellphone]", format="string", default="", description="合約相關人員電話，最大字元：15"),
     *                  @OA\Property(property="associatedPersons[0][birthday]", format="string", default="", description="合約相關人員生日，格式為：Y-m-d"),
     *                  @OA\Property(property="paymentCycle[type]", format="string", default="", description="繳費週期-類型：weekly 每週、monthly 每月、yearly 每年"),
     *                  @OA\Property(property="paymentCycle[month]", format="integer", default="", description="繳費週期-月份，如果 type 為 yearly 此項必填，其他情況可以不傳此項，最小值：1，最大值:12"),
     *                  @OA\Property(property="paymentCycle[dayOfWeek]", format="integer", default="", description="繳費週期-星期，1:星期一, 2:星期二...., 0:星期日，如果 type 為 weekly 此項必填，其他情況可以不傳此項"),
     *                  @OA\Property(property="paymentCycle[dayOfMonth]", format="integer", default="", description="繳費週期-日，如果 type 為 monthly 或 yearly 此項必填，最小值：1，最大值：31，其他情況可以不傳此項"),
     *                  @OA\Property(property="notify[0][type]", format="string", default="", description="通知 (提醒、推播，一堆不同名字...) 類型: customization 自行輸入、monthly 每一個月、beforeEnd 合約結束前一個月"),
     *                  @OA\Property(property="notify[0][triggerTime]", format="string", default="", description="通知 (提醒、推播) 時間，格式為：Y-m-d H:i，如果 type 為 customization 此項必填，其他情況可以不傳此項"),
     *                  @OA\Property(property="fees[price]", format="number", default="", description="租金費用 (合約金額)，最小值：-100000000, 最大值：100000000"),
     *                  @OA\Property(property="fees[deposit]", format="number", default="", description="押金，預想情況是如果押金為固定金額此項必填，最小值：-100000000, 最大值：100000000，其他情況可以不填此項"),
     *                  @OA\Property(property="fees[depositTotalMonth]", format="integer", default="", description="押金月份，最小值：1，最大值：12，預想情況是如果押金為每月租金此項必填，其他情況可以不填此項"),
     *                  @OA\Property(property="bank[type]", type="string", format="string", default="", description="銀行類型，請填寫：virtual (虛擬帳戶)、entity (實體帳戶)"),
     *                  @OA\Property(property="bank[code]", type="string", format="string", default="", description="銀行代碼，如果 bank[type] 為 entity，此項必填"),
     *                  @OA\Property(property="bank[account]", type="string", format="string", default="", description="銀行帳號，如果 bank[type] 為 entity，此項必填"),
     *                  @OA\Property(property="signature", type="string", format="string", default="", description="簽名檔案 UUID 或是 base64 圖片"),
     *                  @OA\Property(property="cacheState", type="string", format="string", default="", description="暫存狀態 1 要暫存 0 不暫存"),
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
     * @OA\Get(
     *      path="/renter/car/parking/{carParkingId}/contract/{uuid}",
     *      operationId="AloneCarParkingContract",
     *      tags={"Renter Contract 車位合約"},
     *      summary="取得單筆車位合約資料",
     *      description="取得單筆車位合約資料",
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
     *          name="uuid",
     *          description="合約 UUID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *               format="uuid"
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
     *                          "uuid": "合約 UUID",
     *                          "name": "承租人名字",
     *                          "cellphone": "手機號碼",
     *                          "birthday": "生日",
     *                          "remark": "合約備註",
     *                          "parkingNumber": "車位編號",
     *                          "application": "車位法定名稱",
     *                          "nationalIdNumber": "身分證字號",
     *                          "startTime": "合約開始日期",
     *                          "endTime": "合約結束日期",
     *                          "startTimeUnixTime": "合約開始日期-時間戳",
     *                          "endTimeUnixTime": "合約結束日期-時間戳",
     *                          "allowDeclare": "是否同意承租人申報 0:否,1:是",
     *                          "allowEarlyTermination": "允許提前終止合約 0:否,1:是",
     *                          "allowSublease": "允許轉租 0:否,1:是",
     *                          "signature": {
     *                              "uuid": "簽名檔案 UUID",
     *                              "url": "簽名圖檔 URL",
     *                          },
     *                          "fees": {
     *                              "price": "合約金額",
     *                              "deposit": "押金",
     *                              "depositTotalMonth": "押金月份，如果為 0，代表選擇了固定金額"
     *                          },
     *                          "paymentCycle": {
     *                              "type": "週期類型：weekly 每週、monthly 每月、yearly 每年",
     *                              "month": "月份",
     *                              "dayOfWeek": "每週-星期",
     *                              "dayOfMonth": "每月-日",
     *                          },
     *                          "person": {
     *                              {
     *                                  "uuid": "人員 UUID",
     *                                  "type": "人員類型：surety 保證人、housemate 同住人",
     *                                  "name": "名字",
     *                                  "cellphone": "電話號碼",
     *                                  "birthday": "生日",
     *                                  "nationalIdNumber": "身分證字號",
     *                              }
     *                          },
     *                          "notify": {
     *                              {
     *                                  "uuid": "提醒 UUID",
     *                                  "type": "類型: customization 自行輸入、monthly 每一個月、beforeEnd 合約結束前一個月",
     *                                  "triggerUnixTime": "觸發時間 (時間戳)"
     *                              }
     *                          },
     *                          "document": {
     *                              {
     *                                  "uuid": "檔案 UUID",
     *                                  "extension": "檔案副檔名",
     *                                  "fileOriginalName": "檔案原始名字",
     *                                  "fileName": "檔案名字",
     *                                  "mimeType": "mime type",
     *                                  "url": "檔案 URL",
     *                              }
     *                          },
     *                          "terminationState": "終止狀態 0:未終止,1:已終止，如果合約到期也會是 1",
     *                          "terminationReason": "終止合約原因",
     *                          "terminationDate": "終止日期，使用按鈕進行終止才會有此項",
     *                          "terminationUnixTime": "終止時間戳，使用按鈕進行終止才會有此項，主要提供前端，實現更多時間操作",
     *                          "bill": {
     *                              {
     *                                  "uuid": "帳單 UUID",
     *                                  "paid": "已繳款 0:否,1:是",
     *                                  "startTime": "帳單開始日期",
     *                                  "endTime": "帳單結束日期",
     *                                  "createAt": "帳單建立時間",
     *                                  "createAtUnixTime": "帳單建立時間戳，主要提供前端，實現更多時間操作",
     *                                  "includeTax": "是否含稅 0:否,1:是",
     *                                  "amount": {
     *                                      {
     *                                          "price": "金額",
     *                                          "customization": "是否為自訂 0:否,1:是",
     *                                          "lineItem": "帳單項目名稱：contractPrice 房屋租金、carparkPrice 車位金額、managementFee 管理費"
     *                                      }
     *                                  },
     *                              }
     *                          },
     *                          "fromMutual": "是否為共同合約，false 否, true 是",
     *                          "mutualSourceContract": {
     *                              "uuid": "共同合約來源-合約 UUID",
     *                              "spaceId": "共同合約來源-戶別 ID"
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
    public function show() {}

    /**
     * @OA\Patch(
     *      path="/renter/car/parking/{carParkingId}/contract/{uuid}",
     *      operationId="UpdateCarParkingContract",
     *      tags={"Renter Contract 車位合約"},
     *      summary="修改單筆車位合約資料",
     *      description="修改單筆車位合約資料",
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
     *                  required={"name", "nationalIdNumber", "cellphone", "birthday", "startTime", "endTime", "allowDeclare", "allowEarlyTermination", "allowSublease", "paymentCycle[type]", "fees[price]", "cacheState"},
     *                  @OA\Property(property="name", format="string", default="", description="承租人名字，最大字元：255"),
     *                  @OA\Property(property="nationalIdNumber", format="string", default="", description="身分證字號，最大字元：10，有基本的驗證"),
     *                  @OA\Property(property="cellphone", format="string", default="", description="手機號碼，最大字元：15"),
     *                  @OA\Property(property="birthday", format="date", default="", description="生日，格式為：Y-m-d"),
     *                  @OA\Property(property="startTime", format="date", default="", description="合約開始時間，格式為：Y-m-d"),
     *                  @OA\Property(property="endTime", format="date", default="", description="合約結束時間，格式為：Y-m-d"),
     *                  @OA\Property(property="allowDeclare", type="integer", format="integer", default="", description="是否同意承租人申報 0:否,1:是"),
     *                  @OA\Property(property="allowEarlyTermination", format="integer", default="", description="允許提前終止合約 0:否,1:是"),
     *                  @OA\Property(property="allowSublease", format="integer", default="", description="允許轉租 0:否,1:是"),
     *                  @OA\Property(property="remark", format="string", default="", description="合約備註"),
     *                  @OA\Property(property="document[0]", format="string", default="", description="檔案 UUID，如果有多個檔案。那麼後端接收到的結果大概會長這樣 ['uuid-1', 'uuid-2', 'uuid-3']"),
     *                  @OA\Property(property="associatedPersons[0][type]", format="string", default="", description="合約相關人員類型：surety 保證人"),
     *                  @OA\Property(property="associatedPersons[0][name]", format="string", default="", description="合約相關人員名字，最大字元：255"),
     *                  @OA\Property(property="associatedPersons[0][nationalIdNumber]", format="string", default="", description="合約相關人員身分證字號"),
     *                  @OA\Property(property="associatedPersons[0][cellphone]", format="string", default="", description="合約相關人員電話，最大字元：15"),
     *                  @OA\Property(property="associatedPersons[0][birthday]", format="string", default="", description="合約相關人員生日，格式為：Y-m-d"),
     *                  @OA\Property(property="paymentCycle[type]", format="string", default="", description="繳費週期-類型：weekly 每週、monthly 每月、yearly 每年"),
     *                  @OA\Property(property="paymentCycle[month]", format="integer", default="", description="繳費週期-月份，如果 type 為 yearly 此項必填，其他情況可以不傳此項，最小值：1，最大值:12"),
     *                  @OA\Property(property="paymentCycle[dayOfWeek]", format="integer", default="", description="繳費週期-星期，1:星期一, 2:星期二...., 0:星期日，如果 type 為 weekly 此項必填，其他情況可以不傳此項"),
     *                  @OA\Property(property="paymentCycle[dayOfMonth]", format="integer", default="", description="繳費週期-日，如果 type 為 monthly 或 yearly 此項必填，最小值：1，最大值：31，其他情況可以不傳此項"),
     *                  @OA\Property(property="notify[0][type]", format="string", default="", description="通知 (提醒、推播，一堆不同名字...) 類型: customization 自行輸入、monthly 每一個月、beforeEnd 合約結束前一個月"),
     *                  @OA\Property(property="notify[0][triggerTime]", format="string", default="", description="通知 (提醒、推播) 時間，格式為：Y-m-d H:i，如果 type 為 customization 此項必填，其他情況可以不傳此項"),
     *                  @OA\Property(property="fees[price]", format="number", default="", description="租金費用 (合約金額)，最小值：-100000000, 最大值：100000000"),
     *                  @OA\Property(property="fees[deposit]", format="number", default="", description="押金，預想情況是如果押金為固定金額此項必填，最小值：-100000000, 最大值：100000000，其他情況可以不填此項"),
     *                  @OA\Property(property="fees[depositTotalMonth]", format="integer", default="", description="押金月份，最小值：1，最大值：12，預想情況是如果押金為每月租金此項必填，其他情況可以不填此項"),
     *                  @OA\Property(property="bank[type]", type="string", format="string", default="", description="銀行類型，請填寫：virtual (虛擬帳戶)、entity (實體帳戶)"),
     *                  @OA\Property(property="bank[code]", type="string", format="string", default="", description="銀行代碼，如果 bank[type] 為 entity，此項必填"),
     *                  @OA\Property(property="bank[account]", type="string", format="string", default="", description="銀行帳號，如果 bank[type] 為 entity，此項必填"),
     *                  @OA\Property(property="cacheState", type="string", format="string", default="", description="暫存狀態 1 要暫存 0 不暫存"),
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
     *      path="/renter/car/parking/{carParkingId}/contract/{uuid}",
     *      operationId="DeleteCarParkingContract",
     *      tags={"Renter Contract 車位合約"},
     *      summary="刪除單筆車位合約資料",
     *      description="刪除單筆車位合約資料",
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
     *          name="uuid",
     *          description="合約 UUID",
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
