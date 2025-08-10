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
        Schema::create('park_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_park_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['ride', 'show', 'experience', 'game', 'restaurant']);
            $table->decimal('price', 8, 2);
            $table->integer('duration_minutes')->nullable();
            $table->integer('capacity_per_session')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->json('requirements')->nullable(); // height, health restrictions
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('park_activities');
    }
};
