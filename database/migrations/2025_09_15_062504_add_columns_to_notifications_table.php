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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->string('type')->after('user_id'); // 'booking_confirmation', 'booking_reminder', 'promotional', 'system', 'payment_confirmation', etc.
            $table->string('title')->after('type');
            $table->text('message')->after('title');
            $table->json('data')->nullable()->after('message'); // Additional data (booking_id, hotel_id, etc.)
            $table->string('channel')->default('database')->after('data'); // 'database', 'email', 'sms'
            $table->string('status')->default('unread')->after('channel'); // 'unread', 'read'
            $table->timestamp('read_at')->nullable()->after('status');
            $table->string('priority')->default('normal')->after('read_at'); // 'low', 'normal', 'high', 'urgent'
            $table->string('action_url')->nullable()->after('priority'); // URL to redirect when clicked
            $table->boolean('is_actionable')->default(false)->after('action_url'); // If notification requires user action
            $table->timestamp('expires_at')->nullable()->after('is_actionable'); // For time-sensitive notifications
            
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
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['expires_at']);
            $table->dropColumn([
                'user_id', 'type', 'title', 'message', 'data', 'channel',
                'status', 'read_at', 'priority', 'action_url', 'is_actionable', 'expires_at'
            ]);
        });
    }
};
