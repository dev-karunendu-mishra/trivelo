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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Icon class or image path
            $table->enum('category', ['general', 'business', 'wellness', 'entertainment', 'food', 'connectivity', 'accessibility', 'family']);
            $table->enum('type', ['hotel', 'room', 'both']); // Where this amenity applies
            $table->boolean('is_premium')->default(false); // Premium amenity
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['type', 'category', 'is_active']);
            $table->index('is_premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
