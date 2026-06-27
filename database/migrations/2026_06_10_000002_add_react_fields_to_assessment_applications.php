<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('assessment_applications', function (Blueprint $table) {
            $table->string('title')->nullable()->after('token');
            $table->date('motor_tax_expiry')->nullable()->after('license_expiry');
            $table->date('vehicle_insurance_expiry')->nullable()->after('motor_tax_expiry');
            $table->string('insurance_company')->nullable()->after('vehicle_insurance_expiry');
            $table->date('nct_due')->nullable()->after('insurance_company');
            $table->text('gp_name_address')->nullable()->after('gp_address');
            $table->text('consultant_name_address')->nullable()->after('consultant_phone');
            $table->string('alt_contact_name')->nullable()->after('consultant_name_address');
            $table->string('alt_contact_phone')->nullable()->after('alt_contact_name');
            $table->date('signature_date')->nullable()->after('signature_data');
        });
    }
    public function down(): void {
        Schema::table('assessment_applications', function (Blueprint $table) {
            $table->dropColumn([
                'title','motor_tax_expiry','vehicle_insurance_expiry','insurance_company','nct_due',
                'gp_name_address','consultant_name_address','alt_contact_name','alt_contact_phone','signature_date'
            ]);
        });
    }
};
