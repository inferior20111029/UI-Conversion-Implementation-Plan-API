<?php

namespace App\Docs\Api\Backend\certification;

class BuildingSpace
{
    /**
     * @OA\Get(
     *      path="/certification/building-space",
     *      tags={"certification Building Space 認證紀錄"},
     *      summary="建立戶別下認證紀錄",
     *      description="建立戶別下認證紀錄",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="參數類型 BERS: 建築能效標章, BCFD: 建築碳足跡認證, LEED: LEED綠建築標章, WELL: 國際WELL健康建築認證",
     *              enum={"BERS", "BCFD", "LEED", "WELL"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="space_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string", default="37a40c44-1017-456f-83de-480b63c5bd01")
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
     *                      @OA\Property(property="id", type="integer", example=23),
     *                      @OA\Property(property="name", type="string", example="113年度建築能效認證"),
     *                      @OA\Property(property="enable_state", type="integer", example=1),
     *                      @OA\Property(property="application_at", type="string", format="date-time", example="2023-06-17T16:00:00.000000Z"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-16T08:24:37.000000Z"),
     *                      @OA\Property(
     *                          property="avatar",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="uuid", type="string", example="91f3dc5a-d445-4bae-92f3-afea3000d931"),
     *                              @OA\Property(property="url", type="string", example="https://example.com/path/to/avatar1.jpg")
     *                          )
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
     * @OA\Post(
     *      path="/certification/building-space",
     *      tags={"certification Building Space 認證紀錄"},
     *      summary="建立戶別下認證紀錄",
     *      description="建立戶別下認證紀錄",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="space_id", type="string", description="戶別 uuid", example="37a40c44-1017-456f-83de-480b63c5bd01"),
     *                  @OA\Property(property="name", type="string", description="名稱", example="113年度建築能效認證"),
     *                  @OA\Property(property="type", type="string",
     *                                 description="參數類型 BERSn: 新建建築能效標章, BERSe: 既有建築能效標章, BCFD: 建築碳足跡認證, LEED: LEED綠建築標章, WELL: 國際WELL健康建築認證",
     *                                 enum={"BERSn", "BERSe", "BCFD", "LEED", "WELL"}),
     *                  @OA\Property(property="application_at", type="string", description="日期", example="2023-06-18"),
     *                  @OA\Property(property="enable_state", type="integer", description="0不啟用,1:啟用", example="1"),
     *                  @OA\Property(property="file_id[0]", type="string", description="認證標章 uuid 檔案", example="f5dc3def-70f7-494e-8972-8ba971bb41a4"),
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
     * @OA\Patch(
     *      path="/certification/building-space/{id}",
     *      tags={"certification Building Space 認證紀錄"},
     *      summary="編輯戶別下認證紀錄",
     *      description="編輯戶別下認證紀錄 , 編輯有送檔案的時候會刪掉原來的~~",
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
     *                   @OA\Property(property="name", type="string", description="名稱", example="113年度建築能效認證"),
     *                   @OA\Property(property="application_at", type="string", description="日期", example="2023-06-18"),
     *                   @OA\Property(property="file_id[0]", type="string", description="認證標章 uuid 檔案 ", example="f5dc3def-70f7-494e-8972-8ba971bb41a4"),
     *                   @OA\Property(property="enable_state", type="integer", description="0不啟用,1:啟用", example="1"),
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
     *       path="/certification/building-space/{id}",
     *       tags={"certification Building Space 認證紀錄"},
     *       summary="刪除認證資料",
     *       description="刪除認證資料",
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

    /**
     * @OA\Post(
     *      path="/certification/batch",
     *      tags={"certification Building Space 認證紀錄", "Batch-Setting 專有批次設定"},
     *      summary="批次建立戶別下認證紀錄",
     *      description="批次建立戶別下認證紀錄",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\RequestBody(
     *           @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="space_id[0]", type="string", description="戶別 uuid", example="37a40c44-1017-456f-83de-480b63c5bd01"),
     *                  @OA\Property(property="data[0][name]", type="string", description="名稱", example="113年度建築能效認證"),
     *                  @OA\Property(property="data[0][type]", type="string",
     *                                 description="參數類型 BERSn: 新建建築能效標章, BERSe: 既有建築能效標章, BCFD: 建築碳足跡認證, LEED: LEED綠建築標章, WELL: 國際WELL健康建築認證",
     *                                 enum={"BERSn", "BERSe", "BCFD", "LEED", "WELL"}),
     *                  @OA\Property(property="data[0][application_at]", type="string", description="日期", example="2023-06-18"),
     *                  @OA\Property(property="data[0][file_id]", type="string", description="認證標章 uuid 檔案", example="f5dc3def-70f7-494e-8972-8ba971bb41a4"),
     *                 @OA\Property(property="data[0][enable_state]", type="integer", description="0不啟用,1:啟用", example="1"),
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
    public function batch()
    {
    }
}
