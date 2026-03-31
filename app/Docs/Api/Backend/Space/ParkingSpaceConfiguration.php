<?php

namespace App\Docs\Api\Backend\Space;

class ParkingSpaceConfiguration
{
    /**
     * @OA\Get(
     *     path="/parking-space-configuration",
     *     tags={"Parking-Space-Configuration 車位列表"},
     *     summary="車位列表",
     *     description="車位列表",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
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
     *      @OA\Parameter(
     *          name="filter_key[district_name]",
     *          in="query",
     *          required=false,
     *          example="區",
     *          @OA\Schema(type="string", nullable=false)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[building_name]",
     *          in="query",
     *          required=false,
     *          example="甲棟",
     *          @OA\Schema(type="string", nullable=true)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[staircase_name]",
     *          in="query",
     *          required=false,
     *          example="8梯",
     *          @OA\Schema(type="string", nullable=true)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[floor_name]",
     *          in="query",
     *          required=false,
     *          example="7F",
     *          @OA\Schema(type="string", nullable=true)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[household_name]",
     *          in="query",
     *          required=false,
     *          example="03F",
     *          @OA\Schema(type="string", nullable=true)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[main_application]",
     *          in="query",
     *          required=false,
     *          example="H010",
     *          description="法定車位名稱",
     *          @OA\Schema(type="string", nullable=true)
     *      ),
     *      @OA\Parameter(
     *          name="filter_key[block_id]",
     *          in="query",
     *          required=false,
     *          example="",
     *          @OA\Schema(type="string", nullable=true)
     *      ),
     *       @OA\Parameter(
     *           name="filter_key[car_type]",
     *           in="query",
     *           required=false,
     *           example="1",
     *           @OA\Schema(type="string", nullable=true)
     *       ),
     *     @OA\Parameter(
     *            name="filter_key[parking_number]",
     *            in="query",
     *            required=false,
     *            example="",
     *            @OA\Schema(type="string", nullable=true)
     *        ),
     *     @OA\Parameter(
     *            name="filter_key[parking_type]",
     *            in="query",
     *            required=false,
     *            example="",
     *            @OA\Schema(type="string", nullable=true)
     *        ),
     *      @OA\Parameter(
     *             name="filter_key[parking_attribute]",
     *             in="query",
     *             required=false,
     *             example="",
     *             @OA\Schema(type="string", nullable=true)
     *         ),
     *     @OA\Parameter(
     *            name="filter_key[parking_size]",
     *            in="query",
     *            required=false,
     *            example="",
     *            @OA\Schema(type="string", nullable=true)
     *        ),
     *      @OA\Parameter(
     *             name="filter_key[use_direction]",
     *             in="query",
     *             required=false,
     *             example="",
     *             @OA\Schema(type="string", nullable=true)
     *         ),
     *        @OA\Parameter(
     *              name="filter_key[use_direction]",
     *              in="query",
     *              required=false,
     *              example="",
     *              @OA\Schema(type="string", nullable=true)
     *          ),
     *     @OA\Parameter(
     *               name="filter_key[main_application]",
     *               in="query",
     *               required=false,
     *               example="",
     *               @OA\Schema(type="string", nullable=true)
     *           ),
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
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="perPage", type="integer", example=1),
     *                 @OA\Property(property="total", type="integer", example=5),
     *                 @OA\Property(property="lastPage", type="integer", example=5),
     *                 @OA\Property(property="next_url", type="string", example="https://laravel-leasehold.test/api/v1/parking-space-configuration?page=2"),
     *                 @OA\Property(property="prev_url", type="string", nullable=true),
     *                 @OA\Property(
     *                     property="list",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="car_space_id", type="string" ,description="車位空間配置id"),
     *                         @OA\Property(property="space_id", type="string",description="車位配置戶別id"),
     *                         @OA\Property(property="parking_number", type="string", description="車位編號"),
     *                         @OA\Property(property="car_type", type="integer", description="車位種類 0:機車 1:汽車 2:電動"),
     *                         @OA\Property(property="extent_of_ownership", type="string", description="車位編號"),
     *                         @OA\Property(property="extent_of_ownership_numerator", type="integer", description="(預售)權利範圍(分子)"),
     *                         @OA\Property(property="extent_of_ownership_denominator", type="integer", description="(預售)權利範圍(分母)"),
     *                         @OA\Property(property="default_extent_of_ownership", type="string", description="(預售)權利範圍"),
     *                         @OA\Property(property="default_extent_of_ownership_numerator", type="integer", description="(保存)權利範圍(分子)"),
     *                         @OA\Property(property="default_extent_of_ownership_denominator", type="integer", description="(保存)權利範圍(分母)"),
     *                         @OA\Property(property="parking_square_meter", type="integer", description="(保存)權利範圍"),
     *                         @OA\Property(property="parking_area", type="number", format="float", description="車位坪數(坪)"),
     *                         @OA\Property(property="land_square_meter", type="integer", description="土地持分"),
     *                         @OA\Property(property="default_parking_meter", type="integer", description="[預售]車位坪數(平方公尺)"),
     *                         @OA\Property(property="default_parking_area", type="number", format="float", description="[預售]車位坪數(坪)"),
     *                         @OA\Property(property="land_area", type="string", description="土地持分"),
     *                         @OA\Property(property="parking_type", type="string", description="車位類型"),
     *                         @OA\Property(property="parking_size", type="string", description="車位尺寸"),
     *                         @OA\Property(property="parking_attribute", type="string", description="車位屬性"),
     *                         @OA\Property(property="application", type="integer", description="車位法定名稱 0 :法定車位 1: 增設車位 2:獎勵車位 3: 殘障車位 4:訪客貴賓專用車位 "),
     *                         @OA\Property(property="use_direction", type="string", description="使用方式"),
     *                         @OA\Property(property="sell_price", type="integer", description="車位售價"),
     *                         @OA\Property(property="district", type="string", description="區"),
     *                         @OA\Property(property="district_name", type="string", description="區"),
     *                         @OA\Property(property="district_natsort", type="string", description="區"),
     *                         @OA\Property(property="building", type="string", description="棟"),
     *                         @OA\Property(property="building_name", type="string", description="棟"),
     *                         @OA\Property(property="building_natsort", type="string", description="棟"),
     *                         @OA\Property(property="staircase", type="string", nullable=true, description="梯"),
     *                         @OA\Property(property="staircase_name", type="string", nullable=true, description="梯"),
     *                         @OA\Property(property="staircase_natsort", type="string", nullable=true, description="梯"),
     *                         @OA\Property(property="floor", type="string", description="樓"),
     *                         @OA\Property(property="floor_name", type="string", description="樓"),
     *                         @OA\Property(property="floor_natsort", type="string", description="樓"),
     *                         @OA\Property(property="household", type="string", description="戶"),
     *                         @OA\Property(property="household_name", type="string", description="戶"),
     *                         @OA\Property(property="household_natsort", type="string", description="戶"),
     *                         @OA\Property(property="block_id", type="string", description="建號"),
     *                         @OA\Property(property="main_application", type="string", description="主要用途"),
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Get(
     *     path="/parking-space-configuration/create",
     *     tags={"Parking-Space-Configuration 車位列表"},
     *     summary="車位列表創建資料",
     *     description="車位列表創建資料",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
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
     *                     property="car_space",
     *                     type="array",
     *                     description="車位位置資料",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="space_id", type="string"),
     *                         @OA\Property(property="district", type="string"),
     *                         @OA\Property(property="district_name", type="string"),
     *                         @OA\Property(property="district_natsort", type="string"),
     *                         @OA\Property(property="building", type="string"),
     *                         @OA\Property(property="building_name", type="string"),
     *                         @OA\Property(property="building_natsort", type="string"),
     *                         @OA\Property(property="staircase", type="string", nullable=true),
     *                         @OA\Property(property="staircase_name", type="string", nullable=true),
     *                         @OA\Property(property="staircase_natsort", type="string", nullable=true),
     *                         @OA\Property(property="floor", type="string"),
     *                         @OA\Property(property="floor_name", type="string"),
     *                         @OA\Property(property="floor_natsort", type="string"),
     *                         @OA\Property(property="household", type="string"),
     *                         @OA\Property(property="household_name", type="string"),
     *                         @OA\Property(property="household_natsort", type="string"),
     *                         @OA\Property(property="block_id", type="string"),
     *                         @OA\Property(property="main_application", type="string")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="household_space",
     *                     type="array",
     *                     description="戶別資料",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="space_id", type="string"),
     *                         @OA\Property(property="household_name", type="string")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="parking_attribute",
     *                     type="array",
     *                     description="車位屬性資料",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="parking_type",
     *                     type="array",
     *                     description="車位類型",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="use_direction",
     *                     type="array",
     *                     description="使用方式",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="car_type",
     *                     type="array",
     *                     description="車位種類 0:機車 1:汽車 2:電動",
     *                     @OA\Items(type="integer")
     *                 ),
     *                 @OA\Property(
     *                     property="application",
     *                     type="array",
     *                     description="車位法定名稱 0 :法定車位 1: 增設車位 2:獎勵車位 3: 殘障車位 4:訪客貴賓專用車位 ",
     *                     @OA\Items(type="integer")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function create()
    {
    }

    /**
     * @OA\Post(
     *      path="/parking-space-configuration",
     *      tags={"Parking-Space-Configuration 車位列表"},
     *      summary="新增車位列表",
     *      description="新增車位列表",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"rental_and_sale", "car_type"},
     *                  @OA\Property(property="space_id", type="string", description="戶別的id", example="0ddb0a99-cd69-48b8-8021-2bbb0d1c08b5"),
     *                  @OA\Property(property="parking_number", type="string", description="車位編號", example="1111"),
     *                  @OA\Property(property="application", type="string", description="車位法定名稱 0:法定車位 1:增設車位 2:獎勵車位 3:殘障車位 4:訪客貴賓專用車位", enum={"0", "1", "2", "3", "4"}),
     *                  @OA\Property(property="parking_attribute", type="string", description="車位屬性"),
     *                  @OA\Property(property="use_direction", type="string", description="使用方式"),
     *                  @OA\Property(property="car_type", type="string", description="車位種類 0:機車 1:汽車 2:電動", enum={"0", "1", "2"}),
     *                  @OA\Property(property="parking_type", type="string", description="車位類型"),
     *                  @OA\Property(property="parking_size", type="string", description="車位尺寸"),
     *                  @OA\Property(property="sell_price", type="string", description="車位售價"),
     *                  @OA\Property(property="car_space_id", type="string", description="車位位置的id", example="0ddb0a99-cd69-48b8-8021-2bbb0d1c08b5"),
     *                  @OA\Property(property="default_extent_of_ownership_numerator", type="string", description="[預售]權利範圍(分子)"),
     *                  @OA\Property(property="default_extent_of_ownership_denominator", type="string", description="[預售]權利範圍(分母)"),
     *                  @OA\Property(property="default_parking_meter", type="string", description="[預售]車位坪數(平方公尺)"),
     *                  @OA\Property(property="default_parking_area", type="string", description="[預售]車位坪數(坪)"),
     *                  @OA\Property(property="extent_of_ownership_numerator", type="string", description="[保存]權利範圍(分子)", example="1"),
     *                  @OA\Property(property="extent_of_ownership_denominator", type="string", description="[保存]權利範圍(分母)", example="1111"),
     *                  @OA\Property(property="parking_square_meter", type="string", description="[保存]車位坪數(平方公尺)", example="1111"),
     *                  @OA\Property(property="parking_area", type="string", description="[保存]車位坪數(坪)", example="1111"),
     *                  @OA\Property(property="land_square_meter", type="string", description="土地持分(平方公尺)", example="1111"),
     *                  @OA\Property(property="land_area", type="string", description="土地持分(坪)", example="1111"),
     *                  @OA\Property(property="rental_and_sale", type="string", description="房屋租售狀態，rental (出租)、sell (出售)、notYet (無)", example="rental"),
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
     *     path="/parking-space-configuration/{uuid}/edit",
     *     tags={"Parking-Space-Configuration 車位列表"},
     *     summary="取得編輯車位資訊",
     *     description="取得編輯車位資訊",
     *     security={
     *           {"Authorization": {}},
     *           {"Community-Id-Header": {}}
     *       },
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *         type="string"
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
     *                 @OA\Property(
     *                     property="edit_data",
     *                     type="object",
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="company_id", type="integer"),
     *                     @OA\Property(property="comid", type="integer"),
     *                     @OA\Property(property="car_space_id", type="string"),
     *                     @OA\Property(property="space_id", type="string", nullable=true),
     *                     @OA\Property(property="parking_number", type="string"),
     *                     @OA\Property(property="car_type", type="integer"),
     *                     @OA\Property(property="extent_of_ownership", type="string"),
     *                     @OA\Property(property="extent_of_ownership_numerator", type="integer"),
     *                     @OA\Property(property="extent_of_ownership_denominator", type="integer"),
     *                     @OA\Property(property="default_extent_of_ownership", type="string"),
     *                     @OA\Property(property="default_extent_of_ownership_numerator", type="integer"),
     *                     @OA\Property(property="default_extent_of_ownership_denominator", type="integer"),
     *                     @OA\Property(property="parking_square_meter", type="integer"),
     *                     @OA\Property(property="parking_area", type="number"),
     *                     @OA\Property(property="land_square_meter", type="integer"),
     *                     @OA\Property(property="default_parking_meter", type="integer"),
     *                     @OA\Property(property="default_parking_area", type="number"),
     *                     @OA\Property(property="land_area", type="string"),
     *                     @OA\Property(property="parking_type", type="string"),
     *                     @OA\Property(property="parking_size", type="string"),
     *                     @OA\Property(property="parking_attribute", type="string"),
     *                     @OA\Property(property="application", type="integer"),
     *                     @OA\Property(property="use_direction", type="string"),
     *                     @OA\Property(property="sell_price", type="integer"),
     *                     @OA\Property(property="sign_date", type="string", nullable=true, format="date-time"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(property="default_total_area", type="string", description="預售總面積",),
     *                     @OA\Property(property="total_area", type="string", description="保存總面積",),
     *                     @OA\Property(property="rental_and_sale", type="string", description="房屋租售狀態，rental (出租)、sell (出售)、notYet (無)"),
     *                 ),
     *                 @OA\Property(
     *                     property="car_space",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="space_id", type="string"),
     *                         @OA\Property(property="district", type="string"),
     *                         @OA\Property(property="district_name", type="string"),
     *                         @OA\Property(property="district_natsort", type="string"),
     *                         @OA\Property(property="building", type="string"),
     *                         @OA\Property(property="building_name", type="string"),
     *                         @OA\Property(property="building_natsort", type="string"),
     *                         @OA\Property(property="staircase", type="string", nullable=true),
     *                         @OA\Property(property="staircase_name", type="string", nullable=true),
     *                         @OA\Property(property="staircase_natsort", type="string", nullable=true),
     *                         @OA\Property(property="floor", type="string"),
     *                         @OA\Property(property="floor_name", type="string"),
     *                         @OA\Property(property="floor_natsort", type="string"),
     *                         @OA\Property(property="household", type="string"),
     *                         @OA\Property(property="household_name", type="string"),
     *                         @OA\Property(property="household_natsort", type="string"),
     *                         @OA\Property(property="block_id", type="string"),
     *                         @OA\Property(property="main_application", type="string")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="household_space",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="space_id", type="string"),
     *                         @OA\Property(property="household_name", type="string")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="parking_attribute",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="parking_type",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="use_direction",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="car_type",
     *                     type="array",
     *                     @OA\Items(type="integer")
     *                 ),
     *                 @OA\Property(
     *                     property="application",
     *                     type="array",
     *                     @OA\Items(type="integer")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function edit()
    {
    }

    /**
     * @OA\Patch(
     *      path="/parking-space-configuration/{uuid}",
     *      tags={"Parking-Space-Configuration 車位列表"},
     *      summary="更新車位資訊",
     *      description="更新車位資訊",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *     @OA\Parameter(
     *            name="uuid",
     *            in="path",
     *            required=true,
     *            @OA\Schema(
     *                type="string"
     *            )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                 required={"rental_and_sale", "car_type"},
     *                  @OA\Property(property="space_id", type="string", description="戶別的id", example="0ddb0a99-cd69-48b8-8021-2bbb0d1c08b5"),
     *                  @OA\Property(property="parking_number", type="string", description="車位編號", example="1111"),
     *                  @OA\Property(property="application", type="string", description="車位法定名稱 0:法定車位 1:增設車位 2:獎勵車位 3:殘障車位 4:訪客貴賓專用車位", enum={"0", "1", "2", "3", "4"}),
     *                  @OA\Property(property="parking_attribute", type="string", description="車位屬性"),
     *                  @OA\Property(property="use_direction", type="string", description="使用方式"),
     *                  @OA\Property(property="car_type", type="string", description="車位種類 0:機車 1:汽車 2:電動", enum={"0", "1", "2"}),
     *                  @OA\Property(property="parking_type", type="string", description="車位類型"),
     *                  @OA\Property(property="parking_size", type="string", description="車位尺寸"),
     *                  @OA\Property(property="sell_price", type="string", description="車位售價"),
     *                  @OA\Property(property="car_space_id", type="string", description="車位位置的id", example="0ddb0a99-cd69-48b8-8021-2bbb0d1c08b5"),
     *                  @OA\Property(property="default_extent_of_ownership_numerator", type="string", description="[預售]權利範圍(分子)"),
     *                  @OA\Property(property="default_extent_of_ownership_denominator", type="string", description="[預售]權利範圍(分母)"),
     *                  @OA\Property(property="default_parking_meter", type="string", description="[預售]車位坪數(平方公尺)"),
     *                  @OA\Property(property="default_parking_area", type="string", description="[預售]車位坪數(坪)"),
     *                  @OA\Property(property="extent_of_ownership_numerator", type="string", description="[保存]權利範圍(分子)"),
     *                  @OA\Property(property="extent_of_ownership_denominator", type="string", description="[保存]權利範圍(分母)"),
     *                  @OA\Property(property="parking_square_meter", type="string", description="[保存]車位坪數(平方公尺)", example="1111"),
     *                  @OA\Property(property="parking_area", type="string", description="[保存]車位坪數(坪)"),
     *                  @OA\Property(property="land_square_meter", type="string", description="土地持分(平方公尺)"),
     *                  @OA\Property(property="land_area", type="string", description="土地持分(坪)"),
     *                  @OA\Property(property="rental_and_sale", type="string", description="房屋租售狀態，rental (出租)、sell (出售)、notYet (無)"),
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
     * @OA\Delete(
     *      path="/parking-space-configuration/{uuid}",
     *      tags={"Parking-Space-Configuration 車位列表"},
     *      summary="刪除車位配置",
     *      description="刪除車位配置",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="uuid",
     *           in="path",
     *           required=true,
     *           description="",
     *           @OA\Schema(
     *               type="string",
     *           )
     *       ),
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

    /**
     * @OA\Post(
     *      path="/parking-space-configuration/cancel/{uuid}",
     *      tags={"Parking-Space-Configuration 車位列表"},
     *      summary="車位取消配置戶別",
     *      description="車位取消配置戶別",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          description="車位 UUID",
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
     *                  @OA\Property(property="space_id", format="string", description="戶別id"),
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
    public function cancel()
    {
    }

    /**
     * @OA\Post(
     *      path="/parking-space-configuration/configuration/{uuid}",
     *      tags={"Parking-Space-Configuration 車位列表"},
     *      summary="車位配置戶別",
     *      description="車位配置戶別",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          description="車位 UUID",
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
     *                  @OA\Property(property="space_id", format="string", description="戶別id"),
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
    public function configuration()
    {
    }
}
