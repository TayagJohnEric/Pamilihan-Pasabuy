<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiderSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Check if users with these phone numbers already exist to avoid duplicates
        $existingPhoneNumbers = DB::table('users')
            ->whereIn('phone_number', ['09331234567', '09171234568', '09221234569'])
            ->pluck('phone_number')
            ->toArray();
            
        if (in_array('09331234567', $existingPhoneNumbers)) {
            $this->command->warn('User with phone number 09331234567 already exists');
        }
        
        if (in_array('09171234568', $existingPhoneNumbers)) {
            $this->command->warn('User with phone number 09171234568 already exists');
        }
        
        if (in_array('09221234569', $existingPhoneNumbers)) {
            $this->command->warn('User with phone number 09221234569 already exists');
        }
        
        if (count($existingPhoneNumbers) >= 3) {
            $this->command->info('All riders already exist. Skipping seeder to avoid duplicate entries.');
            return;
        }

        // Rider 1
        if (!in_array('09331234567', $existingPhoneNumbers)) {
            $rider1UserId = DB::table('users')->insertGetId([
                'role' => 'rider',
                'first_name' => 'Mark',
                'last_name' => 'Salvador',
                'email' => 'rider1@example.com',
                'phone_number' => '09331234567',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('riders')->insert([
                'user_id' => $rider1UserId,  
                'average_rating' => 4.7,
                'total_deliveries' => 120,
                'daily_deliveries' => 5,
                'is_available' => true,
                'verification_status' => 'verified',
                'license_number' => 'LIC123456',
                'vehicle_type' => 'motorcycle',
                'gcash_number' => '92840294481',
                 'gcash_name' => 'Mark S.',
                'current_latitude' => 15.12345678,
                'current_longitude' => 120.12345678,
                'location_last_updated_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Rider 2
        if (!in_array('09171234568', $existingPhoneNumbers)) {
            $rider2UserId = DB::table('users')->insertGetId([
                'role' => 'rider',
                'first_name' => 'Sarah',
                'last_name' => 'Lim',
                'email' => 'rider2@example.com',
                'phone_number' => '09171234568',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('riders')->insert([
                'user_id' => $rider2UserId,  
                'average_rating' => 4.5,
                'total_deliveries' => 85,
                'daily_deliveries' => 4,
                'is_available' => true,
                'verification_status' => 'verified',
                'license_number' => 'LIC789012',
                'vehicle_type' => 'motorcycle',
                 'gcash_number' => '85734933281',
                 'gcash_name' => 'Sarah L.',
                'current_latitude' => 15.22345678,
                'current_longitude' => 120.22345678,
                'location_last_updated_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Rider 3
        if (!in_array('09221234569', $existingPhoneNumbers)) {
            $rider3UserId = DB::table('users')->insertGetId([
                'role' => 'rider',
                'first_name' => 'David',
                'last_name' => 'Ramos',
                'email' => 'rider3@example.com',
                'phone_number' => '09221234569',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('riders')->insert([
                'user_id' => $rider3UserId,  
                'average_rating' => 4.9,
                'total_deliveries' => 200,
                'daily_deliveries' => 8,
                'is_available' => true,
                'verification_status' => 'verified',
                'license_number' => 'LIC345678',
                'vehicle_type' => 'motorcycle',
                 'gcash_number' => '628392739271',
                 'gcash_name' => 'David R.',
                'current_latitude' => 15.32345678,
                'current_longitude' => 120.32345678,
                'location_last_updated_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('Rider seeder completed successfully.');
    }
}