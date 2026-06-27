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
        Schema::create('hcp_referrals', function (Blueprint $table) {
            $table->id();
            $table->string('hcp_name');
            $table->string('hcp_registration_no');
            $table->string('hcp_practice');
            $table->string('hcp_email');
            $table->string('hcp_phone');
            $table->string('patient_full_name');
            $table->date('patient_dob');
            $table->string('patient_pps')->nullable();
            $table->text('reason_for_referral');
            $table->text('clinical_notes');
            $table->boolean('consent')->default(false);
            $table->string('status')->default('new');
            $table->boolean('synced_to_sheets')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hcp_referrals');
    }
};
