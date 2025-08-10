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
        Schema::create('ferries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('departure_location');
            $table->string('arrival_location');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->integer('capacity');
            $table->decimal('price', 8, 2);
            $table->json('operating_days'); // ['monday', 'tuesday', etc.]
            $table->boolean('is_active')->default(true);
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferries');
    }
};
