<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
    protected $fillable = [
        'stripe_payment_intent_id','stripe_session_id','customer_email','customer_name',
        'amount','currency','status','application_id',
    ];
    public function application() { return $this->belongsTo(AssessmentApplication::class, 'application_id'); }
}
