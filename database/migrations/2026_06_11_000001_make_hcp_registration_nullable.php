<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('hcp_referrals', function (Blueprint $table) {
            $table->string('hcp_registration_no')->nullable()->change();
        });
    }
    public function down(): void {
        Schema::table('hcp_referrals', function (Blueprint $table) {
            $table->string('hcp_registration_no')->nullable(false)->change();
        });
    }
};
