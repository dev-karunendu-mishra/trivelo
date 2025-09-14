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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // Payment gateway transaction ID
            $table->string('payment_intent_id')->nullable(); // Stripe payment intent ID
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('type', ['payment', 'refund', 'partial_refund']);
            $table->enum('method', ['credit_card', 'debit_card', 'paypal', 'stripe', 'bank_transfer', 'cash']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded']);
            $table->string('gateway')->nullable(); // stripe, paypal, etc.
            $table->json('gateway_response')->nullable(); // Full gateway response
            $table->string('receipt_number')->nullable();
            $table->text('description')->nullable();
            $table->text('failure_reason')->nullable();
            $table->decimal('fee_amount', 10, 2)->default(0.00); // Gateway fees
            $table->decimal('net_amount', 10, 2); // Amount after fees
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['booking_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('transaction_id');
            $table->index(['status', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
