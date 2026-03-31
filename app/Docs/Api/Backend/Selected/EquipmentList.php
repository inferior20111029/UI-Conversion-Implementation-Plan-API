<?php

namespace App\Docs\Api\Backend\Selected;

class EquipmentList
{
    /**
     * @OA\Get(
     *      path="/selected/equipment/list",
     *      tags={"Selected 選項"},
     *      summary="取得元件列表資料",
     *      description="取得元件列表資料",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
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
     *                 @OA\Property(
     *                     property="area",
     *                     type="array",
     *                     @OA\Items(type="string", example="區域")
     *                 ),
     *                 @OA\Property(
     *                     property="space",
     *                     type="array",
     *                     @OA\Items(type="string", example="客廳")
     *                 ),
     *                 @OA\Property(
     *                     property="location",
     *                     type="array",
     *                     @OA\Items(type="string", example="位置")
     *                 ),
     *                 @OA\Property(
     *                     property="district_name",
     *                     type="array",
     *                     @OA\Items(type="string", example="B")
     *                 ),
     *                 @OA\Property(
     *                     property="building_name",
     *                     type="array",
     *                     @OA\Items(type="string", example="甲棟")
     *                 ),
     *                 @OA\Property(
     *                     property="staircase_name",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="floor_name",
     *                     type="array",
     *                     @OA\Items(type="string", example="11F")
     *                 ),
     *                 @OA\Property(
     *                     property="household_name",
     *                     type="array",
     *                     @OA\Items(type="string", example="18B")
     *                 ),
     *                 @OA\Property(
     *                     property="type_name",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="客")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="system_name",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="變")
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
