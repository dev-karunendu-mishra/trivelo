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
        Schema::create('hotel_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->string('image_url');
            $table->string('alt_text')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index(['hotel_id', 'is_primary']);
            $table->index(['hotel_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_images');
    }
};
