<?php

namespace App\Docs\Api\Frontend\RealEstateAgent;

class RealEstateAgent
{
    /**
     * @OA\Post(
     *      path="/frontend/real-estate-agent",
     *      operationId="CreateRealEstateAgent",
     *      tags={"Real-Estate-Agent 房仲列表 (前台)"},
     *      summary="建立單筆房仲",
     *      description="建立單筆房仲",
     *      security={{"Authorization":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"account", "name", "sex", "nationalIdNumber", "cellphoneAreaCode", "cellphone", "contactNumbersAreaCode", "email", "companyCellphoneAreaCode", "password", "password_confirmation"},
     *                  @OA\Property(property="account", type="string", format="string", default="", description="帳號，最大字元：255"),
     *                  @OA\Property(property="avatar", type="string", format="uuid", default="", description="頭像，檔案 UUID，配合檔案上傳 API 使用"),
     *                  @OA\Property(property="name", type="string", format="string", default="", description="名字，最大字元：255"),
     *                  @OA\Property(property="sex", type="string", format="string", default="", description="請輸入 man、female、other，最大字元：255"),
     *                  @OA\Property(property="birthday", type="string", format="date", default="", description="生日，格式為：Y-m-d"),
     *                  @OA\Property(property="nationalIdNumber", type="string", format="string", default="", description="身分證字號，最大字元：10，有基本的驗證，一個身分證字號只能有一組帳號"),
     *                  @OA\Property(property="cellphoneAreaCode", type="string", format="string", default="TW", description="手機號碼-區碼，最大字元：6"),
     *                  @OA\Property(property="cellphone", type="string", format="string", default="", description="手機號碼，最大字元：15"),
     *                  @OA\Property(property="contactNumbersAreaCode", type="string", format="string", default="TW", description="聯絡電話-區碼，最大字元：6"),
     *                  @OA\Property(property="contactNumbers", type="string", format="string", default="", description="聯絡電話，最大字元：15"),
     *                  @OA\Property(property="email", type="string", format="email", default="", description="電子信箱，最大字元：255"),
     *                  @OA\Property(property="companyCellphoneAreaCode", type="string", format="string", default="TW", description="公司電話-區碼，最大字元：6"),
     *                  @OA\Property(property="companyCellphone", type="string", format="string", default="", description="公司電話，最大字元：15"),
     *                  @OA\Property(property="companyName", type="string", format="string", default="", description="公司名稱，最大字元：255"),
     *                  @OA\Property(property="companyBranchName", type="string", format="string", default="", description="公司分店名稱，最大字元：255"),
     *                  @OA\Property(property="companyAddress", type="string", format="string", default="", description="公司地址，最大字元：255"),
     *                  @OA\Property(property="companyUrl", type="string", format="uri", default="", description="公司網址，沒有最大上限"),
     *                  @OA\Property(property="password", type="string", format="string", default="", description="密碼，至少需要 6 字元"),
     *                  @OA\Property(property="password_confirmation", type="string", format="string", default="", description="確認密碼")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 201,
     *                      "message": "建立成功",
     *                      "data": {
     *                         "uuid": "房仲 UUID"
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
    public function store() {}

    /**
     * @OA\Get(
     *      path="/frontend/real-estate-agent/{uuid}",
     *      operationId="AloneRealEstateAgent",
     *      tags={"Real-Estate-Agent 房仲列表 (前台)"},
     *      summary="取得單筆房仲資料",
     *      description="取得單筆房仲資料",
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
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "取得成功",
     *                      "data": {
     *                         "uuid": "房仲 UUID",
     *                         "account": "帳號",
     *                         "identificationCode": "房仲識別碼",
     *                         "name": "名字",
     *                         "sex": "性別，man、female、other",
     *                         "birthday": "生日",
     *                         "nationalIdNumber": "身分證字號",
     *                         "cellphoneAreaCode": "手機號碼-區碼",
     *                         "cellphone": "手機號碼",
     *                         "contactNumbersAreaCode": "聯絡電話-區碼",
     *                         "contactNumbers": "聯絡電話",
     *                         "email": "電子信箱",
     *                         "companyCellphoneAreaCode": "公司電話-區碼",
     *                         "companyCellphone": "公司電話",
     *                         "companyName": "公司名稱",
     *                         "companyBranchName": "公司分店名稱",
     *                         "companyAddress": "公司地址",
     *                         "companyUrl": "公司網址",
     *                         "verifyState": "密碼狀態-啟用狀態 0:未啟用, 1:啟用",
     *                         "avatar": {
     *                              "fileUuid": "頭像-檔案 UUID",
     *                              "url": "頭像-網址",
     *                         },
     *                         "entrust": {
     *                              {
     *                                  "startTime": "委託-開始時間",
     *                                  "endTime": "委託-結束時間",
     *                                  "communityName": "委託-建案名字",
     *                                  "householdName": "委託-戶別名稱",
     *                                  "districtName": "委託-區名名稱",
     *                                  "buildingName": "委託-棟別名稱",
     *                                  "staircaseName": "委託-梯間名稱",
     *                                  "floorName": "委託-樓層名稱"
     *                              }
     *                         }
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
     *      path="/frontend/real-estate-agent/{uuid}",
     *      operationId="UpdateRealEstateAgent",
     *      tags={"Real-Estate-Agent 房仲列表 (前台)"},
     *      summary="修改單筆房仲資料",
     *      description="修改單筆房仲資料",
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
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"name", "sex", "cellphoneAreaCode", "cellphone", "contactNumbersAreaCode", "email", "companyCellphoneAreaCode"},
     *                  @OA\Property(property="avatar", type="string", format="uuid", default="", description="頭像，檔案 UUID，配合檔案上傳 API 使用"),
     *                  @OA\Property(property="name", type="string", format="string", default="", description="名字，最大字元：255"),
     *                  @OA\Property(property="sex", type="string", format="string", default="", description="請輸入 man、female、other，最大字元：255"),
     *                  @OA\Property(property="birthday", type="string", format="date", default="", description="生日，格式為：Y-m-d"),
     *                  @OA\Property(property="cellphoneAreaCode", type="string", format="string", default="TW", description="手機號碼-區碼，最大字元：6"),
     *                  @OA\Property(property="cellphone", type="string", format="string", default="", description="手機號碼，最大字元：15"),
     *                  @OA\Property(property="contactNumbersAreaCode", type="string", format="string", default="TW", description="聯絡電話-區碼，最大字元：6"),
     *                  @OA\Property(property="contactNumbers", type="string", format="string", default="", description="聯絡電話，最大字元：15"),
     *                  @OA\Property(property="email", type="string", format="email", default="", description="電子信箱，最大字元：255"),
     *                  @OA\Property(property="companyCellphoneAreaCode", type="string", format="string", default="TW", description="公司電話-區碼，最大字元：6"),
     *                  @OA\Property(property="companyCellphone", type="string", format="string", default="", description="公司電話，最大字元：15"),
     *                  @OA\Property(property="companyName", type="string", format="string", default="", description="公司名稱，最大字元：255"),
     *                  @OA\Property(property="companyBranchName", type="string", format="string", default="", description="公司分店名稱，最大字元：255"),
     *                  @OA\Property(property="companyAddress", type="string", format="string", default="", description="公司地址，最大字元：255"),
     *                  @OA\Property(property="companyUrl", type="string", format="uri", default="", description="公司網址，沒有最大上限")
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
}
