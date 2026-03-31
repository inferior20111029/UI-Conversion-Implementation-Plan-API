<?php

namespace App\Docs\Api\Backend\RealEstateAgent;

class Entrust
{
    /**
     * @OA\Get(
     *      path="/real-estate-agent/entrust/{spaceId}",
     *      operationId="AllRealEstateAgentEntrust",
     *      tags={"Real-Estate-Agent Entrust 房仲委託"},
     *      summary="取得全部房仲委託資料",
     *      description="取得全部房仲委託資料，請填寫 Community-Id-Header (建案 ID)",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="spaceId",
     *          description="戶別 ID",
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
     *                         "name": "名字",
     *                         "cellphoneAreaCode": "手機號碼-區碼",
     *                         "cellphone": "手機號碼",
     *                         "contactNumbersAreaCode": "聯絡電話-區碼",
     *                         "contactNumbers": "聯絡電話",
     *                         "email": "電子信箱",
     *                         "companyCellphoneAreaCode": "公司電話-區碼",
     *                         "companyCellphone": "公司電話",
     *                         "companyName": "公司名稱",
     *                         "companyAddress": "公司地址",
     *                         "endTime": "委託開始時間",
     *                         "endTime": "委託結束時間",
     *                         "whileSoldOut": "租售出為止 0:否, 1:是",
     *                         "hasEntrust": "是否有進行戶別委託 false：否, true:是",
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
     *      path="/real-estate-agent/entrust/{spaceId}",
     *      operationId="SetRealEstateAgentEntrust",
     *      tags={"Real-Estate-Agent Entrust 房仲委託"},
     *      summary="設定單筆房仲委託",
     *      description="設定單筆房仲委託，請填寫 Community-Id-Header (建案 ID)",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="spaceId",
     *          description="戶別 ID",
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
     *                  required={"realEstateAgentUuid", "entrustState"},
     *                  @OA\Property(property="realEstateAgentUuid", format="string", default="", description="房仲 UUID"),
     *                  @OA\Property(property="startTime", format="date", default="", description="如果 entrustState 為 1，此項必填，開始時間，時間格式為 Y-m-d"),
     *                  @OA\Property(property="endTime", type="string", format="date", default="", description="如果 entrustState 為 1，此項必填，結束時間，時間格式為 Y-m-d，需大於 startTime"),
     *                  @OA\Property(property="whileSoldOut", type="integer", format="integer", default="", description="如果 entrustState 為 1，此項必填，租售出為止 0:否 1:是"),
     *                  @OA\Property(property="entrustState", type="integer", format="integer", default="", description="是否進行委託 0:否, 1:是"),
     *                  @OA\Property(property="file_id", type="string" , default="2b9224e3-3d7a-4b8e-bf8e-93148df74c30", description="委託書"),
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
}
