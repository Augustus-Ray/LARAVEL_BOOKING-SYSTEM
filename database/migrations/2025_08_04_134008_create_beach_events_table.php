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
        Schema::create('beach_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('type', ['sports', 'music', 'cultural', 'adventure', 'dining']);
            $table->decimal('price', 8, 2);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('capacity');
            $table->json('equipment_included')->nullable();
            $table->string('image_url')->nullable();
            $table->text('requirements')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beach_events');
    }
};
