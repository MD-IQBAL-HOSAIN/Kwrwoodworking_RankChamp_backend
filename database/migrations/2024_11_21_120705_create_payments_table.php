<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Payment ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('payment_method', ['stripe', 'paypal', 'credit_card'])->default('stripe');
            $table->string('stripe_payment_id')->nullable(); // Stripe payment transaction ID
            $table->json('product_id'); 
            $table->string('stripe_charge_id')->nullable();  // Stripe charge ID
            $table->decimal('amount', 10, 2); // Payment amount
            $table->enum('status', ['pending', 'succeeded', 'failed'])->default('pending');
            $table->timestamps(); 
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
