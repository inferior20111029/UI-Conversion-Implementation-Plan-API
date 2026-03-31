<?php

namespace App\Docs\Api\Backend\Space;

class rule
{
    /**
     * @OA\Get(
     *     path="/rule",
     *     tags={"Rule 空間規則"},
     *     summary="取得空間規則",
     *     description="取得空間規則的詳細資訊",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          description="組態類型 building:棟別 privacy:戶別 public公設 district:區 floor:樓層 staircase:梯間",
     *         @OA\Schema(
     *              type="string",
     *              enum={"building", "privacy", "public", "district", "floor", "staircase"}
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={
     *                 "code": 200,
     *                 "message": "取得成功",
     *                 "data": {
     *                     "list": {
     *                         {
     *                             "language": "區",
     *                             "language_id": 1,
     *                             "rule_data": {
     *                                 {
     *                                     "configuration_id": "53841b95-278b-4ccd-8740-38f729c7f426",
     *                                     "configuration_name": "基隆市中正區",
     *                                     "configuration_type": "district",
     *                                     "floor_type": ""
     *                                 }
     *                             }
     *                         }
     *                     },
     *                     "option_id": 2
     *                 }
     *             }
     *         )
     *     ),
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
     *      path="/rule",
     *      tags={"Rule 空間規則"},
     *      summary="建立空間規則",
     *      description="建立空間規則",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="language", format="string", description="空間規則名稱", example="規則一"),
     *                  @OA\Property(property="language_id", format="integer", description="空間規則id", example="1"),
     *                  @OA\Property(property="floor_type", format="string", description="樓層類型 ground:地上層 underground:地下層 intermediate:夾層 protrusion:突出物 public:公設 privacy:戶別", example="1"),
     *                  @OA\Property(property="configuration_name[0]", format="string", description="空間規則名稱", example="A區"),
     *                  @OA\Property(property="configuration_type", format="string", example="district", description="組態類型 building:棟別 privacy:戶別 public公設 district:區 floor:樓層 staircase:梯間"),
     *                  @OA\Property(property="default", format="string", description="預設值 true 是 false 否", example="true"),
     *                )
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
    public function store()
    {
    }

    /**
     * @OA\Post(
     *      path="/rule/",
     *      tags={"Rule 空間規則"},
     *      summary="建立樓層空間規則",
     *      description="建立樓層空間規則",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="language", format="string", description="空間規則名稱", example="樓層"),
     *                  @OA\Property(property="language_id[0]", format="integer", description="空間規則id", example="1"),
     *                  @OA\Property(property="configuration_type", format="string", example="floor", description="組態類型 building:棟別 privacy:戶別 public公設 district:區 floor:樓層 staircase:梯間"),
     *                  @OA\Property(property="total_floor", format="integer", description="樓層數量", example="20"),
     *                )
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
    public function storeFloor()
    {
    }

    /**
     * @OA\Get(
     *     path="/rule/{language_id}",
     *     tags={"Rule 空間規則"},
     *     summary="取得空間規則",
     *     description="取得指定語言 ID 和類型的空間規則的詳細資訊",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="language_id",
     *         in="path",
     *         required=true,
     *         description="語言 ID 用於查詢對應的空間規則",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="組態類型 building:棟別 privacy:戶別 public公設 district:區 floor:樓層 staircase:梯間",
     *         @OA\Schema(
     *             type="string",
     *             enum={"building", "privacy", "public", "district", "floor", "staircase"},
     *             default="district"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={
     *                 "code": 200,
     *                 "message": "取得成功",
     *                 "data": {
     *                     "language": "區",
     *                     "language_id": 1,
     *                     "rule_data": {
     *                         {
     *                             "configuration_id": "53841b95-278b-4ccd-8740-38f729c7f426",
     *                             "configuration_name": "基隆市中正區",
     *                             "configuration_type": "district",
     *                             "floor_type": ""
     *                         }
     *                     }
     *                 }
     *             }
     *         )
     *     ),
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
    public function show()
    {
    }

    /**
     * @OA\Patch(
     *      path="/rule/{id}",
     *      operationId="id",
     *      tags={"Rule 空間規則"},
     *      summary="修改空間規則",
     *      description="修改空間規則",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="語言 ID 用於查詢對應的空間規則",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="data[0][configuration_id]", format="string", example="5875c067-db94-4769-af08-27c6317d02b7", description="空間規則 ID"),
     *                  @OA\Property(property="data[0][configuration_name]", format="string", description="空間規則名稱", example="2F機車區1"),
     *                  @OA\Property(property="data[0][floor_type]", format="string", example="1", description="樓層類型 ground:地上層 underground:地下層 intermediate:夾層 protrusion:突出物 public:公設 privacy:戶別"),
     *                  @OA\Property(property="data[0][configuration_type]", format="string", example="household", description="組態類型 building:棟別 privacy:戶別 public公設 district:區 floor:樓層 staircase:梯間"),
     *                  @OA\Property(property="language", format="string", example="district",description="空間規則名稱"),
     *                  @OA\Property(property="default", format="string",example="true",description="預設值 true 是 false 否"),
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
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/rule/{id}",
     *      tags={"Rule 空間規則"},
     *      summary="刪除空間規則",
     *      description="刪除空間規則",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="",
     *           @OA\Schema(
     *               type="string",
     *           )
     *       ),
     *     @OA\Parameter(
     *          name="identity",
     *          in="query",
     *          required=true,
     *          description="規則刪除language,選項刪除configuration",
     *          @OA\Schema(
     *              type="string",
     *               enum={"language", "configuration"},
     *               default="language"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          description="組態類型 building:棟別 privacy:戶別 public公設 district:區 floor:樓層 staircase:梯間",
     *          @OA\Schema(
     *              type="string",
     *              enum={"building", "privacy", "public", "district", "floor", "staircase"},
     *              default="district"
     *          )
     *      ),
     *       @OA\Parameter(
     *            name="value",
     *            in="query",
     *            description="是configuration 才需要給值",
     *            @OA\Schema(
     *                type="string",
     *                default="district.1"
     *            )
     *        ),
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
}
