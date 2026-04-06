<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->boolean('is_registered')->default(false)->after('microchip_number');
            $table->string('registration_number', 64)->nullable()->after('is_registered');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['is_registered', 'registration_number']);
        });
    }
};
