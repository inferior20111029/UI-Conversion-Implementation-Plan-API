<?php

namespace Database\Seeders;

use App\Models\Affiliate;
use Illuminate\Database\Seeder;

class AffiliateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 保險類 (INSURANCE)
        Affiliate::create([
            'offer_type' => 'INSURANCE',
            'name' => '富邦寵物守護',
            'title' => '富邦 - 全方位寵物醫療險',
            'partner_name' => '富邦產險',
            'description' => '針對高齡毛孩設計，涵蓋住院、手術及門診費用。風險分數低於 90 者可享額外 15% 折扣。',
            'affiliate_url' => 'https://www.fubon.com/insurance/pet',
            'min_risk_score' => 0,
            'max_risk_score' => 120,
            'target_pet_types' => ['dog', 'cat'],
            'is_active' => true,
        ]);

        Affiliate::create([
            'offer_type' => 'INSURANCE',
            'name' => '國泰毛孩計畫',
            'title' => '國泰 - 寵物安心基礎保障',
            'partner_name' => '國泰產險',
            'description' => '基礎醫療保障，首年投保即享 5% 折扣。適合健康狀態穩定的年輕毛孩。',
            'affiliate_url' => 'https://www.cathay-ins.com.tw/pet',
            'min_risk_score' => 80,
            'max_risk_score' => 100,
            'target_pet_types' => ['dog', 'cat'],
            'is_active' => true,
        ]);

        // 2. 飲食與服務類 (FOOD / VET)
        Affiliate::create([
            'offer_type' => 'FOOD',
            'name' => '毛孩鮮食特惠',
            'title' => '法米納 - 天然低敏配方飼料',
            'partner_name' => 'Farmina',
            'description' => '專為體重過重毛孩設計，幫助維持理想體態，解鎖更高保費折扣。',
            'affiliate_url' => 'https://www.farmina.com/tw',
            'min_risk_score' => 100,
            'max_risk_score' => 200,
            'target_pet_types' => ['dog', 'cat'],
            'is_active' => true,
        ]);

        Affiliate::create([
            'offer_type' => 'VET',
            'name' => '年度健檢方案',
            'title' => '曼哈頓動物醫院 - 年度健康檢查',
            'partner_name' => '曼哈頓動醫',
            'description' => '包含超音波與血液生化檢查。完成健檢後系統將自動調降風險評分。',
            'affiliate_url' => 'https://www.manhattan-vet.com.tw',
            'min_risk_score' => 0,
            'max_risk_score' => 200,
            'target_pet_types' => ['dog', 'cat'],
            'is_active' => true,
        ]);
    }
}
