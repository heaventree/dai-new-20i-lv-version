<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AssessmentApplication extends Model
{
    protected $fillable = [
        'token','order_id','stripe_payment_intent_id','stripe_session_id','payment_status','amount_paid',
        'title','first_name','last_name','email','phone','dob','address','eircode',
        'license_number','license_category','license_expiry',
        'motor_tax_expiry','vehicle_insurance_expiry','insurance_company','nct_due',
        'vehicle_make','vehicle_model','vehicle_year','vehicle_reg',
        'insurance_valid','nct_valid','tax_valid',
        'referral_reason',
        'gp_name','gp_phone','gp_address','gp_name_address',
        'consultant_name','consultant_phone','consultant_name_address',
        'alt_contact_name','alt_contact_phone',
        'medical_notes','signature_data','signature_date',
        'status','synced_to_sheets','submitted_at',
    ];
    protected $casts = [
        'dob'                    => 'date',
        'license_expiry'         => 'date',
        'motor_tax_expiry'       => 'date',
        'vehicle_insurance_expiry' => 'date',
        'nct_due'                => 'date',
        'signature_date'         => 'date',
        'submitted_at'           => 'datetime',
        'insurance_valid'        => 'boolean',
        'nct_valid'              => 'boolean',
        'tax_valid'              => 'boolean',
        'synced_to_sheets'       => 'boolean',
    ];
    public function payment() { return $this->hasOne(Payment::class, 'application_id'); }
}
