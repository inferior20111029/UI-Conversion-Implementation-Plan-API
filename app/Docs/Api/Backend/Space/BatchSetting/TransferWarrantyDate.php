<?php

namespace App\Docs\Api\Backend\Space\BatchSetting;

class TransferWarrantyDate
{
    /**
     * @OA\Post(
     *     path="/batch-setting/transfer-warranty-date",
     *     tags={"Batch-Setting 專有批次設定"},
     *     summary="專有批次設定交屋 & 保固日期",
     *     description="專有批次設定交屋 & 保固日期  保固資料在 /warranty 這隻api ",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="handover_date", type="string", description="交屋日期", example="2024-02-11"),
     *                 @OA\Property(property="warranty[0][id]", type="integer", description="保固 ID 沒資料不用帶", example="1"),
     *                 @OA\Property(property="warranty[0][date]", type="string", description="保固日期 沒資料不用帶", example="2024-02-11"),
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

