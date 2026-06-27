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
        Schema::create('assessment_applications', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->string('stripe_session_id')->nullable();
            $table->string('payment_status')->default('pending');
            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('eircode')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_category')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('vehicle_reg')->nullable();
            $table->boolean('insurance_valid')->default(false);
            $table->boolean('nct_valid')->default(false);
            $table->boolean('tax_valid')->default(false);
            $table->string('referral_reason')->nullable();
            $table->string('gp_name')->nullable();
            $table->string('gp_phone')->nullable();
            $table->string('gp_address')->nullable();
            $table->string('consultant_name')->nullable();
            $table->string('consultant_phone')->nullable();
            $table->text('medical_notes')->nullable();
            $table->text('signature_data')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('synced_to_sheets')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_applications');
    }
};
