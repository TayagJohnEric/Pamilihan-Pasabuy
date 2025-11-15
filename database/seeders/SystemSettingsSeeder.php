<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table first to avoid duplicate keys
        DB::table('system_settings')->truncate();

        DB::table('system_settings')->insert([
            // == Financial Settings ==
            [
                'setting_key' => 'platform_commission_rate',
                'setting_value' => '0.10',
                'description' => 'The percentage the platform takes from each vendor sale (e.g., 0.10 for 10%).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'paymongo_public_key',
                'setting_value' => config('services.paymongo.public_key'),
                'description' => 'Your public API key for PayMongo.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'paymongo_secret_key',
                'setting_value' => config('services.paymongo.secret_key'),
                'description' => 'Your secret API key for PayMongo. Handle with care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'paymongo_webhook_secret',
                'setting_value' => config('services.paymongo.webhook_secret'),
                'description' => 'The signing secret to verify that webhook notifications are from PayMongo.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // == Operational & Delivery Settings ==
            [
                'setting_key' => 'market_latitude',
                'setting_value' => '15.0345', // Example coordinate for San Fernando, Pampanga
                'description' => 'The fixed latitude of the San Fernando Public Market.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'market_longitude',
                'setting_value' => '120.6908', // Example coordinate for San Fernando, Pampanga
                'description' => 'The fixed longitude of the San Fernando Public Market.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'default_order_preparation_time_minutes',
                'setting_value' => '30',
                'description' => 'An estimated time (in minutes) shown to customers for order preparation.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // == Rider-Specific Settings ==
            [
                'setting_key' => 'rider_assignment_pool_size',
                'setting_value' => '5',
                'description' => 'The number of riders to include in the broadcast for a new order.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'rider_acceptance_timeout_seconds',
                'setting_value' => '90',
                'description' => 'How many seconds a rider has to accept an order offer.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'rider_bonus_deliveries_target',
                'setting_value' => '10',
                'description' => 'The number of deliveries a rider needs to complete (e.g., per day) to earn a bonus.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'rider_bonus_amount',
                'setting_value' => '150.00',
                'description' => 'The monetary value of the bonus when the target is met.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // == General Application Settings ==
            [
                'setting_key' => 'customer_support_email',
                'setting_value' => 'support@pamilihanpasabuy.com',
                'description' => 'The official email address for customer inquiries.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'customer_support_phone',
                'setting_value' => '+639171234567',
                'description' => 'The official contact number for support.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'app_maintenance_mode',
                'setting_value' => 'false',
                'description' => 'Set to "true" to display a maintenance page to users.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'app_maintenance_mode_message',
                'setting_value' => 'PasaBuy is currently down for scheduled maintenance. We will be back shortly!',
                'description' => 'The message to display when the app is in maintenance mode.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // == Payout Settings ==
            [
                'setting_key' => 'payout_schedule_type',
                'setting_value' => 'weekly',
                'description' => 'How often payouts are generated: weekly, monthly, or manual.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'payout_minimum_amount',
                'setting_value' => '50.00',
                'description' => 'Minimum amount required to generate a payout (in PHP).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'payout_processing_fee',
                'setting_value' => '0.00',
                'description' => 'Fee deducted from payouts for processing (in PHP).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'payout_auto_generation_enabled',
                'setting_value' => 'true',
                'description' => 'Whether to automatically generate payouts based on schedule.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
