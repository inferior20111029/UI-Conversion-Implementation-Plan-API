<?php

namespace App\Docs\Api\Backend\Space;

class ConfigurationCommon
{
    /**
     * @OA\Get(
     *     path="/configuration-common",
     *     tags={"Configuration common 空間配置-公共空間"},
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
     *         name="filter_key[district_name]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=false)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[building_name]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[staircase_name]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[floor_name]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[household_name]",
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
     *                          "block_id": "94-87",
     *                          "doorplate": "台灣省嘉義縣溪口鄉民族西路26號",
     *                          "main_application_value": "汽車停車場（增設）"
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
     * @OA\Post(
     *      path="/configuration-common",
     *      tags={"Configuration common 空間配置-公共空間"},
     *      summary="建立公共空間配置",
     *      description="建立公共空間配置",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="block_id", type="string", description="建號", example="94-87"),
     *                  @OA\Property(property="pre_sale_total_area", type="string", description="預售-建物總面積", example="94"),
     *                  @OA\Property(property="preserved_total_area", type="string", description="保存-建物總面積", example="87"),
     *                  @OA\Property(property="doorplate", type="string", description="門牌", example="1f87"),
     *                  @OA\Property(property="tax_id", type="string", description="稅籍號碼", example="11008"),
     *                  @OA\Property(
     *                      property="land_use_zoning",
     *                      type="string",
     *                      description="土地使用分區 住宅:residential 商用:commercial",
     *                      enum={"residential", "commercial"},
     *                      example="residential"
     *                  ),
     *                  @OA\Property(
     *                      property="main_application",
     *                      type="string",
     *                      description="主要用途: H001-住家, H002-套房, H004-店鋪, H005-商場, H006-辦公室, H007-合併戶, H008-汽車停車場(法定), H009-汽車停車場(獎勵), H010-汽車停車場(增設), H011-機車停車場(法定), H012-機車停車場(獎勵), H013-機車停車場(增設), H014-公共空間",
     *                      enum={"H001", "H002", "H004", "H005", "H006", "H007", "H008", "H009", "H010", "H011", "H012", "H013", "H014"},
     *                      example="H014"
     *                  ),
     *                  @OA\Property(property="building_build_licence_id", type="string", description="建照執照號碼", example="94建字第0087號"),
     *                  @OA\Property(property="use_license_id", type="string", description="使用執照號碼", example="101使字第0155號"),
     *                  @OA\Property(property="locate", type="string", description="坐落", example="新北市"),
     *                  @OA\Property(property="extent_of_ownership", type="string", description="權力範圍", example="100000分之487"),
     *                  @OA\Property(property="land_area", type="string", description="土地面積", example="94"),
     *                  @OA\Property(property="building_area", type="string", description="建案面積", example="87"),
     *                  @OA\Property(property="space_id[0]", type="string", description="公共空間 id", example="00f7c4ea-9d25-4c2c-b9e8-6b1f6f32a679")
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
     *     path="/configuration-common/{id}",
     *     tags={"Configuration common 空間配置-公共空間"},
     *     summary="檢視公有空間配置資料",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
     *                 @OA\Property(
     *                     property="space_id",
     *                     type="string",
     *                     example="966c9657-2e50-42c2-8270-758e7e286363"
     *                 ),
     *                 @OA\Property(property="locate", type="string", description="坐落", example="新北??"),
     *                 @OA\Property(property="pre_sale_total_area", type="string", description="預售-建物總面積", example="94"),
     *                 @OA\Property(property="preserved_total_area", type="string", description="保存-建物總面積", example="87"),
     *                 @OA\Property(property="doorplate", type="string", example="dooe"),
     *                 @OA\Property(property="block_id", type="string", example="123654"),
     *                 @OA\Property(property="tax_id", type="string", example="7889"),
     *                 @OA\Property(property="building_build_licence_id", type="string", description="建照執照號碼", example="94建字第0087號"),
     *                 @OA\Property(property="use_license_id", type="string", description="使用執照號碼", example="101使字第0155號"),
     *                 @OA\Property(property="extent_of_ownership", type="string", description="權力範圍", example="****100000分之487*******"),
     *                 @OA\Property(property="land_area", type="string", description="土地面積", example="94"),
     *                 @OA\Property(property="building_area", type="string", description="建案面積", example="87"),
     *                 @OA\Property(
     *                      property="space",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="space_id", type="string", example="966c9657-2e50-42c2-8270-758e7e286363"),
     *                          @OA\Property(property="district_name", type="string", example="A區", description="區"),
     *                          @OA\Property(property="building_name", type="string", example="B棟", description="棟"),
     *                          @OA\Property(property="staircase_name", type="string", example="梯", description="梯"),
     *                          @OA\Property(property="floor_name", type="string", example="1F", description="樓"),
     *                          @OA\Property(property="household_name", type="string", example="03F", description="戶")
     *                      )
     *                  ),
     *             )
     *         )
     *     ),
     *  @OA\Response(response=201, description="建立成功"),
     *  @OA\Response(response=301, description="網址跳轉"),
     *  @OA\Response(response=400, description="參數錯誤"),
     *  @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *  @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *  @OA\Response(response=404, description="資源不存在，查無資料"),
     *  @OA\Response(response=500, description="程式錯誤")
     * ),
     */
    public function show()
    {
    }

