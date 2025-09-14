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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Hotel manager
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('images')->nullable(); // Array of image URLs
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('star_rating')->default(1); // 1-5 stars
            $table->decimal('average_rating', 3, 2)->default(0.00); // 0-5.00
            $table->integer('total_reviews')->default(0);
            $table->json('amenities')->nullable(); // Array of amenities
            $table->json('policies')->nullable(); // Check-in/out times, cancellation policy, etc.
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'is_active']);
            $table->index(['city', 'country']);
            $table->index('star_rating');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
