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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('rider_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('preferred_rider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('delivery_address_id')->constrained('saved_addresses')->onDelete('restrict');
            $table->timestamp('order_date')->useCurrent();
            $table->string('status')->default('pending_payment'); // e.g., 'pending_payment', 'processing', 'awaiting_rider_assignment', 'assigned', 'pickup_confirmed', 'out_for_delivery', 'delivered', 'cancelled', 'failed'
            $table->decimal('delivery_fee', 8, 2);
            $table->decimal('final_total_amount', 8, 2);
            $table->enum('payment_method', ['online_payment', 'cod']);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
           // ❌ REMOVED: $table->string('payment_intent_id')->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('delivery_proof_image')->nullable(); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
