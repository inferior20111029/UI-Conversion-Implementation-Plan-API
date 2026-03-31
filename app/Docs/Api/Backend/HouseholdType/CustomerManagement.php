<?php

namespace App\Docs\Api\Backend\HouseholdType;

class CustomerManagement
{
    /**
     * @OA\Get(
     *     path="/customer-management",
     *     tags={"Customer Management 客戶總覽"},
     *     summary="獲取客戶總覽資料",
     *     description="獲取客戶總覽資料",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
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
     *         name="filter_key[phone]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[email]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter_key[name]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
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
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="perPage", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=1),
     *                 @OA\Property(property="lastPage", type="integer", example=1),
     *                 @OA\Property(property="next_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="prev_url", type="string", nullable=true, example=null),
     *                 @OA\Property(
     *                     property="list",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="name", type="string", example="陳的聖", description="姓名"),
     *                         @OA\Property(property="phone", type="string", example="01111111", description="手機"),
     *                         @OA\Property(property="email", type="string", example="bcd@gmail.com", description="信箱"),
     *                         @OA\Property(property="quantity", type="integer", example=2, description="購買戶數"),
     *                         @OA\Property(
     *                             property="list",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="community", type="string", example="Demo展示社區-1", description="銷售建案名稱"),
     *                                 @OA\Property(property="space_id", type="string", example="2b62012b-ab70-4faf-a8e7-d2b6ee9ade9c"),
     *                                 @OA\Property(property="household_name", type="string", example="18B", description="戶別"),
     *                                 @OA\Property(
     *                                     property="identity_mode",
     *                                     type="array",
     *                                     description="身分別",
     *                                     @OA\Items(type="string", example="所有權人")
     *                                 ),
     *                                 @OA\Property(
     *                                     property="members",
     *                                     type="array",
     *                                     description="關係人",
     *                                     @OA\Items(type="string", example="王81GGGGGG (配偶)")
     *                                 ),
     *                                 @OA\Property(property="property_status", type="string", example="現有戶", description="財產轉移狀態"),
     *                                 @OA\Property(property="build_date", type="string", example="2024-06-30", description="建置時間")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token 或無法識別的資料，登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已被禁止的 Token 或嘗試訪問權限不足的項目"
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
}
