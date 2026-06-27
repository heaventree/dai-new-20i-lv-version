<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\HcpReferral;
use App\Models\Setting;
use App\Services\EmailService;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
class HcpReferralController extends Controller
{
    public function index() { return view('public.hcp-referral'); }
    public function submit(Request $request)
    {
        $request->validate([
            'hcp_name'            => 'required|string|max:200',
            'hcp_practice'        => 'required|string|max:200',
            'hcp_email'           => 'required|email|max:200',
            'hcp_phone'           => 'required|string|max:30',
            'alt_contact_name'    => 'nullable|string|max:200',
            'alt_contact_details' => 'nullable|string|max:200',
            'patient_full_name'   => 'required|string|max:200',
            'patient_dob'         => 'required|date|before_or_equal:today',
            'reason_for_referral' => 'required|string',
            'clinical_notes'      => 'required|string',
            'consent'             => 'accepted',
        ]);
        $referral = HcpReferral::create([
            'hcp_name'            => $request->hcp_name,
            'hcp_practice'        => $request->hcp_practice,
            'hcp_email'           => $request->hcp_email,
            'hcp_phone'           => $request->hcp_phone,
            'alt_contact_name'    => $request->alt_contact_name,
            'alt_contact_details' => $request->alt_contact_details,
            'patient_full_name'   => $request->patient_full_name,
            'patient_dob'         => $request->patient_dob,
            'reason_for_referral' => $request->reason_for_referral,
            'clinical_notes'      => $request->clinical_notes,
            'consent'             => true,
        ]);
        $sheets = app(GoogleSheetsService::class);
        if ($sheets->appendHcpReferral($referral->toArray())) {
            $referral->update(['synced_to_sheets' => true]);
        }
        $adminEmail = Setting::get('notification_email', 'info@driverassessmentsireland.ie');
        $emailService = app(EmailService::class);
        $emailService->sendFromTemplate('hcp-referral-confirmation', $referral->hcp_email, $referral->hcp_name, [
    'hcpName'       => $referral->hcp_name,
    'hcpAddress'    => $referral->hcp_practice,
    'hcpPhone'      => $referral->hcp_phone,
    'hcpEmail'      => $referral->hcp_email,
    'patientName'   => $referral->patient_full_name,
    'patientDob'    => $referral->patient_dob?->format('d/m/Y') ?? '—',
    'patientCounty' => '—',
]);
        
        $emailService->send(
            $adminEmail,
            'DAI Admin',
            'New HCP Referral – ' . $referral->patient_full_name,
            "A new HCP referral has been submitted.\n\nHCP: {$referral->hcp_name} ({$referral->hcp_practice})\nPatient: {$referral->patient_full_name}\nReason: {$referral->reason_for_referral}\n\nLogin to the admin panel to view full details."
        );
        return redirect()->route('hcp-referral.thanks');
    }
    public function thanks() { return view('public.hcp-referral-thanks'); }
}
