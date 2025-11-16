<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status', 50); // e.g., 'pending', 'pending_payment', 'processing', 'awaiting_rider_assignment', 'assigned', 'pickup_confirmed', 'out_for_delivery', 'delivered', 'cancelled', 'failed'
            $table->text('notes')->nullable();
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
        });

        // Add check constraint for valid statuses
        DB::statement("
            ALTER TABLE order_status_history
            ADD CONSTRAINT chk_order_status_history_status
            CHECK (status IN (
                'pending',
                'pending_payment',
                'processing',
                'awaiting_rider_assignment',
                'assigned',
                'pickup_confirmed',
                'pending_customer_confirmation',
                'out_for_delivery',
                'delivered',
                'cancelled',
                'failed'
            ))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};
