<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HcpReferral extends Model
{
    protected $fillable = [
        'hcp_name','hcp_practice','hcp_email','hcp_phone',
        'alt_contact_name','alt_contact_details',
        'patient_full_name','patient_dob',
        'reason_for_referral','clinical_notes','consent','status','synced_to_sheets',
    ];
    protected $casts = ['consent'=>'boolean','synced_to_sheets'=>'boolean','patient_dob'=>'date'];
}
