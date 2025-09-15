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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'booking_confirmation', 'booking_reminder', 'promotional', 'system', 'payment_confirmation', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (booking_id, hotel_id, etc.)
            $table->string('channel')->default('database'); // 'database', 'email', 'sms'
            $table->string('status')->default('unread'); // 'unread', 'read'
            $table->timestamp('read_at')->nullable();
            $table->string('priority')->default('normal'); // 'low', 'normal', 'high', 'urgent'
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->boolean('is_actionable')->default(false); // If notification requires user action
            $table->timestamp('expires_at')->nullable(); // For time-sensitive notifications
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'type']);
            $table->index(['created_at']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
