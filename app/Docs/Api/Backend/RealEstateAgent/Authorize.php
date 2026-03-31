<?php

namespace App\Docs\Api\Backend\RealEstateAgent;

class Authorize
{
    /**
     * @OA\Get(
     *      path="/real-estate-agent/authorize",
     *      operationId="AllRealEstateAgentAuthorize",
     *      tags={"Real-Estate-Agent Authorize 房仲仲介授權"},
     *      summary="取得全部房仲授權資料",
     *      description="取得全部房仲授權資料",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(name="page", description="頁數", required=false, in="query", @OA\Schema(type="integer", format="integer")),
     *      @OA\Parameter(name="perPage", description="呈現幾筆資料，如果無填寫，呈現 10 筆資料", required=false, in="query", @OA\Schema(type="integer", format="integer")),
     *      @OA\Parameter(
     *          name="account",
     *          description="搜尋使用者帳號",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          description="搜尋使用者名稱",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="companyName",
     *          description="搜尋公司名稱",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="cellphone",
     *          description="搜尋手機號碼",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="contactNumbers",
     *          description="搜尋聯絡電話",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="電子信箱",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="email",
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
     *                              {
     *                                  "uuid": "房仲授權 UUID",
     *                                  "realEstateAgent": {
     *                                      "uuid": "房仲 UUID",
     *                                      "account": "帳號",
     *                                      "identificationCode": "房仲識別碼",
     *                                      "name": "名字",
     *                                      "sex": "性別，man、female、other",
     *                                      "birthday": "生日",
     *                                      "nationalIdNumber": "身分證字號",
     *                                      "cellphoneAreaCode": "手機號碼-區碼",
     *                                      "cellphone": "手機號碼",
     *                                      "contactNumbersAreaCode": "聯絡電話-區碼",
     *                                      "contactNumbers": "聯絡電話",
     *                                      "email": "電子信箱",
     *                                      "companyCellphoneAreaCode": "公司電話-區碼",
     *                                      "companyCellphone": "公司電話",
     *                                      "companyName": "公司名稱",
     *                                      "companyBranchName": "公司分店名稱",
     *                                      "companyAddress": "公司地址",
     *                                      "companyUrl": "公司網址",
     *                                      "verifyState": "密碼狀態-啟用狀態 0:未啟用, 1:啟用",
     *                                      "avatar": {
     *                                          "fileUuid": "頭像-檔案 UUID",
     *                                          "url": "頭像-網址",
     *                                      },
     *                                      "entrust": {
     *                                          {
     *                                              "startTime": "委託-開始時間",
     *                                              "endTime": "委託-結束時間",
     *                                              "communityName": "委託-建案名字",
     *                                              "householdName": "委託-戶別名稱",
     *                                              "districtName": "委託-區名名稱",
     *                                              "buildingName": "委託-棟別名稱",
     *                                              "staircaseName": "委託-梯間名稱",
     *                                              "floorName": "委託-樓層名稱"
     *                                          }
     *                                      }
     *                                  }
     *                              }
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
     * @OA\Get(
     *      path="/real-estate-agent/authorize/{authorizeUuid}",
     *      operationId="AloneRealEstateAgentAuthorize",
     *      tags={"Real-Estate-Agent Authorize 房仲仲介授權"},
     *      summary="取得單筆房仲授權資料",
     *      description="取得單筆房仲授權資料",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="authorizeUuid",
     *          description="房仲授權 UUID",
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
     *                              {
     *                                  "uuid": "房仲授權 UUID",
     *                                  "realEstateAgent": {
     *                                      "uuid": "房仲 UUID",
     *                                      "account": "帳號",
     *                                      "identificationCode": "房仲識別碼",
     *                                      "name": "名字",
     *                                      "sex": "性別，man、female、other",
     *                                      "identificationCode": "識別碼",
     *                                      "birthday": "生日",
     *                                      "nationalIdNumber": "身分證字號",
     *                                      "cellphoneAreaCode": "手機號碼-區碼",
     *                                      "cellphone": "手機號碼",
     *                                      "contactNumbersAreaCode": "聯絡電話-區碼",
     *                                      "contactNumbers": "聯絡電話",
     *                                      "email": "電子信箱",
     *                                      "companyCellphoneAreaCode": "公司電話-區碼",
     *                                      "companyCellphone": "公司電話",
     *                                      "companyName": "公司名稱",
     *                                      "companyBranchName": "公司分店名稱",
     *                                      "companyAddress": "公司地址",
     *                                      "companyUrl": "公司網址",
     *                                      "verifyState": "密碼狀態-啟用狀態 0:未啟用, 1:啟用",
     *                                      "avatar": {
     *                                          "fileUuid": "頭像-檔案 UUID",
     *                                          "url": "頭像-網址",
     *                                      },
     *                                      "entrust": {
     *                                          {
     *                                              "startTime": "委託-開始時間",
     *                                              "endTime": "委託-結束時間",
     *                                              "communityName": "委託-建案名字",
     *                                              "householdName": "委託-戶別名稱",
     *                                              "districtName": "委託-區名名稱",
     *                                              "buildingName": "委託-棟別名稱",
     *                                              "staircaseName": "委託-梯間名稱",
     *                                              "floorName": "委託-樓層名稱"
     *                                          }
     *                                      }
     *                                  }
     *                              }
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
     * @OA\Post(
     *      path="/real-estate-agent/authorize",
     *      operationId="CreateRealEstateAgentAuthorize",
     *      tags={"Real-Estate-Agent Authorize 房仲仲介授權"},
     *      summary="建立單筆房仲授權",
     *      description="建立單筆房仲授權",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"identificationCode"},
     *                  @OA\Property(property="identificationCode", type="string", format="string", default="", description="房仲識別碼"),
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
     * @OA\Delete(
     *      path="/real-estate-agent/authorize/{authorizeUuid}",
     *      operationId="DeleteAloneRealEstateAgentAuthorize",
     *      tags={"Real-Estate-Agent Authorize 房仲仲介授權"},
     *      summary="刪除單筆房仲授權資料",
     *      description="刪除單筆房仲授權資料",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="authorizeUuid",
     *          description="房仲授權 UUID",
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
    public function destroy() {}

    /**
     * @OA\Post(
     *      path="/real-estate-agent/authorize/multiple/delete",
     *      operationId="MultipleDeleteRealEstateAgentAuthorize",
     *      tags={"Real-Estate-Agent Authorize 房仲仲介授權"},
     *      summary="批量刪除房仲授權",
     *      description="批量刪除房仲授權",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"uuids"},
     *                  @OA\Property(property="uuids[0]", type="string", format="uuid", default="", description="房仲授權 UUID，如果有多筆，後端預期收到的結果大概長這樣，[uuid1, uuid2]"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="刪除成功"
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
    public function multipleDelete() {}
}
