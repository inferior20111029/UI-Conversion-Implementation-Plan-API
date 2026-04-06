<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_plans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('insurance_provider_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('source_provider_id')->index();
            $table->unsignedBigInteger('source_plan_id')->unique();
            $table->string('code', 100);
            $table->string('name');
            $table->text('summary')->nullable();
            $table->string('plan_type', 50)->nullable();
            $table->char('currency', 3)->default('TWD');
            $table->decimal('annual_premium_min', 12, 2)->default(0);
            $table->decimal('annual_premium_max', 12, 2)->default(0);
            $table->json('species_supported');
            $table->string('terms_url', 500)->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->json('scoring_weight_snapshot')->nullable();
            $table->json('coverage_rule_snapshot')->nullable();
            $table->json('claim_strategy_snapshot')->nullable();
            $table->json('target_audience_snapshot')->nullable();
            $table->json('ranking_strategy_snapshot')->nullable();
            $table->json('eligibility_snapshot')->nullable();
            $table->json('coverage_summary_snapshot')->nullable();
            $table->json('comparison_snapshot')->nullable();
            $table->json('claim_requirement_snapshot')->nullable();
            $table->string('source_status', 20)->default('active')->index();
            $table->string('algorithm_version', 50)->nullable();
            $table->boolean('is_listable')->default(true)->index();
            $table->dateTime('source_updated_at')->nullable()->index();
            $table->dateTime('first_synced_at')->nullable();
            $table->dateTime('synced_at')->nullable();
            $table->dateTime('last_seen_at')->nullable();
            $table->dateTime('source_deleted_at')->nullable();
            $table->timestamps();

            $table->index(['is_listable', 'source_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_plans');
    }
};
