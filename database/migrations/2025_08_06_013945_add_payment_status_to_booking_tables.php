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
        // Add payment_status to hotel_bookings
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('status');
        });

        // Add payment_status to ferry_tickets
        Schema::table('ferry_tickets', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('status');
        });

        // Add payment_status to park_tickets
        Schema::table('park_tickets', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('status');
        });

        // Add payment_status to activity_tickets
        Schema::table('activity_tickets', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('status');
        });

        // Add payment_status to beach_tickets
        Schema::table('beach_tickets', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('ferry_tickets', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('park_tickets', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('activity_tickets', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('beach_tickets', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
