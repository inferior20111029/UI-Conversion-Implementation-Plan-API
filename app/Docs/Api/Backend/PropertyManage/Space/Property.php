<?php

namespace App\Docs\Api\Backend\PropertyManage\Space;

class Property
{
    /**
     * @OA\Get(
     *      path="/property/manage/list/?type=space",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
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
     *          name="searchRentFees",
     *          in="query",
     *          required=false,
     *          description="搜尋房租費用",
     *          @OA\Schema(type="integer", format="integer")
     *      ),
     *      @OA\Parameter(
     *          name="searchManagementFee",
     *          in="query",
     *          required=false,
     *          description="搜尋管理費用",
     *          @OA\Schema(type="integer", format="integer")
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
     *          name="searchSpacePlanType[0]",
     *          in="query",
     *          required=false,
     *          description="搜尋房屋型態，後端預期收到的結果大概會長這樣，searchSpacePlanType: {'公寓', '電梯大樓'}",
     *          @OA\Schema(type="string", format="string")
     *      ),
     *      @OA\Parameter(
     *          name="searchLayout[0]",
     *          in="query",
     *          required=false,
     *          description="搜尋格局，後端預期收到的結果大概會長這樣，searchLayout: {1, 2, 4, 5}，1 代表了搜尋一房，2 代表搜尋 2 房，以此類推，當傳 5 或是 5 以上的數字都視為搜尋 5 房以上",
     *          @OA\Schema(type="integer", format="integer")
     *      ),
     *      @OA\Parameter(
     *          name="searchRegisterArea[0]",
     *          in="query",
     *          required=false,
     *          description="搜尋權狀 (登記面積)，後端預期收到的結果大概會長這樣，searchRegisterArea: {'0-20', '20-30', '50-60', '100-999999999999'}，第一個數字代表了最小值，用 - 隔開後的第二個數字就是最大值，當要搜尋 100 坪時，就是最小值加上理論最大值，搜尋 '其他' 的時候就自己組成這樣的格式 {min int}-{max int}",
     *          @OA\Schema(type="string", format="string")
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
     *                          @OA\Property(property="management_fee", type="integer", example=3000),
     *                          @OA\Property(property="is_car", type="boolean", example=true),
     *                          @OA\Property(property="enable_state", type="boolean", example=true),
     *                          @OA\Property(property="district_name", type="string", example="B"),
     *                          @OA\Property(property="building_name", type="string", example="甲棟"),
     *                          @OA\Property(property="staircase_name", type="string", example=null),
     *                          @OA\Property(property="floor_name", type="string", example="10F"),
     *                          @OA\Property(property="household_name", type="string", example="18B")
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
    public function index() {}

    /**
     * @OA\Get(
     *      path="/property/manage/?type=equipment",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
     *      summary="獲取元件資訊列表",
     *      description="獲取元件資訊列表",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string", description="資料類型", example="equipment")
     *      ),
     *      @OA\Parameter(
     *          name="space_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string", description="空間 ID", example="2b62012b-ab70-4faf-a8e7-d2b6ee9ade9c")
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
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="桌子"),
     *                      @OA\Property(property="area", type="string", example="區域"),
     *                      @OA\Property(property="space", type="string", example="空間"),
     *                      @OA\Property(property="location", type="string", example="位置"),
     *                      @OA\Property(property="from", type="string", example="元件配置"),
     *                      @OA\Property(property="status", type="integer", example=1),
     *                      @OA\Property(property="public_type", type="string", example="p"),
     *                      @OA\Property(property="type_name", type="string", example="客"),
     *                      @OA\Property(property="system_name", type="string", example="變")
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
    public function indexEquipment() {}

    /**
     * @OA\Get(
     *      path="/property/manage/?type=space",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
     *      summary="獲取戶別資訊列表",
     *      description="獲取戶別資訊列表",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string", description="物件類型", example="space")
     *      ),
     *       @OA\Parameter(
     *           name="rental_sale_type",
     *           in="query",
     *           required=true,
     *           description="出租類性 rent(租) sell (售)",
     *           @OA\Schema(type="string", description="出租類性 rent(租) sell (售)", example="sell")
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
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="space_id", type="string", example="beebf564-c1dd-4b93-bd0b-e8f9929934bf"),
     *                      @OA\Property(property="district_name", type="string", example="B"),
     *                      @OA\Property(property="building_name", type="string", example="甲棟"),
     *                      @OA\Property(property="staircase_name", type="string", example=null),
     *                      @OA\Property(property="floor_name", type="string", example="10F"),
     *                      @OA\Property(property="household_name", type="string", example="18B")
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
    public function indexSpace() {}

    /**
     * @OA\Get(
     *      path="/property/manage?type=car",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
     *      summary="獲取戶別下車位資訊列表",
     *      description="獲取戶別下車位資訊列表",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          description="類型: 汽車 (car), 機車 (scooter)",
     *          @OA\Schema(type="string", example="car")
     *      ),
     *      @OA\Parameter(
     *           name="space_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(type="string", description="空間 ID", example="2b62012b-ab70-4faf-a8e7-d2b6ee9ade9c")
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
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="space_id", type="string", example="beebf564-c1dd-4b93-bd0b-e8f9929934bf"),
     *                      @OA\Property(property="district_name", type="string", example="B"),
     *                      @OA\Property(property="building_name", type="string", example="甲棟"),
     *                      @OA\Property(property="staircase_name", type="string", example=null),
     *                      @OA\Property(property="floor_name", type="string", example="10F"),
     *                      @OA\Property(property="household_name", type="string", example="公設名稱(18B)"),
     *                      @OA\Property(property="parking_number", type="string", example="車位編號")
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
    public function indexCar() {}

    /**
     * @OA\Get(
     *      path="/property/manage/register-area",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
     *      summary="獲取戶別下登記坪數",
     *      description="獲取戶別下登記坪數",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *           name="space_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(type="string", description="空間 ID", example="2b62012b-ab70-4faf-a8e7-d2b6ee9ade9c")
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
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="register_area", type="integer", example="1998"),
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
    public function indexRegisterArea()
    {
    }

    /**
     * @OA\Post(
     *      path="/property/manage/space/{spaceId}",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
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
     *               @OA\Schema(
     *                   required={"fees[price]","title", "type", "state", "checkInInfo[lease_term]", "checkInInfo[lease_term]", "checkInInfo[lease_term_type]"},
     *                  @OA\Property(property="fees[price]", type="integer", description="租金費用 &　售價", example=20000),
     *                  @OA\Property(property="fees[unit_price]", type="integer", description="出售使用 單價", example=20000),
     *                  @OA\Property(property="fees[deposit]", type="integer", description="押金", example=40000),
     *                  @OA\Property(property="fees[depositTotalMonth]", type="integer", description="押金月份", example=2),
     *                  @OA\Property(property="fees[managementFee]", type="integer", description="管理費", example=3000),
     *                  @OA\Property(property="checkInInfo[date]", type="string", description="可遷入日", example="1992-11-18"),
     *                  @OA\Property(property="checkInInfo[lease_term]", type="integer", description="最短租期", example=1, enum={"1", "2", "3", "4","5","6"}),
     *                  @OA\Property(property="checkInInfo[lease_term_type]", type="string", description="月或年", enum={"month", "year"}),
     *                  @OA\Property(property="items_included[0]", type="integer", description="租金包含", example=1, enum={"1", "2", "3", "4","5","6"}),
     *                  @OA\Property(property="items_included[1]", type="integer", description="租金包含", example=2, enum={"1", "2", "3", "4","5","6"}),
     *                  @OA\Property(property="state", type="integer", description="上架狀態", example=1),
     *                  @OA\Property(property="have_lease", type="integer", description="出售使用 帶租約", example=1),
     *                  @OA\Property(property="house_age", type="integer", description="出售使用 屋齡 ", example=20),
     *                  @OA\Property(property="decoration[degree]", type="string", description="裝潢程度：unfinished 尚未裝潢, basicFitOut 簡易裝潢, moderateFitOut 中擋裝潢, luxuryFitOut 高擋裝潢", enum={"unfinished","basicFitOut","moderateFitOut", "luxuryFitOut"}),
     *                  @OA\Property(property="decoration[time]", type="string", default="", description="裝潢時間：withinHalfYear 半年內, withinOneYear 一年內, withinThreeYear 三年內, thanThreeYears 三年以上", enum={"withinHalfYear", "withinOneYear", "moreThanOneYear", "thanThreeYears"}),
     *                  @OA\Property(property="title", type="string", description="物件標題", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                  @OA\Property(property="description", type="string", description="物件描述", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                  @OA\Property(property="carpark[0][type]", type="string", description="附設車位 類型 ", example="car", enum={"scooter", "car"}),
     *                  @OA\Property(property="carpark[0][crmParkingSpaceId]", type="string", description="車位UUID", example="0bdb2420-645b-457a-9a85-2515fda4fe80"),
     *                  @OA\Property(property="carpark[0][price]", type="integer", description="車位費用", example=2000),
     *                  @OA\Property(property="transportation[0][type]", type="integer", description="1:線路 2 公車站 3 捷運站 4 火車站", example="3", enum={"1", "2", "3", "4"}),
     *                  @OA\Property(property="transportation[0][name]", type="string", description="名稱之類的", example="大安"),
     *                  @OA\Property(property="livability[0]", type="integer", description="生活機能", example=1, enum={"1", "2", "3", "4","5","6"}),
     *                  @OA\Property(property="livability[1]", type="integer", description="生活機能", example=2, enum={"1", "2", "3", "4","5","6"}),
     *                  @OA\Property(property="document[picture][0][uuid]", type="string", description="圖片 UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                  @OA\Property(property="document[URL]", type="string", description="URL", example="http://192.168.10.105:5173/property_vue/property_information_list/unit-household/0"),
     *                  @OA\Property(property="document[video][0][uuid]", type="string", description="Video UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                  @OA\Property(property="contactPerson[type]", type="string", description="聯絡人類型 landlord 房東 agent 仲介", example="landlord", enum={"landlord", "agent"}),
     *                  @OA\Property(property="contactPerson[name]", type="string", description="聯絡人姓名", example="li landlord"),
     *                  @OA\Property(property="contactInfo[type][0]", type="integer", description="聯絡方式類型", example=1, enum={"1", "2", "3"}),
     *                  @OA\Property(property="contactInfo[info][0]", type="string", description="聯絡方式資訊", example="0928036789"),
     *                  @OA\Property(property="equipment[0][display_state]", type="integer", description="附設設備 0 不顯示 1 顯示", example=0, enum={"0", "1"}),
     *                  @OA\Property(property="equipment[0][id]", type="integer", description="附設設備 ID", example=2),
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
    public function store() {}

    /**
     * @OA\Get(
     *      path="/property/manage/space/{id}/edit",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
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
     *                  @OA\Property(property="space_id", type="string", example="0bdb2420-645b-457a-9a85-2515fda4fe80"),
     *                  @OA\Property(property="title", type="string", example="【社會住宅1】可租補✔台水台電✔傢俱訂製中【社會住宅】可租補✔台水台電✔傢俱訂製中1"),
     *                  @OA\Property(property="description", type="string", example="【社會住宅1】可租補✔台水台電✔傢俱訂製中"),
     *                  @OA\Property(property="state", type="integer", example=1),
     *                  @OA\Property(property="house_age", type="integer", example=1, description="屋齡"),
     *                  @OA\Property(property="have_lease", type="integer", example=1, description="帶租約 0無 1有"),
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
     *                      @OA\Property(property="depositTotalMonth", type="integer", example=2),
     *                      @OA\Property(property="is_management_fee", type="integer", example=1, description="管理費 0無 1有"),
     *                      @OA\Property(property="managementFee", type="integer", example=3000)
     *                  ),
     *                  @OA\Property(
     *                      property="carpark",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="type", type="string", example="car"),
     *                          @OA\Property(property="crmParkingSpaceId", type="string", example="0bdb2420-645b-457a-9a85-2515fda4fe80"),
     *                          @OA\Property(property="price", type="integer", example=20001),
     *                          @OA\Property(property="rent_inclusive", type="integer", example=0, description="1:已含租金內 0: 費用另計",),
     *                          @OA\Property(property="license_plate_number", type="string", example="977-877")
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="items_included",
     *                      type="array",
     *                      @OA\Items(type="integer", example=1)
     *                  ),
     *                  @OA\Property(
     *                      property="checkInInfo",
     *                      type="object",
     *                      @OA\Property(property="date", type="string", format="date", nullable=true),
     *                      @OA\Property(property="lease_term", type="integer", example=1),
     *                      @OA\Property(property="lease_term_type", type="string", example="month")
     *                  ),
     *                  @OA\Property(
     *                      property="livability",
     *                      type="array",
     *                      @OA\Items(type="integer", example=1)
     *                  ),
     *                  @OA\Property(
     *                      property="transportation",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="type", type="integer", example=1),
     *                          @OA\Property(property="name", type="string", example="88")
     *                      )
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
     *                  @OA\Property(property="is_url", type="string", nullable=true, description="1 (url) 0 (video)"),
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
     *                  ),
     *                   @OA\Property(
     *                       property="equipments",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Property(property="id", type="integer", description="1", example="2"),
     *                           @OA\Property(property="display_state", type="string", example="0"),
     *                       )
     *                   )
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
    public function edit() {}

    /**
     * @OA\Patch(
     *     path="/property/manage/space/{uuid}",
     *     tags={"Property manage Space 物件資訊列表-戶別"},
     *     summary="物件資訊列表 編輯物件",
     *     description="物件資訊列表 編輯物件",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *          name="uuid",
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
     *                 required={"space_id", "fees[price]","title", "type", "state", "checkInInfo[lease_term]", "checkInInfo[lease_term]", "checkInInfo[lease_term_type]"},
     *                 @OA\Property(property="space_id", type="string", description="空間 id"),
     *                 @OA\Property(property="fees[price]", type="integer", description="租金費用", example=20000),
     *                 @OA\Property(property="fees[deposit]", type="integer", description="押金", example=40000),
     *                 @OA\Property(property="fees[depositTotalMonth]", type="integer", description="押金月份", example=2),
     *                 @OA\Property(property="fees[managementFee]", type="integer", description="管理費", example=3000),
     *                 @OA\Property(property="checkInInfo[date]", type="string", format="date", description="可遷入日", example="1992-11-18"),
     *                 @OA\Property(property="checkInInfo[lease_term]", type="integer", description="最短租期", example=1, enum={"1", "2", "3", "4","5","6"}),
     *                 @OA\Property(property="checkInInfo[lease_term_type]", type="string", description="月或年", enum={"month", "year"}),
     *                 @OA\Property(property="items_included[0]", type="integer", description="租金包含", example=1, enum={"1", "2", "3", "4","5","6"}),
     *                 @OA\Property(property="items_included[1]", type="integer", description="租金包含", example=2, enum={"1", "2", "3", "4","5","6"}),
     *                 @OA\Property(property="state", type="integer", description="上架狀態", example=1),
     *                 @OA\Property(property="decoration[degree]", type="string", description="裝潢程度：unfinished 尚未裝潢, basicFitOut 簡易裝潢, moderateFitOut 中擋裝潢, luxuryFitOut 高擋裝潢", enum={"unfinished","basicFitOut","moderateFitOut", "luxuryFitOut"}),
     *                 @OA\Property(property="decoration[time]", type="string", default="", description="裝潢時間：withinHalfYear 半年內, withinOneYear 一年內, withinThreeYear 三年內, thanThreeYears 三年以上", enum={"withinHalfYear", "withinOneYear", "moreThanOneYear", "thanThreeYears"}),
     *                 @OA\Property(property="title", type="string", description="物件標題", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                 @OA\Property(property="description", type="string", description="物件描述", example="【社會住宅】可租補✔台水台電✔傢俱訂製中"),
     *                 @OA\Property(property="carpark[0][type]", type="string", description="附設車位類型", example="car", enum={"scooter", "car"}),
     *                 @OA\Property(property="carpark[0][crmParkingSpaceId]", type="string", description="車位UUID", example="0bdb2420-645b-457a-9a85-2515fda4fe80"),
     *                 @OA\Property(property="carpark[0][price]", type="integer", description="車位費用", example=2000),
     *                 @OA\Property(property="carpark[0][licensePlateNumber]", type="string", description="車位車牌", example="977-877"),
     *                 @OA\Property(property="transportation[0][type]", type="integer", description="交通類型", example="3", enum={"1", "2", "3", "4"}),
     *                 @OA\Property(property="transportation[0][name]", type="string", description="交通名稱", example="大安"),
     *                 @OA\Property(property="livability[0]", type="integer", description="生活機能", example=1, enum={"1", "2", "3", "4","5","6"}),
     *                 @OA\Property(property="livability[1]", type="integer", description="生活機能", example=2, enum={"1", "2", "3", "4","5","6"}),
     *                 @OA\Property(property="document[picture][0][uuid]", type="string", description="圖片UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                 @OA\Property(property="document[URL]", type="string", description="URL", example="http://192.168.10.105:5173/property_vue/property_information_list/unit-household/0"),
     *                 @OA\Property(property="document[video][0][uuid]", type="string", description="Video UUID", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                 @OA\Property(property="contactPerson[type]", type="string", description="聯絡人類型", example="landlord", enum={"landlord", "agent"}),
     *                 @OA\Property(property="contactPerson[name]", type="string", description="聯絡人姓名", example="li landlord"),
     *                 @OA\Property(property="contactInfo[type][0]", type="integer", description="聯絡方式類型", example=1, enum={"1", "2", "3"}),
     *                 @OA\Property(property="contactInfo[info][0]", type="string", description="聯絡方式資訊", example="0928036789"),
     *                 @OA\Property(property="equipment[0][display_state]", type="integer", description="附設設備顯示狀態", example=0, enum={"0", "1"}),
     *                 @OA\Property(property="equipment[0][id]", type="integer", description="附設設備ID", example=2),
     *                 @OA\Property(property="type", type="string", description="出租(rent)/出售(sell)類型", example="rent", enum={"rent", "sell"})
     *             )
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
    public function update() {}

    /**
     * @OA\Get(
     *      path="/property/manage/space/{id}",
     *      tags={"Property manage Space 物件資訊列表-戶別"},
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
     *              example="275"
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
     *                  @OA\Property(
     *                      property="equipment",
     *                      type="array",
     *                      @OA\Items(type="string", example="桌子2")
     *                  ),
     *                  @OA\Property(property="district_name", type="string", example="區"),
     *                  @OA\Property(property="building_name", type="string", example="棟"),
     *                  @OA\Property(property="staircase_name", type="string", example="梯"),
     *                  @OA\Property(property="floor_name", type="string", example="樓"),
     *                  @OA\Property(property="household_name", type="string", example="戶"),
     *                  @OA\Property(property="area", type="string", example="地區"),
     *                  @OA\Property(property="exclusive", type="integer", example="面積"),
     *                  @OA\Property(
     *                      property="layoutSetting",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="type", type="string", example="格局: room (房間)、living_room (客廳/餐廳)、kitchen (廚房)、bathroom (衛浴)、balcony (陽台)"),
     *                          @OA\Property(property="quantity", type="string", example="數量")
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
    public function preview()
    {
    }
}
