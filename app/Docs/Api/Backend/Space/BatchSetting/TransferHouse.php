<?php

namespace App\Docs\Api\Backend\Space\BatchSetting;

class TransferHouse
{
    /**
     * @OA\Post(
     *     path="/batch-setting/transfer-house",
     *     tags={"Batch-Setting 專有批次設定"},
     *     summary="批次列入資產",
     *     description="批次列入資產 已出售之預售屋不得列入資產",
     *     security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
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

