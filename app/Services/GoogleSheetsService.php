<?php
namespace App\Services;
use App\Models\Setting;
use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    private ?Sheets $service = null;
    private string $spreadsheetId;

    public function __construct()
    {
        // Prefer DB setting, fall back to env
        $this->spreadsheetId = Setting::get('google_sheets_id', env('GOOGLE_SHEETS_ID', ''));
    }

    private function getService(): ?Sheets
    {
        if ($this->service) return $this->service;
        $json = env('GOOGLE_SERVICE_ACCOUNT_JSON', Setting::get('google_service_account_json', ''));
        if (empty($json) || empty($this->spreadsheetId)) return null;
        try {
            $credentials = json_decode($json, true);
            if (!$credentials) return null;
            $client = new Client();
            $client->setAuthConfig($credentials);
            $client->addScope(Sheets::SPREADSHEETS);
            $this->service = new Sheets($client);
            return $this->service;
        } catch (\Exception $e) {
            return null;
        }
    }

    // Headers: ID, Submitted At, HCP Name, HCP Address, HCP Phone, HCP Email,
    //          Patient Title, Patient Name, Patient Address, Patient Eircode,
    //          Patient Date of Birth, Patient Phone, Patient Email,
    //          Alternative Contact, Alternative Contact Details,
    //          Medical Condition, Clinical Notes, Consent, Doc Attached
    public function appendHcpReferral(array $data): bool
    {
        $service = $this->getService();
        if (!$service) return false;
        try {
            $dob = (string)($data['patient_dob'] ?? '');
            if (strlen($dob) > 10) $dob = substr($dob, 0, 10);
            $values = [[
                (string)($data['id'] ?? ''),
                now()->format('Y-m-d H:i:s'),
                (string)($data['hcp_name'] ?? ''),
                (string)($data['hcp_practice'] ?? ''),
                (string)($data['hcp_phone'] ?? ''),
                (string)($data['hcp_email'] ?? ''),
                '',
                (string)($data['patient_full_name'] ?? ''),
                '',
                '',
                $dob,
                '',
                '',
                (string)($data['alt_contact_name'] ?? ''),
                (string)($data['alt_contact_details'] ?? ''),
                (string)($data['reason_for_referral'] ?? ''),
                (string)($data['clinical_notes'] ?? ''),
                ($data['consent'] ?? false) ? 'Yes' : 'No',
                (string)($data['document_name'] ?? ''),
            ]];
            $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
            $service->spreadsheets_values->append(
                $this->spreadsheetId, 'HCP Referrals!A:S',
                $body, ['valueInputOption' => 'USER_ENTERED']
            );
            return true;
        } catch (\Exception $e) {
            \Log::error('GoogleSheets appendHcpReferral failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    // Headers: Date, Submission ID, Person Title, Name, Address, Eircode, Phone, Email,
    //          DOB, (Wppay-skip), Booking ID, Amount Paid, License Number, License Expire,
    //          Motor Tax Expire, Vehicle Insurance Expire, Insurance Company, Next NCT Due,
    //          GPS Name & Address, Alt Contact Name, Consultant Name & Address,
    //          Alt Contact Number, Signature, Referral, Payment Status, Application Status
    public function appendAssessment(array $data): bool
    {
        $service = $this->getService();
        if (!$service) {
            \Log::warning('GoogleSheets appendAssessment: getService() returned null — check google_service_account_json and google_sheets_id settings');
            return false;
        }
        try {
            $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
            $id = $data['id'] ?? '';
            $submissionId = $id ? ('DAI-' . date('Y') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT)) : '';
            $amount = isset($data['amount_paid']) ? number_format((float)$data['amount_paid'], 2, '.', '') : '';
            $fmt = function($key) use ($data) {
                $v = $data[$key] ?? '';
                if (empty($v)) return '';
                if ($v instanceof \DateTimeInterface) return $v->format('Y-m-d');
                $v = (string)$v;
                if (strlen($v) > 10 && preg_match('/^\d{4}-\d{2}-\d{2}/', $v)) return substr($v, 0, 10);
                return $v;
            };
            $hasSig = !empty($data['signature_data']);
            $values = [[
                now()->format('Y-m-d H:i:s'),
                $submissionId,
                (string)($data['title'] ?? ''),
                $fullName,
                (string)($data['address'] ?? ''),
                (string)($data['eircode'] ?? ''),
                (string)($data['phone'] ?? ''),
                (string)($data['email'] ?? ''),
                $fmt('dob'),
                '',
                (string)($data['token'] ?? ''),
                $amount,
                (string)($data['license_number'] ?? ''),
                $fmt('license_expiry'),
                $fmt('motor_tax_expiry'),
                $fmt('vehicle_insurance_expiry'),
                (string)($data['insurance_company'] ?? ''),
                $fmt('nct_due'),
                (string)($data['gp_name_address'] ?? $data['gp_name'] ?? ''),
                (string)preg_replace('/^.*?:\s*/', '', $data['alt_contact_name'] ?? ''),
                (string)($data['consultant_name_address'] ?? ''),
                (string)preg_replace('/^.*?:\s*/', '', $data['alt_contact_phone'] ?? ''),
                $hasSig ? 'Yes' : 'No',
                (string)($data['referral_reason'] ?? ''),
                (string)($data['payment_status'] ?? ''),
                (string)($data['status'] ?? ''),
            ]];
            \Log::info('GoogleSheets appendAssessment', ['col_count' => count($values[0]), 'values' => $values[0]]);
            $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
            $service->spreadsheets_values->append(
                $this->spreadsheetId, 'Assessments!A:Z',
                $body, ['valueInputOption' => 'USER_ENTERED']
            );
            return true;
        } catch (\Exception $e) {
            \Log::error('GoogleSheets appendAssessment failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
