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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_url');
            $table->string('link_url')->nullable();
            $table->enum('type', ['hotel', 'theme_park', 'beach_event', 'ferry', 'general']);
            $table->enum('position', ['banner', 'sidebar', 'featured', 'popup']);
            $table->integer('priority')->default(0);
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->boolean('is_active')->default(true);
            $table->integer('clicks')->default(0);
            $table->integer('views')->default(0);
            $table->foreignId('advertiser_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
