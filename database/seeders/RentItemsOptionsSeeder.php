<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\RentItemsOptions;

class RentItemsOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insertData = [
            [
                'id' => 1,
                'name' => '管理費'
            ],
            [
                'id' => 2,
                'name' => '清潔費'
            ],
            [
                'id' => 3,
                'name' => '第四台'
            ],
            [
                'id' => 4,
                'name' => '網路'
            ],
            [
                'id' => 5,
                'name' => '水費'
            ],
            [
                'id' => 6,
                'name' => '電費'
            ],
            [
                'id' => 7,
                'name' => '瓦斯費'
            ],
        ];

        RentItemsOptions::upsert($insertData, ['id']);
    }
}
