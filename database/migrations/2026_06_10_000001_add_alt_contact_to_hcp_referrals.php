<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hcp_referrals', function (Blueprint $table) {
            $table->string('alt_contact_name')->nullable()->after('hcp_phone');
            $table->string('alt_contact_details')->nullable()->after('alt_contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('hcp_referrals', function (Blueprint $table) {
            $table->dropColumn(['alt_contact_name', 'alt_contact_details']);
        });
    }
};
