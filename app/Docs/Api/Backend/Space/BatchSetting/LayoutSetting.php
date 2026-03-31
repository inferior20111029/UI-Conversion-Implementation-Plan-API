<?php

namespace App\Docs\Api\Backend\Space\BatchSetting;

class LayoutSetting
{
    /**
     * @OA\Post(
     *     path="/batch-setting/layout",
     *     tags={"Batch-Setting 專有批次設定"},
     *     summary="批次設定格局",
     *     description="批次設定格局 crm_layout_setting_id 格局群組 自訂 帶 data",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="crm_layout_setting_id", type="integer", description="格局設定ID 群駔使用 自訂不用帶", example=2),
     *                 @OA\Property(property="data[room]", type="integer", description="房間 自訂使用 ", example=2),
     *                 @OA\Property(property="data[living_room]", type="integer", description="客廳 自訂使用 群駔不用帶", example=2),
     *                 @OA\Property(property="data[kitchen]", type="integer", description="廚房 自訂使用 群駔不用帶", example=4),
     *                 @OA\Property(property="data[bathroom]", type="integer", description="浴室 自訂使用 群駔不用帶", example=5),
     *                 @OA\Property(property="data[balcony]", type="integer", description="陽台 自訂使用 群駔不用帶", example=6),
     *                 @OA\Property(property="space_id[0]", type="string", description="空間ID 1", example="01161261-506f-4720-8c3c-e0369e61481b"),
     *                 @OA\Property(property="space_id[1]", type="string", description="空間ID 2", example="06c55b0e-757e-4734-823d-56d295be681e")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="批量設定成功"),
     *     @OA\Response(response=400, description="參數錯誤"),
     *     @OA\Response(response=401, description="無效的 Token"),
     *     @OA\Response(response=403, description="權限不足"),
     *     @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function index()
    {
    }
}

