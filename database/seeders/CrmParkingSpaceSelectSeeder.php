<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CrmParkingSpaceSelectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('db_leasehold.crm_parking_space_select')->upsert([
            [
                'id' => '91bb01f3-d68a-45a5-ba23-c02a65033a11',
                'company_id' => 0,
                'type' => 'parking_type',
                'value' => '升降平面式',
            ],
            [
                'id' => '4adc1298-ef97-4ce4-b0c4-48d039723686',
                'company_id' => 0,
                'type' => 'parking_type',
                'value' => '升降機械式',
            ],
            [
                'id' => '9316db7a-8750-4f4d-a354-b93f72a8079b',
                'company_id' => 0,
                'type' => 'parking_type',
                'value' => '坡道平面式',
            ],
            [
                'id' => 'aeb947e9-e550-463e-b5b0-09eb173fca05',
                'company_id' => 0,
                'type' => 'parking_type',
                'value' => '坡道機械式',
            ],
            [
                'id' => '094e48ae-bf77-40f9-bf11-6ca44d0d6c29',
                'company_id' => 0,
                'type' => 'parking_attribute',
                'value' => '保留中',
            ],
            [
                'id' => '4ddd92e5-169c-401a-981c-2eeb543a5c73',
                'company_id' => 0,
                'type' => 'parking_attribute',
                'value' => '銷售中',
            ],
            [
                'id' => '37406aa3-4c90-462e-a396-5c55c92c454e',
                'company_id' => 0,
                'type' => 'parking_attribute',
                'value' => '私車位',
            ],
            [
                'id' => 'a878824d-35c6-4a70-b8bb-76b6615cfb63',
                'company_id' => 0,
                'type' => 'parking_attribute',
                'value' => '公車位',
            ],
            [
                'id' => 'e6b27a6a-176e-4c6e-b92a-20f763740b40',
                'company_id' => 0,
                'type' => 'use_direction',
                'value' => '租賃',
            ],
            [
                'id' => 'ee1fb0d3-5cb2-4a88-bf1b-536ff1c77132',
                'company_id' => 0,
                'type' => 'use_direction',
                'value' => '自用',
            ],
            [
                'id' => 'a0243242-48c0-4df2-8134-cf4b991e525a',
                'company_id' => 0,
                'type' => 'use_direction',
                'value' => '共用',
            ],
        ], ['id']);
    }
}