    /**
     * @OA\Patch(
     *      path="/configuration-common/{id}",
     *      tags={"Configuration common 空間配置-公共空間"},
     *      summary="修改公共空間配置",
     *      description="修改公共空間配置",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           @OA\Schema(
     *               type="integer"
     *           )
     *       ),
     *      @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(
     *                  @OA\Property(property="block_id", type="string", description="建號", example="94-87"),
     *                  @OA\Property(property="pre_sale_total_area", type="string", description="預售-建物總面積", example="94"),
     *                  @OA\Property(property="preserved_total_area", type="string", description="保存-建物總面積", example="87"),
     *                  @OA\Property(property="doorplate", type="string", description="門牌", example="1f87"),
     *                  @OA\Property(property="tax_id", type="string", description="稅籍號碼", example="11008"),
     *                  @OA\Property(property="land_use_zoning", type="string", description="土地使用分區 住宅:residence 商用: commercial", example="11008", enum={"residential", "business"}),
     *                  @OA\Property(
     *                       property="main_application",
     *                       type="string",
     *                       description="主要用途: H001-住家, H002-套房, H004-店鋪, H005-商場, H006-辦公室, H007-合併戶, H008-汽車停車場(法定), H009-汽車停車場(獎勵), H010-汽車停車場(增設), H011-機車停車場(法定), H012-機車停車場(獎勵), H013-機車停車場(增設), H014-公共空間",
     *                       enum={"H001", "H002", "H004", "H005", "H006", "H007", "H008", "H009", "H010", "H011", "H012", "H013", "H014"}
     *                   ),
     *                  @OA\Property(property="building_build_licence_id", type="string", description="建照執照號碼", example="94建字第0087號"),
     *                  @OA\Property(property="use_license_id", type="string", description="使用執照號碼", example="101使字第0155號"),
     *                  @OA\Property(property="locate", type="string", description="坐落", example="新北??"),
     *                  @OA\Property(property="extent_of_ownership", type="string", description="權力範圍", example="****100000分之487*******"),
     *                  @OA\Property(property="land_area", type="string", description="土地面積", example="94"),
     *                  @OA\Property(property="building_area", type="string", description="建案面積", example="87"),
     *                  @OA\Property(property="space_id[0]", type="string", description="新增公共空間 id", example="00f7c4ea-9d25-4c2c-b9e8-6b1f6f32a679"),
     *                  @OA\Property(property="del_space_id[0]", type="string", description="刪除公共空間 id", example="00f7c4ea-9d25-4c2c-b9e8-6b1f6f32a679"),
     *               )
     *           )
     *       ),
     *      @OA\Response(response=200, description="修改成功"),
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
     * @OA\Delete(
     *      path="/configuration-common/{space_id}",
     *      tags={"Configuration common 空間配置-公共空間"},
     *      summary="刪除公有空間配置",
     *      description="刪除公有空間配置",
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
     *      @OA\Response(response=200, description="刪除成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     *      )
     */
    public function destroy()
    {
    }
}
