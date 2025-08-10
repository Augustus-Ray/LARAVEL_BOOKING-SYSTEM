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
        Schema::create('ferry_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ferry_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_booking_id')->constrained()->onDelete('cascade');
            $table->date('travel_date');
            $table->integer('passengers');
            $table->decimal('total_price', 8, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'used'])->default('pending');
            $table->string('booking_reference')->unique();
            $table->timestamp('booking_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferry_tickets');
    }
};
