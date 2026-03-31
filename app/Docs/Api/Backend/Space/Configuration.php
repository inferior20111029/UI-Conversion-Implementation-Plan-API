<?php

namespace App\Docs\Api\Backend\Space;

class Configuration
{
    /**
     * @OA\Get(
     *     path="/configuration",
     *     tags={"Configuration 空間配置"},
     *     summary="取得空間配置列表",
     *     description="取得空間配置的詳細資訊",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          description="專有:1 公有:0",
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *     @OA\Parameter(
     *         name="filter_key[district]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=false)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[building]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[staircase]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[floor]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[household]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[doorplate]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[main_application]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[block_id]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *    @OA\Response(
     *      response=200,
     *      description="取得成功",
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *              "code": 200,
     *              "message": "取得成功",
     *              "data": {
     *                  "page": 1,
     *                  "perPage": 30,
     *                  "total": 1,
     *                  "lastPage": 1,
     *                  "next_url": null,
     *                  "prev_url": null,
     *                  "list": {
     *                      {
     *                          "space_id": "120e317c-afd6-48bd-84a8-46530159e6cf",
     *                          "company_id": 10,
     *                          "comid": 899,
     *                          "locate": null,
     *                          "district": "district.3",
     *                          "district_name": "B",
     *                          "district_natsort": "B",
     *                          "building": "building.3",
     *                          "building_name": "甲棟",
     *                          "building_natsort": "甲棟",
     *                          "staircase": null,
     *                          "staircase_name": null,
     *                          "staircase_natsort": null,
     *                          "floor": "floor.5",
     *                          "floor_name": "11F",
     *                          "floor_natsort": "[00000000011000]F",
     *                          "household": "household.99",
     *                          "household_name": "11B",
     *                          "household_natsort": "[00000000011000]B",
     *                          "public_type": 1,
     *                          "doorplate": "dooe",
     *                          "building_build_licence_id": null,
     *                          "block_id": "123654",
     *                          "tax_id": "7889",
     *                          "use_license_id": "8-777",
     *                          "main_application": "H001",
     *                          "house_status": null,
     *                          "handover_date": null,
     *                          "warranty_type": null,
     *                          "created_at": null,
     *                          "updated_at": null,
     *                          "deleted_at": null
     *                      }
     *                  }
     *              }
     *          }
     *      )
     *  ),
     *     @OA\Response(
     *         response=301,
     *         description="網址跳轉"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token、或是無法識別的資料、登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="資源不存在，查無資料"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="程式錯誤"
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Get(
     *     path="/configuration/create",
     *     tags={"Configuration 空間配置"},
     *     summary="取的建立空建配置下拉選單",
     *     description="取的建立空建配置下拉選單",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *           name="type",
     *           in="query",
     *           required=true,
     *           description="專有:1 公有:0",
     *           @OA\Schema(type="integer", default=1)
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="取得成功"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="floor",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="configuration_id", type="string"),
     *                         @OA\Property(property="company_id", type="integer"),
     *                         @OA\Property(property="comid", type="integer"),
     *                         @OA\Property(property="language_id", type="integer"),
     *                         @OA\Property(property="language", type="string"),
     *                         @OA\Property(property="configuration_value", type="string"),
     *                         @OA\Property(property="configuration_name", type="string"),
     *                         @OA\Property(property="configuration_natsort", type="string"),
     *                         @OA\Property(property="configuration_type", type="string"),
     *                         @OA\Property(property="floor_type", type="string", nullable=true)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="district",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="configuration_id", type="string"),
     *                         @OA\Property(property="company_id", type="integer"),
     *                         @OA\Property(property="comid", type="integer"),
     *                         @OA\Property(property="language_id", type="integer"),
     *                         @OA\Property(property="language", type="string"),
     *                         @OA\Property(property="configuration_value", type="string"),
     *                         @OA\Property(property="configuration_name", type="string"),
     *                         @OA\Property(property="configuration_natsort", type="string"),
     *                         @OA\Property(property="configuration_type", type="string"),
     *                         @OA\Property(property="floor_type", type="string", nullable=true)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="building",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="configuration_id", type="string"),
     *                         @OA\Property(property="company_id", type="integer"),
     *                         @OA\Property(property="comid", type="integer"),
     *                         @OA\Property(property="language_id", type="integer"),
     *                         @OA\Property(property="language", type="string"),
     *                         @OA\Property(property="configuration_value", type="string"),
     *                         @OA\Property(property="configuration_name", type="string"),
     *                         @OA\Property(property="configuration_natsort", type="string"),
     *                         @OA\Property(property="configuration_type", type="string"),
     *                         @OA\Property(property="floor_type", type="string", nullable=true)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="house_type",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string"
     *                     ),
     *                     example={"H001", "H002", "H004", "H005", "H006", "H007", "H008", "H009", "H010", "H011", "H012", "H013", "H014"}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="建立成功"),
     *     @OA\Response(response=301, description="網址跳轉"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *     @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *     @OA\Response(response=404, description="資源不存在，查無資料"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function create()
    {
    }

    /**
     * @OA\Post(
     *      path="/configuration",
     *      tags={"Configuration 空間配置"},
     *      summary="建立空間配置",
     *      description="建立空間配置",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="district", type="string", description="區 ex:district.2,B"),
     *                  @OA\Property(property="building", type="string", description="棟 ex:building.3,甲棟"),
     *                  @OA\Property(property="staircase", type="string", description="梯 ex:staircase.3,梯1"),
     *                  @OA\Property(property="floor", type="string", description="樓 ex:floor.3,11F"),
     *                  @OA\Property(property="household", type="string", description="戶 ex:household.99,11B"),
     *                  @OA\Property(property="doorplate", type="string", description="門牌"),
     *                  @OA\Property(property="locate", type="string", description="做落"),
     *                  @OA\Property(property="use_license_id", type="string", description="使用執照號碼"),
     *                  @OA\Property(property="building_build_licence_id", type="string", description="建照執照號碼"),
     *                  @OA\Property(property="block_id", type="string", description="建號"),
     *                  @OA\Property(property="tax_id", type="string", description="稅籍號碼"),
     *                  @OA\Property(property="extent_of_ownership", type="string", description="權利範圍"),
     *                  @OA\Property(
     *                      property="main_application",
     *                      type="string",
     *                      description="主要用途: H001-住家, H002-套房, H004-店鋪, H005-商場, H006-辦公室, H007-合併戶, H008-汽車停車場(法定), H009-汽車停車場(獎勵), H010-汽車停車場(增設), H011-機車停車場(法定), H012-機車停車場(獎勵), H013-機車停車場(增設), H014-公共空間",
     *                      enum={"H001", "H002", "H004", "H005", "H006", "H007", "H008", "H009", "H010", "H011", "H012", "H013", "H014"}
     *                  ),
     *                  @OA\Property(property="land_use_zoning", type="string", description="土地使用分區 住宅:residence 商用: commercial", example="residence"),
     *                  @OA\Property(property="water[0][value]", type="string", description="水號"),
     *                  @OA\Property(property="water[0][children][0]", type="string", description="水號子層"),
     *                  @OA\Property(property="electric[0][value]", type="string", description="電號"),
     *                  @OA\Property(property="electric[0][children][0]", type="string", description="電號子層"),
     *                  @OA\Property(property="type", type="integer", description="專有:1 公有:0")
     *                )
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
     *     path="/configuration/{space_id}",
     *     tags={"Configuration 空間配置"},
     *     summary="檢視戶別下空間配置資料",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="space_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             description="空間 ID"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="取得成功"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="space_id", type="string", example="966c9657-2e50-42c2-8270-758e7e286363"),
     *                 @OA\Property(property="locate", type="string", nullable=true),
     *                 @OA\Property(property="district", type="string", example="district.2"),
     *                 @OA\Property(property="district_name", type="string", nullable=true, example="B"),
     *                 @OA\Property(property="district_natsort", type="string", example="B"),
     *                 @OA\Property(property="building", type="string", example="building.3"),
     *                 @OA\Property(property="building_name", type="string", example="甲棟"),
     *                 @OA\Property(property="building_natsort", type="string", example="甲棟"),
     *                 @OA\Property(property="staircase", type="string", nullable=true),
     *                 @OA\Property(property="staircase_name", type="string", nullable=true),
     *                 @OA\Property(property="staircase_natsort", type="string", nullable=true),
     *                 @OA\Property(property="floor", type="string", example="floor.5"),
     *                 @OA\Property(property="floor_name", type="string", example="11F"),
     *                 @OA\Property(property="floor_natsort", type="string", example="[00000000011000]F"),
     *                 @OA\Property(property="household", type="string", example="household.99"),
     *                 @OA\Property(property="household_name", type="string", example="11B"),
     *                 @OA\Property(property="household_natsort", type="string", example="[00000000011000]B"),
     *                 @OA\Property(property="public_type", type="integer", example=1),
     *                 @OA\Property(property="doorplate", type="string", example="dooe"),
     *                 @OA\Property(property="block_id", type="string", example="123654"),
     *                 @OA\Property(property="tax_id", type="string", example="7889"),
     *                 @OA\Property(property="use_license_id", type="string", example="8-777"),
     *                 @OA\Property(property="main_application", type="string", example="H001"),
     *                 @OA\Property(property="house_status", type="string", nullable=true),
     *                 @OA\Property(property="handover_date", type="string", format="date", nullable=true),
     *                 @OA\Property(property="warranty_type", type="string", nullable=true),
     *                 @OA\Property(property="land_use_zoning", type="string", example="residence"),
     *                 @OA\Property(
     *                     property="water",
     *                     type="object",
     *                     @OA\Property(
     *                         property="41bf166d-9a78-46bb-8443-a1ba578a5a94",
     *                         type="object",
     *                         @OA\Property(property="id", type="string", example="41bf166d-9a78-46bb-8443-a1ba578a5a94"),
     *                         @OA\Property(property="type", type="string", example="water"),
     *                         @OA\Property(property="label", type="string", example="8568"),
     *                         @OA\Property(
     *                             property="children",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="string", example="42fdb4fb-2994-424d-89a6-c930b284316d"),
     *                                 @OA\Property(property="type", type="string", example="water"),
     *                                 @OA\Property(property="label", type="string", example="88"),
     *                                 @OA\Property(property="children", type="array", @OA\Items(type="object"))
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="electric",
     *                     type="object",
     *                     @OA\Property(
     *                         property="dfcf872b-3984-4d57-8a4d-0970a3488408",
     *                         type="object",
     *                         @OA\Property(property="id", type="string", example="dfcf872b-3984-4d57-8a4d-0970a3488408"),
     *                         @OA\Property(property="type", type="string", example="electric"),
     *                         @OA\Property(property="label", type="string", example="8568"),
     *                         @OA\Property(
     *                             property="children",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="string", example="7aa8e725-cef9-485f-ab7f-9da60b4e59c6"),
     *                                 @OA\Property(property="type", type="string", example="electric"),
     *                                 @OA\Property(property="label", type="string", example="88"),
     *                                 @OA\Property(property="children", type="array", @OA\Items(type="object"))
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=301, description="網址跳轉"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *     @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *     @OA\Response(response=404, description="資源不存在，查無資料"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Patch(
     *     path="/configuration/{space_id}",
     *     operationId="updateConfiguration",
     *     tags={"Configuration 空間配置"},
     *     summary="修改空間配置",
     *     description="修改空間配置",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="space_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="district", type="string", description="區", example="district.3,B"),
     *                 @OA\Property(property="building", type="string", description="棟", example="building.3,甲棟"),
     *                 @OA\Property(property="staircase", type="string", description="梯", example=""),
     *                 @OA\Property(property="floor", type="string", description="樓", example="floor.5,11F"),
     *                 @OA\Property(property="household", type="string", description="戶", example="household.99,11B"),
     *                 @OA\Property(property="doorplate", type="string", description="門牌", example="dooe222"),
     *                 @OA\Property(property="use_license_id", type="string", description="使用執照號碼", example="8-777"),
     *                 @OA\Property(property="building_build_licence_id", type="string", description="建照執照號碼", example="a9997"),
     *                 @OA\Property(property="block_id", type="string", description="建號", example="123654"),
     *                 @OA\Property(property="tax_id", type="string", description="稅籍號碼", example="7889"),
     *                 @OA\Property(property="main_application", type="string", description="主要用途", enum={"H001", "H002", "H004", "H005", "H006", "H007", "H008", "H009", "H010", "H011", "H012", "H013", "H014"}, example="H001"),
     *                 @OA\Property(property="water[228151e4-e9b6-4640-8f09-a58cb63b26d1][id]", type="string", description="水號 ID", example="228151e4-e9b6-4640-8f09-a58cb63b26d1"),
     *                 @OA\Property(property="water[228151e4-e9b6-4640-8f09-a58cb63b26d1][type]", type="string", description="水號類型", example="water"),
     *                 @OA\Property(property="water[228151e4-e9b6-4640-8f09-a58cb63b26d1][label]", type="string", description="水號標籤", example="12-12123456-1"),
     *                 @OA\Property(property="water[228151e4-e9b6-4640-8f09-a58cb63b26d1][children][0][id]", type="string", description="水號子層 ID", example="0425341e-eb3d-468a-9500-eafad539a770"),
     *                 @OA\Property(property="water[228151e4-e9b6-4640-8f09-a58cb63b26d1][children][0][type]", type="string", description="水號子層類型", example="water"),
     *                 @OA\Property(property="water[228151e4-e9b6-4640-8f09-a58cb63b26d1][children][0][label]", type="string", description="水號子層標籤", example="12-12123456-2"),
     *                 @OA\Property(property="water[228151e4-e9b6-4640-8f09-a58cb63b26d1][children][0][children]", type="string", example="[]"),
     *                 @OA\Property(property="electric[e99cd956-5c9c-41a4-84f5-801e94d1db0f][id]", type="string", description="電號 ID", example="e99cd956-5c9c-41a4-84f5-801e94d1db0f"),
     *                 @OA\Property(property="electric[e99cd956-5c9c-41a4-84f5-801e94d1db0f][type]", type="string", description="電號類型", example="electric"),
     *                 @OA\Property(property="electric[e99cd956-5c9c-41a4-84f5-801e94d1db0f][label]", type="string", description="電號標籤", example="12-electric-122"),
     *                 @OA\Property(property="electric[e99cd956-5c9c-41a4-84f5-801e94d1db0f][children][0][id]", type="string", description="電號子層 ID", example="34a7ba27-8345-464a-ada6-7db401345918"),
     *                 @OA\Property(property="electric[e99cd956-5c9c-41a4-84f5-801e94d1db0f][children][0][type]", type="string", description="電號子層類型", example="electric"),
     *                 @OA\Property(property="electric[e99cd956-5c9c-41a4-84f5-801e94d1db0f][children][0][label]", type="string", description="電號子層標籤", example="12-electric-22"),
     *                 @OA\Property(property="electric[e99cd956-5c9c-41a4-84f5-801e94d1db0f][children][0][children]", type="string", example="[]"),
     *                 @OA\Property(property="electric[0][id]", type="string", description="電號 ID 新增的時候 給0 或是產生新的uuid", example="0"),
     *                  @OA\Property(property="electric[0][type]", type="string", description="電號類型", example="electric"),
     *                  @OA\Property(property="electric[0][label]", type="string", description="電號標籤", example="12-electric-122"),
     *                  @OA\Property(property="electric[0][children][0][id]", type="string", description="電號子層 ID 新增的時候 給0 或是產生新的uuid", example="0"),
     *                  @OA\Property(property="electric[0][children][0][type]", type="string", description="電號子層類型", example="electric"),
     *                  @OA\Property(property="electric[0][children][0][label]", type="string", description="電號子層標籤", example="12-electric-22"),
     *                  @OA\Property(property="electric[0][children][0][children]", type="string", example="[]"),
     *                 @OA\Property(property="del_fee_number[0]", type="string", description="刪除主層編號", example="228151e4-e9b6-4640-8f09-a58cb63b26d1"),
     *                 @OA\Property(property="del_fee_number_children[0]", type="string", description="刪除的費用子編號", example="228151e4-e9b6-4640-8f09-a58cb63b26d1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="修改成功"
     *     ),
     *     @OA\Response(response=301, description="網址跳轉"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *     @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *     @OA\Response(response=404, description="資源不存在，查無資料"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/configuration/{space_id}",
     *      tags={"Configuration 空間配置"},
     *      summary="刪除空間配置",
     *      description="刪除空間配置",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="space_id",
     *           in="path",
     *           required=true,
     *           description="",
     *           @OA\Schema(
     *               type="string",
     *           )
     *       ),
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
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *      path="/configuration/batch-delete",
     *      tags={"Configuration 空間配置"},
     *      summary="批次刪除空間配置",
     *      description="批次刪除空間配置",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="space_id[0]",type="string",description="待刪除的戶別 ID 列表")
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
    public function destroyAll()
    {
    }
}
