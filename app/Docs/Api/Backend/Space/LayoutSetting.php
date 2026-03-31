<?php

namespace App\Docs\Api\Backend\Space;

class LayoutSetting
{
    /**
     * @OA\Get(
     *     path="/space-layout-setting",
     *     summary="格局設定 列表",
     *     description="格局設定 列表",
     *     tags={"space-layout-setting 格局設定"},
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
     *          name="name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
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
     *                 @OA\Property(property="perPage", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=8),
     *                 @OA\Property(property="lastPage", type="integer", example=1),
     *                 @OA\Property(property="next_url", type="string", format="uri", example=null),
     *                 @OA\Property(property="prev_url", type="string", format="uri", example=null),
     *                 @OA\Property(
     *                     property="list",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="一房一廳一衛浴")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *        @OA\Response(response=201, description="建立成功"),
     *        @OA\Response(response=301, description="網址跳轉"),
     *        @OA\Response(response=400, description="參數錯誤"),
     *        @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *        @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *        @OA\Response(response=404, description="資源不存在，查無資料"),
     *        @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *      path="/space-layout-setting",
     *      tags={"space-layout-setting 格局設定"},
     *      summary="建立格局設定",
     *      description="建立格局設定",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", format="string", description="格局名稱", example="一房一廳一衛浴"),
     *                  @OA\Property(property="room", format="integer", description="房間", example="2"),
     *                  @OA\Property(property="living_room", format="integer", description="客廳", example="2"),
     *                  @OA\Property(property="kitchen", format="integer", description="廚房", example="2"),
     *                  @OA\Property(property="bathroom", format="integer", description="衛浴", example="2"),
     *                  @OA\Property(property="balcony", format="integer", description="陽台", example="2"),
     *                  @OA\Property(property="type", format="integer", description="0:公寓 1: 透天", example="1"),
     *                  @OA\Property(property="floor_type", format="string", example="ground", description="ground:地上層, underground:地下層, intermediate:夾層, protrusion:突出物", enum={"ground", "underground", "intermediate", "protrusion"}),
     *                )
     *          )
     *      ),
     *       @OA\Response(response=201, description="建立成功"),
     *       @OA\Response(response=301, description="網址跳轉"),
     *       @OA\Response(response=400, description="參數錯誤"),
     *       @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *       @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *       @OA\Response(response=404, description="資源不存在，查無資料"),
     *       @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *     path="/space-layout-setting/{id}/edit",
     *     summary="編輯格局設定資料",
     *     description="編輯格局設定資料",
     *     tags={"space-layout-setting 格局設定"},
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=9),
     *         description="格局設定的id",
     *     ),
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
     *                 @OA\Property(property="id", type="integer", example=27),
     *                 @OA\Property(property="name", type="string", example="一房一廳六衛浴"),
     *                 @OA\Property(property="type", type="integer", example=1),
     *                 @OA\Property(property="floor_type", type="string", example="55"),
     *                 @OA\Property(property="room", type="integer", example=3),
     *                 @OA\Property(property="living_room", type="integer", example=2),
     *                 @OA\Property(property="kitchen", type="integer", example=4),
     *                 @OA\Property(property="bathroom", type="integer", example=2),
     *                 @OA\Property(property="balcony", type="integer", example=2)
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
    public function edit()
    {
    }

    /**
     * @OA\PATCH(
     *      path="/space-layout-setting/{id}",
     *      tags={"space-layout-setting 格局設定"},
     *      summary="建立格局設定",
     *      description="建立格局設定",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="格局 ID",
     *           @OA\Schema(
     *               type="integer",
     *               format="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", format="string", description="格局名稱", example="一房一廳一衛浴"),
     *                  @OA\Property(property="room", format="integer", description="房間", example="2"),
     *                  @OA\Property(property="living_room", format="integer", description="客廳", example="0"),
     *                  @OA\Property(property="kitchen", format="integer", description="廚房", example="5"),
     *                  @OA\Property(property="bathroom", format="integer", description="衛浴", example="8"),
     *                  @OA\Property(property="balcony", format="integer", description="陽台", example="0"),
     *                  @OA\Property(property="type", format="integer", description="0:公寓 1: 透天", example="1"),
     *                  @OA\Property(property="floor_type", format="string", example="ground",
     *                               description="ground:地上層, underground:地下層, intermediate:夾層, protrusion:突出物",
     *                               enum={"ground", "underground", "intermediate", "protrusion"}),
     *                )
     *          )
     *      ),
     *       @OA\Response(response=201, description="建立成功"),
     *       @OA\Response(response=301, description="網址跳轉"),
     *       @OA\Response(response=400, description="參數錯誤"),
     *       @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *       @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *       @OA\Response(response=404, description="資源不存在，查無資料"),
     *       @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/space-layout-setting/{id}",
     *      tags={"space-layout-setting 格局設定"},
     *      summary="刪除格局設定",
     *      description="刪除格局設定",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="格局 ID",
     *           @OA\Schema(
     *               type="integer",
     *               format="integer"
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
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *      path="/space-layout-setting/batch-destroy",
     *      tags={"space-layout-setting 格局設定"},
     *      summary="批次刪除格局",
     *      description="批次刪除格局",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *          @OA\RequestBody(
     *            @OA\MediaType(
     *                mediaType="application/x-www-form-urlencoded",
     *                @OA\Schema(
     *                    @OA\Property(property="ids[0]", format="string", description="刪除格局id", example="1"),
     *                  )
     *            )
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
    public function batchDestroy()
    {
    }
}
