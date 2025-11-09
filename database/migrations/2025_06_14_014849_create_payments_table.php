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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 8, 2);
            $table->enum('payment_method_used', ['online_payment', 'cod']);

            // ✅ ADDED: New fields for manual verification
            $table->string('payment_proof_url')->nullable();
            $table->string('customer_reference_code')->nullable();
            
            // Admin verification (for oversight/disputes only)
            $table->enum('admin_verification_status', ['pending_review', 'approved', 'rejected'])
                  ->default('pending_review');
            $table->text('admin_notes')->nullable();
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // ⭐ NEW: Rider verification (primary verification)
            $table->enum('rider_verification_status', ['pending', 'verified', 'rejected'])
                  ->default('pending');
            $table->timestamp('rider_verified_at')->nullable();
            $table->text('rider_verification_notes')->nullable();
            $table->foreignId('verified_by_rider_id')->nullable()->constrained('users')->nullOnDelete();

            // ❌ REMOVED: PayMongo fields
            // $table->string('gateway_transaction_id')->nullable();
            // $table->json('payment_gateway_response')->nullable();

            // Original fields
            $table->string('status', 20)->default('pending'); // e.g., 'pending', 'completed', 'failed', 'refunded'
            $table->timestamp('payment_processed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_details')->nullable();
            $table->timestamps();

            // Indexes for verification dashboards
            $table->index('admin_verification_status');
            $table->index('rider_verification_status');
        });

        // Add check constraint for payments
        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT chk_payments_status
            CHECK (status IN ('pending', 'completed', 'failed', 'refunded'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
