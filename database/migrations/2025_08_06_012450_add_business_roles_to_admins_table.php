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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('business_type')->nullable()->after('is_active'); // hotel, ferry, theme_park, beach_event, general
            $table->json('permissions')->nullable()->after('business_type'); // specific permissions
            $table->unsignedBigInteger('business_id')->nullable()->after('permissions'); // ID of the specific business they manage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['business_type', 'permissions', 'business_id']);
        });
    }
};
