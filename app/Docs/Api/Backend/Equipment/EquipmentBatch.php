<?php

namespace App\Docs\Api\Backend\Equipment;

class EquipmentBatch
{
    /**
     * @OA\Patch(
     *      path="/equipment/batch/update",
     *      tags={"Equipment 元件庫"},
     *      summary="批次更新元件",
     *      description="批次更新元件",
     *      security={
     *          {"Authorization": {}},
     *          {"Community-Id-Header": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="ids[0]", type="integer", description="元件id", example="1"),
     *                  @OA\Property(property="properties", type="string", description="元件屬性", example="{'4.00': {'name': '電氣屬性', 'value': '', 'expand': true, 'properties': {'4.01': {'name': '電壓', 'value': '', 'properties': []}, '4.02': {'name': '電流', 'value': '', 'properties': []}, '4.03': {'name': '頻率', 'value': '', 'properties': []}, '4.04': {'name': '功率', 'value': '', 'properties': []}, '4.05': {'name': '相位', 'value': '', 'properties': []}, '4.06': {'name': '負載分類', 'value': '', 'properties': []}, '4.07': {'name': '導管大小', 'value': '', 'properties': []}, '4.08': {'name': '其他電氣屬性', 'value': '', 'properties': []}}}, '5.00': {'name': '照明屬性', 'value': '', 'expand': true, 'properties': {'5.01': {'name': '照度', 'value': '', 'properties': []}, '5.02': {'name': '明度', 'value': '', 'properties': []}, '5.03': {'name': '光通量', 'value': '', 'properties': []}, '5.04': {'name': '色溫', 'value': '', 'properties': []}, '5.05': {'name': '發光強度', 'value': '', 'properties': []}, '5.06': {'name': '其他照明屬性', 'value': '', 'properties': []}}}, '6.00': {'name': '空調屬性', 'value': '', 'expand': true, 'properties': {'6.01': {'name': '密度', 'value': '', 'properties': []}, '6.02': {'name': '氣體摩擦力', 'value': '', 'properties': []}, '6.03': {'name': '溫度', 'value': '', 'properties': []}, '6.04': {'name': '風速', 'value': '', 'properties': []}, '6.05': {'name': '氣體流量', 'value': '', 'properties': []}, '6.06': {'name': '風管大小', 'value': '', 'properties': []}, '6.07': {'name': '其他空調屬性', 'value': '', 'properties': []}}}, '7.00': {'name': '水資源屬性', 'value': '', 'expand': true, 'properties': {'7.01': {'name': '液體流量', 'value': '', 'properties': []}, '7.02': {'name': '液體摩擦力', 'value': '', 'properties': []}, '7.03': {'name': '速度', 'value': '', 'properties': []}, '7.04': {'name': '液體壓力', 'value': '', 'properties': []}, '7.05': {'name': '水管大小', 'value': '', 'properties': []}, '7.06': {'name': '其他水資源屬性', 'value': '', 'properties': []}}}, '8.00': {'name': '366', 'value': '', 'expand': true, 'properties': {'8.01': {'name': '999', 'value': '', 'properties': []}}}}"),
     *                  @OA\Property(property="files_type[file_built_drawing][0]", type="string", description="元件檔案UUID", example="29ec6691-0a90-4700-a890-bf8c59f437bf"),
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
     *      path="/equipment/batch/delete",
     *      tags={"Equipment 元件庫"},
     *      summary="批次刪除元件",
     *      description="批次刪除元件",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *           name="ids[0]",
     *           in="path",
     *           required=true,
     *           description="",
     *           @OA\Schema(
     *               type="integer",
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
}
