<?php

namespace App\Docs\Api\Backend\PropertyManage\CarParking;

class Property
{
    /**
     * @OA\Get(
     *      path="/property/manage/list/?type=car",
     *      tags={"Property manage Car 物件資訊列表-車位"},
     *      summary="獲取物件資訊列表",
     *      description="獲取物件資訊列表",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *      @OA\Parameter(
     *          name="perPage",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", default=12)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[title]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="標題過濾", example="宅")
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[type]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="類型過濾", example="rent", enum={"rent", "sell"})
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[district]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="區域過濾")
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[staircase]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="梯過濾")
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[floor]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="樓層過濾")
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[building]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="棟過濾", example="甲")
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[household]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="戶過濾")
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[is_car]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", description="是否有車位", example=1, enum={"0", "1"})
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[enable_state]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", description="上架過濾", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[creator]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string", description="建立者", example="經理")
     *      ),
     *      @OA\Parameter(
     *           name="filter_key[parking_type]",
     *           in="query",
     *           required=false,
     *           @OA\Schema(type="string", description="車位", example="私車位")
     *       ),
     *       @OA\Parameter(
     *           name="filter_key[parking_attribute]",
     *           in="query",
     *           required=false,
     *           @OA\Schema(type="string", description="車位種類", example="坡道機")
     *       ),
     *     @OA\Parameter(
     *            name="filter_key[price]",
     *            in="query",
     *            required=false,
     *            @OA\Schema(type="string", description="價錢", example="10000")
     *        ),
     *        @OA\Parameter(
     *           name="filter_key[car_type]",
     *           in="query",
     *           required=false,
     *           @OA\Schema(type="string", description="車位類型 0汽車 1 機車", example="0")
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="page", type="integer", example=1),
     *                  @OA\Property(property="perPage", type="integer", example=12),
     *                  @OA\Property(property="total", type="integer", example=4),
     *                  @OA\Property(property="lastPage", type="integer", example=1),
     *                  @OA\Property(property="nextUrl", type="string", example=""),
     *                  @OA\Property(property="prevUrl", type="string", example=""),
     *                  @OA\Property(
     *                      property="list",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="id", type="integer", example=195),
     *                          @OA\Property(property="uuid", type="string", example="11f0cc05-62ec-4ab0-ad77-f7767b271177"),
     *                          @OA\Property(property="space_id", type="string", example="0bdb2420-645b-457a-9a85-2515fda4fe80"),
     *                          @OA\Property(property="title", type="string", example="【444社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                          @OA\Property(property="type", type="string", example="rent"),
     *                          @OA\Property(property="creator", type="string", example="經理 陳得盛"),
     *                          @OA\Property(property="price", type="integer", example=20000),
     *                          @OA\Property(property="enable_state", type="boolean", example=true),
     *                          @OA\Property(property="district_name", type="string", example="B"),
     *                          @OA\Property(property="building_name", type="string", example="甲棟"),
     *                          @OA\Property(property="staircase_name", type="string", example=null),
     *                          @OA\Property(property="floor_name", type="string", example="10F"),
     *                          @OA\Property(property="household_name", type="string", example="18B"),
     *                          @OA\Property(property="car_name", type="string", example="車位名稱"),
     *                          @OA\Property(property="parking_type", type="string", example="坡道機械式"),
     *                          @OA\Property(property="parking_attribute", type="string", example="私車位"),
     *                          @OA\Property(property="parking_number", type="string", example="車位號碼(11-AD)"),
     *                          @OA\Property(property="car_type", type="integer", example="0"),
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Get(
     *      path="/property/manage/car/{type}",
     *      tags={"Property manage Car 物件資訊列表-車位"},
     *      summary="獲取車位資訊列表",
     *      description="獲取車位資訊列表",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="type",
     *          in="path",
     *          description="出租類性 rent(租) sell (售)",
     *          @OA\Schema(type="string", description="出租類性 rent(租) sell (售)", example="sell"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="space_id", type="string", example="beebf564-c1dd-4b93-bd0b-e8f9929934bf"),
     *                      @OA\Property(property="district_name", type="string", example="B"),
     *                      @OA\Property(property="building_name", type="string", example="甲棟"),
     *                      @OA\Property(property="staircase_name", type="string", example=null),
     *                      @OA\Property(property="floor_name", type="string", example="10F"),
     *                      @OA\Property(property="household_name", type="string", example="18B"),
     *                      @OA\Property(property="car_type", type="string", example="汽車"),
     *                      @OA\Property(property="parking_number", type="string", example="車位號碼(3A-4)"),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function indexCar()
    {
    }

    /**
     * @OA\Post(
     *      path="/property/manage/car/parking/{spaceId}",
     *      tags={"Property manage Car 物件資訊列表-車位"},
     *      summary="物件資訊列表 新增物件",
     *      description="物件資訊列表 新增物件",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="spaceId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"fees[price]","title", "type", "state"},
     *                  @OA\Property(property="fees[price]", type="integer", description="租金費用 &　售價", example=20000),
     *                  @OA\Property(property="fees[deposit]", type="integer", description="押金 出售不用傳", example=40000),
     *                  @OA\Property(property="fees[depositTotalMonth]", type="integer", description="押金月份 出售不用傳", example=2),
     *                  @OA\Property(property="checkInInfo[lease_term]", type="integer", description="最短租期 出售不用傳", example=1, enum={"1", "2", "3", "4", "5", "6"}),
     *                  @OA\Property(property="checkInInfo[lease_term_type]", type="string", description="月或年 出售不用傳", enum={"month", "year"}),
     *                  @OA\Property(property="state", type="integer", description="上架狀態", example=1),
     *                  @OA\Property(property="title", type="string", description="物件標題", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                  @OA\Property(property="description", type="string", description="物件描述", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                  @OA\Property(property="document[picture][0][uuid]", type="string", description="圖片 UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                  @OA\Property(property="document[URL]", type="string", description="URL", example="http://192.168.10.105:5173/property_vue/property_information_list/unit-household/0"),
     *                  @OA\Property(property="document[video][uuid]", type="string", description="Video UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                   @OA\Property(property="contactPerson[type]", type="string", description="聯絡人類型 landlord 房東 agent 仲介", example="landlord", enum={"landlord", "agent"}),
     *                   @OA\Property(property="contactPerson[name]", type="string", description="聯絡人姓名", example="li landlord"),
     *                   @OA\Property(property="contactInfo[type][0]", type="integer", description="聯絡方式類型", example=1, enum={"1", "2", "3"}),
     *                   @OA\Property(property="contactInfo[info][0]", type="string", description="聯絡方式資訊", example="0928036789"),
     *                  @OA\Property(property="type", type="string", description="出租/出售", example="rent", enum={"rent", "sell"})
     *              )
     *          )
     *      ),
     *      @OA\Response(response=201, description="建立成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *      path="/property/manage/car/parking/{id}/edit",
     *      tags={"Property manage Car 物件資訊列表-車位"},
     *      summary="取得編輯資料",
     *      description="Retrieve property space details for editing.",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="uuid", type="string", example="0e2362a4-4bee-4523-816f-0fe932389c78"),
     *                  @OA\Property(property="crm_parking_space_id", type="string", example="0bdb2420-645b-457a-9a85-2515fda4fe80"),
     *                  @OA\Property(property="title", type="string", example="【社會住宅1】可租補✔台水台電✔傢俱訂製中【社會住宅】可租補✔台水台電✔傢俱訂製中1"),
     *                  @OA\Property(property="description", type="string", example="【社會住宅1】可租補✔台水台電✔傢俱訂製中"),
     *                  @OA\Property(property="state", type="integer", example=1),
     *                  @OA\Property(property="parking_number", type="string", example="車位號碼(3A-4)"),
     *                  @OA\Property(property="household_name", type="string", example="公設名稱"),
     *                  @OA\Property(
     *                      property="decoration",
     *                      type="object",
     *                      @OA\Property(property="degree", type="string", example="unfinished9"),
     *                      @OA\Property(property="time", type="string", example="withinHalfYear")
     *                  ),
     *                  @OA\Property(
     *                      property="fees",
     *                      type="object",
     *                      @OA\Property(property="price", type="integer", example=200006),
     *                      @OA\Property(property="deposit", type="integer", example=40000),
     *                      @OA\Property(property="deposit_total_month", type="integer", example=2),
     *                      @OA\Property(property="management_fee", type="integer", example=3000)
     *                  ),
     *                  @OA\Property(
     *                      property="checkInInfo",
     *                      type="object",
     *                      @OA\Property(property="date", type="string", format="date", nullable=true),
     *                      @OA\Property(property="lease_term", type="integer", example=1),
     *                      @OA\Property(property="lease_term_type", type="string", example="month")
     *                  ),
     *                  @OA\Property(
     *                      property="contactInfo",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="info", type="string", example="0928036789"),
     *                          @OA\Property(property="type", type="integer", example=1)
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="contactPerson",
     *                      type="object",
     *                      @OA\Property(property="name", type="string", example="li landlord6"),
     *                      @OA\Property(property="type", type="string", example="landlord")
     *                  ),
     *                  @OA\Property(
     *                      property="document",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="uuid", type="string", nullable=true),
     *                          @OA\Property(property="fileOriginalName", type="string", nullable=true),
     *                          @OA\Property(property="file_url", type="string", nullable=true),
     *                          @OA\Property(property="type", type="string", example="URL"),
     *                          @OA\Property(property="url", type="string", nullable=true)
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function edit()
    {
    }

    /**
     * @OA\Patch(
     *     path="/property/manage/car/parking/{UUID}",
     *     tags={"Property manage Car 物件資訊列表-車位"},
     *     summary="物件資訊列表 編輯物件",
     *     description="物件資訊列表 編輯物件",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *          name="UUID",
     *          in="path",
     *          required=true,
     *          description="物件UUID",
     *          @OA\Schema(
     *             type="string",
     *          )
     *        ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"space_id", "fees[price]","title", "type", "state"},
     *                 @OA\Property(property="space_id", type="string", description="空間 id"),
     *                 @OA\Property(property="fees[price]", type="integer", description="租金費用 售價", example=20000),
     *                 @OA\Property(property="fees[deposit]", type="integer", description="押金 出售不用傳", example=40000),
     *                 @OA\Property(property="fees[depositTotalMonth]", type="integer", description="押金月份 出售不用傳", example=2),
     *                 @OA\Property(property="checkInInfo[lease_term]", type="integer", description="最短租期 出售不用傳", example=1, enum={"1", "2", "3", "4","5","6"}),
     *                 @OA\Property(property="checkInInfo[lease_term_type]", type="string", description="月或年 出售不用傳", enum={"month", "year"}),
     *                 @OA\Property(property="state", type="integer", description="上架狀態", example=1),
     *                 @OA\Property(property="title", type="string", description="物件標題", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                 @OA\Property(property="description", type="string", description="物件描述", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                 @OA\Property(property="document[picture][0][uuid]", type="string", description="圖片UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                 @OA\Property(property="document[URL]", type="string", description="URL", example="http://192.168.10.105:5173/property_vue/property_information_list/unit-household/0"),
     *                 @OA\Property(property="document[video][uuid]", type="string", description="Video UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                 @OA\Property(property="contactPerson[type]", type="string", description="聯絡人類型 landlord 房東 agent 仲介", example="landlord", enum={"landlord", "agent"}),
     *                 @OA\Property(property="contactPerson[name]", type="string", description="聯絡人姓名", example="li landlord"),
     *                 @OA\Property(property="contactInfo[type][0]", type="integer", description="聯絡方式類型", example=1, enum={"1", "2", "3"}),
     *                 @OA\Property(property="contactInfo[info][0]", type="string", description="聯絡方式資訊", example="0928036789"),
     *                 @OA\Property(property="type", type="string", description="出租/出售", example="rent", enum={"rent", "sell"})
     *              )
     *         )
     *     ),
     *     @OA\Response(response=200, description="更新成功"),
     *     @OA\Response(response=301, description="網址跳轉"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的Token或無法識別的資料，登入失敗"),
     *     @OA\Response(response=403, description="使用已被禁止的Token或嘗試訪問權限不足的項目"),
     *     @OA\Response(response=404, description="資源不存在，查無資料"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Get(
     *      path="/property/manage/car/parking/{id}",
     *      tags={"Property manage Car 物件資訊列表-車位"},
     *      summary="獲取物件預覽資料",
     *      description="獲取物件預覽資料。",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="物件id",
     *          @OA\Schema(
     *              type="integer",
     *              example="74"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="取得成功"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="contactInfo",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="info", type="string", example="聯絡方式: 098888888"),
     *                          @OA\Property(property="type", type="string", example="聯絡型態 1:找房網, 2: 電話聯絡, 3: 電子姓箱 ")
     *                      )
     *                  ),
     *                  @OA\Property(property="contactPerson", type="string", example="聯絡人(房東-li landlord)"),
     *                  @OA\Property(property="district_name", type="string", example="區"),
     *                  @OA\Property(property="building_name", type="string", example="棟"),
     *                  @OA\Property(property="staircase_name", type="string", example="梯"),
     *                  @OA\Property(property="floor_name", type="string", example="樓"),
     *                  @OA\Property(property="household_name", type="string", example="戶"),
     *                  @OA\Property(property="area", type="string", example="地區"),
     *                  @OA\Property(property="exclusive", type="integer", example="面積"),
     *                  @OA\Property(property="parking_attribute", type="string", example="車位屬性"),
     *                  @OA\Property(property="parking_number", type="string", example="車位號碼"),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function preview()
    {
    }
}
