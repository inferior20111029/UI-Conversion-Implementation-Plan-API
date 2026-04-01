<?php

namespace App\Docs\Api\Backend\Space\BatchSetting;

class TransferOwnerTitle
{
    /**
     * @OA\Post(
     *     path="/batch-setting/transfer-owner-title",
     *     tags={"Batch-Setting 專有批次設定"},
     *     summary="專有批次過戶",
     *     description="針對有設定的(立約人)之戶別 轉成(過戶成) 所有權人",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="transfer_date", type="string", description="過戶日期", example="2024-02-11"),
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

