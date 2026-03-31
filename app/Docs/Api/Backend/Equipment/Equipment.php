<?php

namespace App\Docs\Api\Backend\Equipment;

class Equipment
{
    /**
     * @OA\Get(
     *     path="/equipment",
     *     tags={"Equipment 元件庫"},
     *     summary="元件庫列表",
     *     description="元件庫列表",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(name="page", in="query", required=true,
     *          @OA\Schema(type="integer", default= 1)
     *      ),
     *     @OA\Parameter(name="perPage", in="query",required=true,
     *       @OA\Schema(type="integer",default= 10)
     *       ),
     *     @OA\Parameter(name="filter_key[name]", in="query", required=false, description="元件名稱",
     *        @OA\Schema(type="integer", default= 1)
     *        ),
     *     @OA\Parameter(name="filter_key[type_name]", in="query", required=false, description="類別名稱",
     *         @OA\Schema(type="integer", default= 1, description="類別id")
     *         ),
     *     @OA\Parameter(name="filter_key[system_name]", in="query", required=false, description="系統名稱",
     *         @OA\Schema(type="integer", default= 1, description="系統id")
     *         ),
     *     @OA\Parameter(name="filter_key[area]", in="query", required=false, description="區域",
     *         @OA\Schema(type="string", example= "area")
     *         ),
     *     @OA\Parameter(name="filter_key[space]", in="query", required=false, description="空間",
     *         @OA\Schema(type="string", example= "space")
     *         ),
     *     @OA\Parameter(name="filter_key[location]", in="query", required=false, description="位置",
     *        @OA\Schema(type="string", example= "location")
     *         ),
     *     @OA\Parameter(name="filter_key[district_name]", in="query", required=false, description="區",
     *         @OA\Schema(type="string", example= "district.2")
     *          ),
     *     @OA\Parameter(name="filter_key[building_name]", in="query", required=false, description="棟",
     *         @OA\Schema(type="string", example= "building.2")
     *          ),
     *     @OA\Parameter(name="filter_key[staircase_name]", in="query", required=false, description="梯",
     *         @OA\Schema(type="string", example= "staircase.2")
     *          ),
     *     @OA\Parameter(name="filter_key[floor_name]", in="query", required=false, description="樓",
     *         @OA\Schema(type="string", example= "floor.5")
     *          ),
     *     @OA\Parameter(name="filter_key[household_name]", in="query", required=false, description="戶",
     *          @OA\Schema(type="string", example= "household.2")
     *           ),
     *     @OA\Parameter(name="filter_key[public_type]", in="query", required=false, description="空間屬性",
     *           @OA\Schema(type="string", example= "p")
     *            ),
     *     @OA\Parameter(name="filter_key[status]", in="query", required=false, description="綁訂戶別狀態",
     *            @OA\Schema(type="string", example= "0")
     *             ),
     *     @OA\Parameter(name="filter_key[updated_at]", in="query", required=false, description="更新時間",
     *             @OA\Schema(type="string", example= "2024-06-12")
     *              ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="perPage", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=2),
     *                 @OA\Property(property="lastPage", type="integer", example=1),
     *                 @OA\Property(property="next_url", type="string", nullable=true),
     *                 @OA\Property(property="prev_url", type="string", nullable=true),
     *                 @OA\Property(
     *                     property="list",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="name", type="string", example="低級桌子", description="設備名稱"),
     *                         @OA\Property(property="space_id", type="string", nullable=true, description="戶別id"),
     *                         @OA\Property(property="type_name", type="string", example="父層", description="類別名稱"),
     *                         @OA\Property(property="system_name", type="string", nullable=true, description="系統名稱"),
     *                         @OA\Property(property="area", type="string", example="區域", description="區域"),
     *                         @OA\Property(property="space", type="string", example="空間", description="空間"),
     *                         @OA\Property(property="location", type="string", example="位置", description="位置"),
     *                         @OA\Property(property="public_type", type="string", example="p", description="空間屬性"),
     *                         @OA\Property(property="pcces_code", type="string", example="公共工程編碼"),
     *                         @OA\Property(property="ominiclass_code", type="string", example="OminiClass編碼"),
     *                         @OA\Property(property="user_defined_code", type="string", example="789-999"),
     *                         @OA\Property(property="brand", type="string", example="格陵"),
     *                         @OA\Property(property="spec_info", type="string", example="56214"),
     *                         @OA\Property(property="spec", type="string", example="56214-56214"),
     *                         @OA\Property(property="size", type="string", example="100*150"),
     *                         @OA\Property(property="weight", type="string", example="80kg"),
     *                         @OA\Property(property="place_of_production", type="string", example="東洲"),
     *                         @OA\Property(property="price", type="integer", example=125000),
     *                         @OA\Property(property="cost", type="integer", example=199999),
     *                         @OA\Property(property="acquisition_date", type="string", format="date", example="1999-12-11"),
     *                         @OA\Property(property="expiration_date", type="string", format="date", example="1999-12-11"),
     *                         @OA\Property(property="amortization_year", type="string", format="date", example="1999-12-11"),
     *                         @OA\Property(property="curing_cycle", type="string", format="date", example="1999-12-11"),
     *                         @OA\Property(property="warranty", type="string", format="date", example="1999-12-11"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-02 18:26:40"),
     *                         @OA\Property(
     *                             property="properties",
     *                             type="object",
     *                             additionalProperties=true
     *                         ),
     *                         @OA\Property(
     *                             property="file_bim",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="type_name", type="string", example="file_bim"),
     *                                 @OA\Property(property="file_uuid", type="string", example="d86325c4-0679-4901-aa76-0f29437c9b86"),
     *                                 @OA\Property(property="url", type="string", example="laravel-leasehold.test/storage/leasehold/893/6ygFJwM97F8k9LcLora62PPEx2qu2iucvw348loJ.xlsm")
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="file_built_drawing",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="type_name", type="string", example="file_built_drawing"),
     *                                 @OA\Property(property="file_uuid", type="string", example="d86325c4-0679-4901-aa76-0f29437c9b86"),
     *                                 @OA\Property(property="url", type="string", example="laravel-leasehold.test/storage/leasehold/893/6ygFJwM97F8k9LcLora62PPEx2qu2iucvw348loJ.xlsm")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *       @OA\Response(response=201, description="建立成功"),
     *       @OA\Response(response=301, description="網址跳轉"),
     *       @OA\Response(response=400, description="參數錯誤"),
     *       @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *       @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *       @OA\Response(response=404, description="資源不存在，查無資料"),
     *       @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Get(
     *     path="/equipment/create",
     *     tags={"Equipment 元件庫"},
     *     summary="元件庫 新增初始資料",
     *     description="元件庫 新增初始資料",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="category",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="父層"),
     *                         @OA\Property(
     *                             property="branch",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=6),
     *                                 @OA\Property(property="name", type="string", example="子層")
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="household",
     *                     type="object",
     *                     @OA\Property(
     *                         property="district",
     *                         type="array",
     *                         @OA\Items(type="string", example="B")
     *                     ),
     *                     @OA\Property(
     *                         property="building",
     *                         type="array",
     *                         @OA\Items(type="string", example="甲棟")
     *                     ),
     *                     @OA\Property(
     *                         property="staircase",
     *                         type="array",
     *                         @OA\Items(type="string")
     *                     ),
     *                     @OA\Property(
     *                         property="floor",
     *                         type="array",
     *                         @OA\Items(type="string", example="11F")
     *                     ),
     *                     @OA\Property(
     *                         property="household_id",
     *                         type="array",
     *                         @OA\Items(type="string", example="11B")
     *                     ),
     *                     @OA\Property(
     *                         property="origin",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="space_id", type="string", example="0ddb0a99-cd69-48b8-8021-2bbb0d1c08b5"),
     *                             @OA\Property(property="district", type="string", example="B"),
     *                             @OA\Property(property="building", type="string", example="甲棟"),
     *                             @OA\Property(property="staircase", type="string", nullable=true),
     *                             @OA\Property(property="floor", type="string", example="11F"),
     *                             @OA\Property(property="household_id", type="string", example="11B"),
     *                             @OA\Property(property="public_type", type="integer", example=1)
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="properties",
     *                     type="object",
     *                     description="詳細屬性"
     *                      )
     *                 )
     *             )
     *         )
     *     ),
     *          @OA\Response(response=201, description="建立成功"),
     *          @OA\Response(response=301, description="網址跳轉"),
     *          @OA\Response(response=400, description="參數錯誤"),
     *          @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *          @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *          @OA\Response(response=404, description="資源不存在，查無資料"),
     *          @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function create()
    {
    }

    /**
     * @OA\Post(
     *     path="/equipment",
     *     tags={"Equipment 元件庫"},
     *     summary="新增元件",
     *     description="新增元件",
     *      security={{"Authorization": {}},{"Community-Id-Header": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", description="設備名稱", example="北極殿冷氣"),
     *                  @OA\Property(property="type_name", type="string", description="類別名稱id", example="1"),
     *                  @OA\Property(property="system_name", type="string", description="系統名稱id", example="2"),
     *                  @OA\Property(property="space_id", type="string", description="戶別id", example=""),
     *                  @OA\Property(property="area", type="string", description="區域", example=""),
     *                  @OA\Property(property="space", type="string", description="標準層四房二廳", example=""),
     *                  @OA\Property(property="location", type="string", description="客廳", example=""),
     *                  @OA\Property(property="pcces_code", type="string", description="公共工程編碼"),
     *                  @OA\Property(property="ominiclass_code", type="string", description="OminiClass編碼", example=""),
     *                  @OA\Property(property="user_defined_code", type="string", description="設備編碼", example=""),
     *                  @OA\Property(property="brand", type="string", description="品牌", example=""),
     *                  @OA\Property(property="model", type="string", description="型號", example=""),
     *                  @OA\Property(property="spec_info", type="string", description="細目規格資訊", example=""),
     *                  @OA\Property(property="public_type", type="string", description="空間屬性(L=大公, S=小公, P=專有)", enum={"L", "S", "P"}),
     *                  @OA\Property(property="spec", type="string", description="補充規格資訊", example=""),
     *                  @OA\Property(property="size", type="string", description="尺寸", example=""),
     *                  @OA\Property(property="weight", type="string", description="重量", example=""),
     *                  @OA\Property(property="place_of_production", type="string", description="產地", example=""),
     *                  @OA\Property(property="price", type="integer", description="預估成本", example=""),
     *                  @OA\Property(property="cost", type="integer", description="取得成本", example=""),
     *                  @OA\Property(property="from", type="string", description="取得來源", example="JA"),
     *                  @OA\Property(property="unit", type="string", description="單位", example="sa"),
     *                  @OA\Property(property="amortization_year", type="string", description="使用年限", example=""),
     *                  @OA\Property(property="curing_cycle", type="string", description="養護週期", example=""),
     *                  @OA\Property(property="acquisition_date", type="string", description="保固年限", example=""),
     *                  @OA\Property(property="properties", type="string", description="詳細屬性", example=""),
     *                  @OA\Property(property="file_bim[0]", type="string", description="BIM圖資", example=""),
     *                  @OA\Property(property="file_shop_drawing[0]", type="string", description="施工圖", example=""),
     *                  @OA\Property(property="file_built_drawing[0]", type="string", description="竣工圖", example=""),
     *                  @OA\Property(property="conservation_instructions[0]", type="string", description="保養說明書", example=""),
     *                  @OA\Property(property="user_guide[0]", type="string", description="設備說明書/操作手冊", example=""),
     *                  @OA\Property(property="certificate_of_merchandise[0]", type="string", description="出廠報告", example=""),
     *                  @OA\Property(property="testing_report[0]", type="string", description="測試報告", example=""),
     *                  @OA\Property(property="other[0]", type="string", description="廠商保固養護紀錄", example=""),
     *                  @OA\Property(property="specifications[0]", type="string", description="設備規格書", example=""),
     *                  @OA\Property(property="certification_report[0]", type="string", description="設備認證報告書", example=""),
     *                  @OA\Property(property="materials_cost_list[0]", type="string", description="保養材料費用清單", example=""),
     *                  @OA\Property(property="floorplan[0]", type="string", description="樓層平面圖", example=""),
     *                  @OA\Property(property="building_elevation[0]", type="string", description="建築立面圖", example=""),
     *                  @OA\Property(property="lighting_scheme[0]", type="string", description="建築燈光計畫", example=""),
     *                  @OA\Property(property="energy_loss_estimate[0]", type="string", description="建築物能源損耗預估(水費, 電費)", example=""),
     *                  @OA\Property(property="images[0]", type="string", description="圖片", example=""),
     *                  @OA\Property(property="component_id[0]", type="integer", description="新增構件 構件id 來自 [post]/equipment/equipment/component", example="1"),
     *
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
     *     path="/equipment/{id}/edit",
     *     tags={"Equipment 元件庫"},
     *     summary="取得元件編輯資料",
     *     description="取得元件編輯資料",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=27),
     *                 @OA\Property(property="name", type="string", example="低級桌子"),
     *                 @OA\Property(property="space_id", type="integer", nullable=true),
     *                 @OA\Property(property="type_name", type="string", example="客"),
     *                 @OA\Property(property="system_name", type="string", example="變"),
     *                 @OA\Property(property="area", type="string", example="區域"),
     *                 @OA\Property(property="space", type="string", example="空間"),
     *                 @OA\Property(property="location", type="string", example="位置"),
     *                 @OA\Property(property="public_type", type="string", example="p"),
     *                 @OA\Property(property="pcces_code", type="string", example="公共工程編碼"),
     *                 @OA\Property(property="ominiclass_code", type="string", example="OminiClass編碼"),
     *                 @OA\Property(property="user_defined_code", type="string", example="789-999"),
     *                 @OA\Property(property="brand", type="string", example="格陵"),
     *                 @OA\Property(property="spec_info", type="string", example="56214"),
     *                 @OA\Property(property="spec", type="string", example="56214-56214"),
     *                 @OA\Property(property="size", type="string", example="100*150"),
     *                 @OA\Property(property="weight", type="string", example="80kg"),
     *                 @OA\Property(property="place_of_production", type="string", example="東洲"),
     *                 @OA\Property(property="price", type="integer", example=125000),
     *                 @OA\Property(property="cost", type="integer", example=199999),
     *                 @OA\Property(property="acquisition_date", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="expiration_date", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="amortization_year", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="curing_cycle", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="warranty", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="status", type="integer", example=0),
     *                 @OA\Property(
     *                     property="properties",
     *                     type="object",
     *                     @OA\Property(
     *                         property="4.00",
     *                         type="object",
     *                         @OA\Property(property="name", type="string", example="電氣屬性"),
     *                         @OA\Property(property="value", type="string", nullable=true),
     *                         @OA\Property(
     *                             property="properties",
     *                             type="object",
     *                             @OA\Property(property="4.01", type="string", example="電壓"),
     *                             @OA\Property(property="4.02", type="string", example="電流")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="file_bim",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="type_name", type="string", example="file_bim"),
     *                         @OA\Property(property="file_uuid", type="string", example="bf4339e6-c444-4083-a191-4d973bbaee5e"),
     *                         @OA\Property(property="url", type="string", nullable=true)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="資源不存在"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function edit()
    {
    }

    /**
     * @OA\Patch(
     *     path="/equipment/{id}",
     *     tags={"Equipment 元件庫"},
     *     summary="編輯元件",
     *     description="編輯元件",
     *     security={{"Authorization": {}},{"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="",
     *          @OA\Schema(
     *             type="string",
     *          )
     *        ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", description="設備名稱", example=""),
     *                  @OA\Property(property="type_name", type="string", description="類別名稱", example=""),
     *                  @OA\Property(property="system_name", type="string", description="系統名稱", example=""),
     *                  @OA\Property(property="space_id", type="string", description="戶別id", example=""),
     *                  @OA\Property(property="area", type="string", description="區域", example=""),
     *                  @OA\Property(property="space", type="string", description="標準層四房二廳", example=""),
     *                  @OA\Property(property="location", type="string", description="客廳", example=""),
     *                  @OA\Property(property="pcces_code", type="string", description="公共工程編碼", example=""),
     *                  @OA\Property(property="ominiclass_code", type="string", description="OminiClass編碼", example=""),
     *                  @OA\Property(property="user_defined_code", type="string", description="設備編碼", example=""),
     *                  @OA\Property(property="brand", type="string", description="品牌", example=""),
     *                  @OA\Property(property="model", type="string", description="型號", example=""),
     *                  @OA\Property(property="spec_info", type="string", description="細目規格資訊", example=""),
     *                  @OA\Property(property="public_type", type="string", description="空間屬性(L=大公, S=小公, P=專有)", enum={"L", "S", "P"}),
     *                  @OA\Property(property="spec", type="string", description="補充規格資訊", example=""),
     *                  @OA\Property(property="size", type="string", description="尺寸", example=""),
     *                  @OA\Property(property="weight", type="string", description="重量", example=""),
     *                  @OA\Property(property="place_of_production", type="string", description="產地", example=""),
     *                  @OA\Property(property="price", type="integer", description="預估成本", example=""),
     *                  @OA\Property(property="cost", type="integer", description="取得成本", example=""),
     *                  @OA\Property(property="from", type="string", description="取得來源", example="JA"),
     *                  @OA\Property(property="unit", type="string", description="單位", example="sa"),
     *                  @OA\Property(property="amortization_year", type="string", description="使用年限", example=""),
     *                  @OA\Property(property="curing_cycle", type="string", description="養護週期", example=""),
     *                  @OA\Property(property="acquisition_date", type="string", description="保固年限", example=""),
     *                  @OA\Property(property="properties", type="string", description="詳細屬性", example=""),
     *                  @OA\Property(property="file_bim[0]", type="string", description="BIM圖資", example=""),
     *                  @OA\Property(property="file_shop_drawing[0]", type="string", description="施工圖", example=""),
     *                  @OA\Property(property="file_built_drawing[0]", type="string", description="竣工圖", example=""),
     *                  @OA\Property(property="conservation_instructions[0]", type="string", description="保養說明書", example=""),
     *                  @OA\Property(property="user_guide[0]", type="string", description="設備說明書/操作手冊", example=""),
     *                  @OA\Property(property="certificate_of_merchandise[0]", type="string", description="出廠報告", example=""),
     *                  @OA\Property(property="testing_report[0]", type="string", description="測試報告", example=""),
     *                  @OA\Property(property="other[0]", type="string", description="廠商保固養護紀錄", example=""),
     *                  @OA\Property(property="specifications[0]", type="string", description="設備規格書", example=""),
     *                  @OA\Property(property="certification_report[0]", type="string", description="設備認證報告書", example=""),
     *                  @OA\Property(property="materials_cost_list[0]", type="string", description="保養材料費用清單", example=""),
     *                  @OA\Property(property="floorplan[0]", type="string", description="樓層平面圖", example=""),
     *                  @OA\Property(property="building_elevation[0]", type="string", description="建築立面圖", example=""),
     *                  @OA\Property(property="lighting_scheme[0]", type="string", description="建築燈光計畫", example=""),
     *                  @OA\Property(property="energy_loss_estimate[0]", type="string", description="建築物能源損耗預估(水費, 電費)", example=""),
     *                  @OA\Property(property="images[0]", type="string", description="圖片", example=""),
     *                  @OA\Property(property="del_files[0]", type="string", description="刪除 檔案", example="1"),
     *                  @OA\Property(property="component_id[0]", type="integer", description="新增構件", example="1"),
     *                  @OA\Property(property="del_component_id[0]", type="integer", description="刪除構件", example="2"),
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
    public function update()
    {
    }

    /**
     * @OA\Get(
     *     path="/equipment/{id}",
     *      tags={"Equipment 元件庫"},
     *      summary="檢視元件資料",
     *      description="檢視元件資料",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="",
     *         @OA\Schema(
     *            type="string",
     *         )
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="category",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="父層"),
     *                         @OA\Property(
     *                             property="branch",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=6),
     *                                 @OA\Property(property="name", type="string", example="子層")
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="household",
     *                     type="object",
     *                     @OA\Property(property="district", type="array", @OA\Items(type="string", example="B")),
     *                     @OA\Property(property="building", type="array", @OA\Items(type="string", example="甲棟")),
     *                     @OA\Property(property="staircase", type="array", @OA\Items(type="string", example="甲棟")),
     *                     @OA\Property(property="floor", type="array", @OA\Items(type="string", example="11F")),
     *                     @OA\Property(property="household_id", type="array", @OA\Items(type="string", example="11B")),
     *                     @OA\Property(
     *                         property="origin",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="space_id", type="string", example="0ddb0a99-cd69-48b8-8021-2bbb0d1c08b5"),
     *                             @OA\Property(property="district", type="string", example="B"),
     *                             @OA\Property(property="building", type="string", example="甲棟"),
     *                             @OA\Property(property="floor", type="string", example="11F"),
     *                             @OA\Property(property="household_id", type="string", example="11B"),
     *                             @OA\Property(property="public_type", type="integer", example=1)
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="id", type="integer", example=137),
     *                 @OA\Property(property="name", type="string", example="低級桌子"),
     *                 @OA\Property(property="type_name", type="string", example="父層"),
     *                 @OA\Property(property="area", type="string", example="區域"),
     *                 @OA\Property(property="space", type="string", example="空間"),
     *                 @OA\Property(property="location", type="string", example="位置"),
     *                 @OA\Property(property="public_type", type="string", example="p"),
     *                 @OA\Property(property="pcces_code", type="string", example="公共工程編碼"),
     *                 @OA\Property(property="ominiclass_code", type="string", example="OminiClass編碼"),
     *                 @OA\Property(property="user_defined_code", type="string", example="789-999"),
     *                 @OA\Property(property="brand", type="string", example="格陵"),
     *                 @OA\Property(property="spec_info", type="string", example="56214"),
     *                 @OA\Property(property="spec", type="string", example="56214-56214"),
     *                 @OA\Property(property="size", type="string", example="100*150"),
     *                 @OA\Property(property="weight", type="string", example="80kg"),
     *                 @OA\Property(property="place_of_production", type="string", example="東洲"),
     *                 @OA\Property(property="price", type="integer", example=125000),
     *                 @OA\Property(property="cost", type="integer", example=199999),
     *                 @OA\Property(property="acquisition_date", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="expiration_date", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="amortization_year", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="curing_cycle", type="string", format="date", example="1999-12-11"),
     *                 @OA\Property(property="warranty", type="string", format="date", example="1999-12-11"),
     *             )
     *         )
     *     ),
     *       @OA\Response(response=201, description="建立成功"),
     *       @OA\Response(response=301, description="網址跳轉"),
     *       @OA\Response(response=400, description="參數錯誤"),
     *       @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *       @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *       @OA\Response(response=404, description="資源不存在，查無資料"),
     *       @OA\Response(response=500, description="程式錯誤"),
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Delete(
     *       path="/equipment/{id}",
     *       tags={"Equipment 元件庫"},
     *       summary="刪除元件資料",
     *       description="刪除元件資料",
     *       security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *       @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="",
     *          @OA\Schema(
     *             type="string",
     *          )
     *        ),
     *      @OA\Response(response=201, description="建立成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function destroy()
    {
    }
}
