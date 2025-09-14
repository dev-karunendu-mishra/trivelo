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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Deluxe Suite", "Standard Room"
            $table->string('room_number')->nullable(); // Actual room number
            $table->text('description');
            $table->json('images')->nullable(); // Array of room image URLs
            $table->enum('type', ['standard', 'deluxe', 'suite', 'presidential', 'family', 'twin', 'single', 'double']);
            $table->integer('capacity'); // Number of guests
            $table->integer('beds')->default(1);
            $table->enum('bed_type', ['single', 'double', 'queen', 'king', 'twin', 'sofa_bed']);
            $table->decimal('base_price', 10, 2); // Base price per night
            $table->decimal('weekend_price', 10, 2)->nullable(); // Weekend pricing
            $table->decimal('holiday_price', 10, 2)->nullable(); // Holiday pricing
            $table->integer('size_sqft')->nullable(); // Room size in square feet
            $table->json('amenities')->nullable(); // Room-specific amenities
            $table->json('features')->nullable(); // Room features (view, balcony, etc.)
            $table->boolean('is_smoking')->default(false);
            $table->boolean('is_accessible')->default(false); // ADA accessible
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('floor_number')->nullable();
            $table->timestamps();
            
            $table->index(['hotel_id', 'is_available', 'is_active']);
            $table->index(['type', 'capacity']);
            $table->index('base_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
