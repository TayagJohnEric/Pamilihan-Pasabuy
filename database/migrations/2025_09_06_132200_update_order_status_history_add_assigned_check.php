<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE order_status_history DROP CONSTRAINT IF EXISTS chk_order_status_history_status");
            DB::statement(<<<SQL
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
            SQL);
        } elseif (in_array($driver, ['mysql', 'mariadb'])) {
            // MySQL 8.0.16+ enforces CHECK constraints; prior versions parse but ignore them.
            // Attempt to drop then re-add the check. Use IF EXISTS for compatibility where supported.
            try {
                DB::statement("ALTER TABLE order_status_history DROP CHECK chk_order_status_history_status");
            } catch (\Throwable $e) {
                // Ignore if not supported or doesn't exist
            }
            try {
                DB::statement(<<<SQL
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
                SQL);
            } catch (\Throwable $e) {
                // Ignore on versions that don't enforce CHECK constraints
            }
        } else {
            // Fallback: attempt generic SQL; ignore errors if unsupported
            try {
                DB::statement("ALTER TABLE order_status_history DROP CONSTRAINT IF EXISTS chk_order_status_history_status");
            } catch (\Throwable $e) {}
            try {
                DB::statement(<<<SQL
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
                SQL);
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE order_status_history DROP CONSTRAINT IF EXISTS chk_order_status_history_status");
            DB::statement(<<<SQL
                ALTER TABLE order_status_history
                ADD CONSTRAINT chk_order_status_history_status
                CHECK (status IN (
                    'pending',
                    'pending_payment',
                    'processing',
                    'awaiting_rider_assignment',
                    'out_for_delivery',
                    'pending_customer_confirmation',
                    'delivered',
                    'cancelled',
                    'failed'
                ))
            SQL);
        } elseif (in_array($driver, ['mysql', 'mariadb'])) {
            try {
                DB::statement("ALTER TABLE order_status_history DROP CHECK chk_order_status_history_status");
            } catch (\Throwable $e) {}
            try {
                DB::statement(<<<SQL
                    ALTER TABLE order_status_history
                    ADD CONSTRAINT chk_order_status_history_status
                    CHECK (status IN (
                        'pending',
                        'pending_payment',
                        'processing',
                        'awaiting_rider_assignment',
                        'out_for_delivery',
                        'delivered',
                        'cancelled',
                        'failed'
                    ))
                SQL);
            } catch (\Throwable $e) {}
        } else {
            try {
                DB::statement("ALTER TABLE order_status_history DROP CONSTRAINT IF EXISTS chk_order_status_history_status");
            } catch (\Throwable $e) {}
            try {
                DB::statement(<<<SQL
                    ALTER TABLE order_status_history
                    ADD CONSTRAINT chk_order_status_history_status
                    CHECK (status IN (
                        'pending',
                        'pending_payment',
                        'processing',
                        'awaiting_rider_assignment',
                        'out_for_delivery',
                        'delivered',
                        'cancelled',
                        'failed'
                    ))
                SQL);
            } catch (\Throwable $e) {}
        }
    }
};
