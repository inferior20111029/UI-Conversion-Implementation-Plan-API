<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            if (!Schema::hasColumn('affiliates', 'title')) {
                $table->string('title')->after('name')->nullable();
            }
            if (!Schema::hasColumn('affiliates', 'partner_name')) {
                $table->string('partner_name')->after('title')->nullable();
            }
            // Ensure affiliate_url exists for the seeder
            if (!Schema::hasColumn('affiliates', 'affiliate_url')) {
                $table->string('affiliate_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn(['title', 'partner_name']);
        });
    }
};
