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
        Schema::create('rider_applications', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('contact_number');
            $table->date('birth_date');
            $table->text('address');
            $table->string('vehicle_type');
            $table->string('vehicle_model');
            $table->string('license_plate_number');
            $table->string('driver_license_number');
            $table->date('license_expiry_date');
            $table->string('nbi_clearance_url')->nullable();
            $table->string('valid_id_url')->nullable();
            $table->string('selfie_with_id_url')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('gcash_number')->nullable()->unique();
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_applications');
    }
};
