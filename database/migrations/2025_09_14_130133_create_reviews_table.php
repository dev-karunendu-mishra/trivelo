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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reviewer
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null'); // Optional booking reference
            $table->integer('rating'); // Overall rating 1-5
            $table->integer('cleanliness_rating')->nullable(); // 1-5
            $table->integer('service_rating')->nullable(); // 1-5
            $table->integer('location_rating')->nullable(); // 1-5
            $table->integer('value_rating')->nullable(); // 1-5
            $table->string('title');
            $table->text('review');
            $table->json('images')->nullable(); // Review images
            $table->json('pros')->nullable(); // Array of positive aspects
            $table->json('cons')->nullable(); // Array of negative aspects
            $table->boolean('is_verified')->default(false); // Verified stay
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'flagged'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->integer('helpful_count')->default(0); // How many found helpful
            $table->timestamp('stayed_at')->nullable(); // When they stayed
            $table->timestamps();
            
            $table->index(['hotel_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['rating', 'status']);
            $table->index('is_verified');
            $table->unique(['user_id', 'hotel_id', 'booking_id']); // One review per booking
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
