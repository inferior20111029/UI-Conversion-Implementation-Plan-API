<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // 保險公司名稱
            $table->string('logo_url')->nullable();          // LOGO
            $table->text('description')->nullable();         // 簡介
            $table->string('affiliate_url');                 // 導購連結（含 UTM）
            $table->decimal('commission_rate', 5, 2)->default(0); // 佣金比例 %
            $table->boolean('is_active')->default(true);
            $table->json('target_pet_types')->nullable();    // ["dog","cat"] 適用寵物類型
            $table->integer('min_risk_score')->default(0);   // 適合的最低風險分數
            $table->integer('max_risk_score')->default(200); // 適合的最高風險分數
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
