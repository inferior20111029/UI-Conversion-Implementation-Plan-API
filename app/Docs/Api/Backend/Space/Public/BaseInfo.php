<?php

namespace App\Docs\Api\Backend\Space\Public;

class BaseInfo
{

    /**
     * @OA\Get(
     *     path="/public/base-info",
     *     tags={"Public Base Info 公(有)設基本資料"},
     *     summary="公設列表",
     *     description="公設列表",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *          @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *      @OA\Parameter(
     *          name="perPage",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", default=10)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="perPage", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=1),
     *                 @OA\Property(property="lastPage", type="integer", example=1),
     *                 @OA\Property(property="nextUrl", type="string", example=""),
     *                 @OA\Property(property="prevUrl", type="string", example=""),
     *                 @OA\Property(
     *                     property="list",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *
     *                         @OA\Property(property="building_common_info_id", type="integer", example="20", description="用來編輯空間配置的id"),
     *                         @OA\Property(property="block_id", type="string", example="11-11", description="建號"),
     *                         @OA\Property(property="pre_sale_total_area", type="number", format="float", example=90, description="預售-建號總面積（平方公尺）"),
     *                         @OA\Property(property="pre_sale_total_area_ping", type="number", format="float", example=27.225, description="預售-建號總面積（坪）"),
     *                         @OA\Property(property="preserved_total_area", type="number", format="float", example=80, description="保存-建號總面積（平方公尺）"),
     *                         @OA\Property(property="preserved_total_area_ping", type="number", format="float", example=24.2, description="保存-建號總面積（坪）"),
     *                         @OA\Property(
     *                             property="spaces",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="space_id", type="string", example="c5853c60-a980-4cb6-a6e3-8508aac68bc7"),
     *                                 @OA\Property(property="district_name", type="string", example="區名1"),
     *                                 @OA\Property(property="building_name", type="string", example="棟別1"),
     *                                 @OA\Property(property="staircase_name", type="string", example="梯"),
     *                                 @OA\Property(property="floor_name", type="string", example="樓層1"),
     *                                 @OA\Property(property="household_name", type="string", example="03f")
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
    public function index()
    {
    }

    /**
     * @OA\Get(
     *       path="/public/base-info/{space_id}",
     *       tags={"Public Base Info 公(有)設基本資料"},
     *       summary="產權基本資訊",
     *       description="產權基本資訊",
     *       security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *       @OA\Parameter(
     *            name="space_id",
     *            in="path",
     *            required=true,
     *            @OA\Schema(
     *                type="string",
     *                description="空間 UUID",
     *                default="37a40c44-1017-456f-83de-480b63c5bd01",
     *            )
     *        ),
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
     *                  @OA\Property(property="space_id", type="string", example="00f7c4ea-9d25-4c2c-b9e8-6b1f6f32a679", description="空間id"),
     *                  @OA\Property(property="household_name", type="string", example="11B", description="空間名稱"),
     *                  @OA\Property(property="district_name", type="string", example="B", description="區"),
     *                  @OA\Property(property="building_name", type="string", example="甲棟", description="棟"),
     *                  @OA\Property(property="staircase_name", type="string", example="梯1", description="梯"),
     *                  @OA\Property(property="floor_name", type="string", example="11F", description="樓"),
     *                  @OA\Property(property="block_id", type="string", example=null, description="建號"),
     *                  @OA\Property(property="doorplate", type="string", example="1f87", description="門牌"),
     *                  @OA\Property(property="land_use_zoning", type="string", description="土地使用分區 住宅:residence 商用: commercial", example="residence"),
     *                  @OA\Property(property="main_application", type="string", description="主要用途"),
     *                  @OA\Property(property="locate", type="string", example="新北??", description="座落"),
     *                  @OA\Property(property="extent_of_ownership", type="string", example="****100000分之487*******", description="權利範圍"),
     *                  @OA\Property(property="building_build_licence_id", type="string", example="94建字第0087號", description="建造執照號碼"),
     *                  @OA\Property(property="use_license_id", type="string", example="101使字第0155號", description="使用執照號碼"),
     *                  @OA\Property(property="land_area", type="integer", example=94, description="土地面積"),
     *                  @OA\Property(property="building_area", type="integer", example=87, description="建物面積"),
     *                  @OA\Property(property="is_edit", type="integer", example="true 可以編輯 false 可以新增", description="建物面積"),
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
    public function show()
    {
    }

    /**
     * @OA\Post(
     *      path="/public/base-info/",
     *      tags={"Public Base Info 公(有)設基本資料"},
     *      summary="新增公設基本資訊",
     *      description="新增公設基本資訊",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="space_id", type="string", description="戶別 uuid", example="37a40c44-1017-456f-83de-480b63c5bd01"),
     *                  @OA\Property(property="serial_number", type="string", description="公設序號", example="12"),
     *                  @OA\Property(property="machine_number", type="string", description="門禁機機號", example="11-879-9X87-55-5X87"),
     *                  @OA\Property(property="type", type="string", description="公設類別 0:一版門禁 1:其他休閒設施", example="0"),
     *                  @OA\Property(property="introduction", type="string", description="公設介紹", example="這是泳池，請勿下水"),
     *                  @OA\Property(property="management_measures_text", type="string", description="管理辦法 文字版", example="下水"),
     *                  @OA\Property(property="prohibit", type="string", description="禁止", example="true"),
     *                  @OA\Property(property="house_viewing", type="string", description="賞屋注意事項", example="不能參觀"),
     *                  @OA\Property(property="management_measures_file", type="string", description="管理辦法檔案 uuid 檔案, 沒有就不用給", example="f5dc3def-70f7-494e-8972-8ba971bb41a4"),
     *                  @OA\Property(property="picture", type="string", description="公設照片 uuid 檔案, 沒有就不用給", example="f5dc3def-70f7-494e-8972-8ba971bb41a4"),
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
     *       path="/public/base-info/{space_id}/edit",
     *       tags={"Public Base Info 公(有)設基本資料"},
     *       summary="產權基本資訊(編輯資料)",
     *       description="產權基本資訊(編輯資料)",
     *       security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *       @OA\Parameter(
     *             name="space_id",
     *             in="path",
     *             required=true,
     *             @OA\Schema(
     *                 type="string",
     *                 description="空間 UUID",
     *                 default="37a40c44-1017-456f-83de-480b63c5bd01"
     *             )
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
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="space_id", type="string", example="00f7c4ea-9d25-4c2c-b9e8-6b1f6f32a679"),
     *                  @OA\Property(property="serial_number", type="string", example="1"),
     *                  @OA\Property(property="machine_number", type="string", example="11-879-9X87-55-5X87"),
     *                  @OA\Property(property="type", type="integer", example=0),
     *                  @OA\Property(property="introduction", type="string", example="TWIB泳池"),
     *                  @OA\Property(property="is_file", type="boolean", example=false),
     *                  @OA\Property(property="management_measures_text", type="string", example="水很深，請勿下水"),
     *                  @OA\Property(property="prohibit", type="string", description="禁止", example="true"),
     *                  @OA\Property(property="house_viewing", type="string", description="賞屋注意事項", example="不能參觀"),
     *                  @OA\Property(
     *                      property="management_measures_file",
     *                      type="object",
     *                      @OA\Property(property="file_uuid", type="string", example="84ccb80a-29f1-4c10-92b8-b5468646cc8e"),
     *                      @OA\Property(property="file_name", type="string", example="泳池不落水管理辦法"),
     *                      @OA\Property(property="url", type="string", example="")
     *                  ),
     *                  @OA\Property(
     *                      property="picture",
     *                      type="object",
     *                      @OA\Property(property="file_uuid", type="string", example="84ccb80a-29f1-4c10-92b8-b5468646cc8e"),
     *                      @OA\Property(property="file_name", type="string", example="8-A泳池地放照"),
     *                      @OA\Property(property="url", type="string", example="")
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
     *        path="/public/base-info/{id}",
     *        tags={"Public Base Info 公(有)設基本資料"},
     *        summary="產權基本資訊(更新資料)",
     *        description="產權基本資訊(更新資料)",
     *        security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *        @OA\Parameter(
     *                name="id",
     *                in="path",
     *                required=true,
     *                @OA\Schema(
     *                    type="string",
     *                    description="空間 ID"
     *                )
     *         ),
     *        @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(
     *                  @OA\Property(property="serial_number", type="string", description="公設序號", example="12"),
     *                  @OA\Property(property="machine_number", type="string", description="門禁機機號", example="11-879-9X87-55-5X87"),
     *                  @OA\Property(property="type", type="string", description="公設類別 0:一版門禁 1:其他休閒設施", example="0"),
     *                  @OA\Property(property="introduction", type="string", description="公設介紹", example="這是泳池，請勿下水"),
     *                  @OA\Property(property="management_measures_text", type="string", description="管理辦法 文字版", example="下水"),
     *                  @OA\Property(property="prohibit", type="string", description="禁止", example="true"),
     *                  @OA\Property(property="house_viewing", type="string", description="賞屋注意事項", example="不能參觀"),
     *                  @OA\Property(property="management_measures_file", type="string", description="管理辦法檔案 uuid 檔案, 沒有就不用給", example="f5dc3def-70f7-494e-8972-8ba971bb41a4"),
     *                  @OA\Property(property="picture", type="string", description="公設照片 uuid 檔案, 沒有就不用給", example="f5dc3def-70f7-494e-8972-8ba971bb41a4")
     *                )
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
}
