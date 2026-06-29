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
                $this->spreadsheetId, 'HCP Referrals!A1',
                $body, ['valueInputOption' => 'USER_ENTERED', 'insertDataOption' => 'INSERT_ROWS']
            );
            return true;
        } catch (\Exception $e) {
            \Log::error('GoogleSheets appendHcpReferral failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    // ORIGINAL: DAI feedback 26-06-28 — realigned columns to match spreadsheet headers
    // Headers: ID, Order ID, Date, Payer Name, Payer Email, Booking #, Amount,
    //          Payment Status, Status, Title, First Name, Last Name, Phone, Address,
    //          DOB, Licence No, Vehicle Make, Model, Year, Reg, GP Name
    public function appendAssessment(array $data): bool
    {
        $service = $this->getService();
        if (!$service) return false;
        try {
            $payerName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
            $dob = $data['dob'] ?? '';
            if ($dob instanceof \DateTimeInterface) {
                $dob = $dob->format('Y-m-d');
            } elseif (is_string($dob) && strlen($dob) > 10) {
                $dob = substr($dob, 0, 10);
            }
            $amount = isset($data['amount_paid']) ? number_format((float)$data['amount_paid'], 2, '.', '') : '';
            $values = [[
                (string)($data['id'] ?? ''),
                (string)($data['order_id'] ?? ''),
                now()->format('Y-m-d H:i:s'),
                $payerName,
                (string)($data['email'] ?? ''),
                (string)($data['token'] ?? ''),
                $amount,
                (string)($data['payment_status'] ?? ''),
                (string)($data['status'] ?? ''),
                (string)($data['title'] ?? ''),
                (string)($data['first_name'] ?? ''),
                (string)($data['last_name'] ?? ''),
                (string)($data['phone'] ?? ''),
                (string)($data['address'] ?? ''),
                (string)$dob,
                (string)($data['license_number'] ?? ''),
                (string)($data['vehicle_make'] ?? ''),
                (string)($data['vehicle_model'] ?? ''),
                (string)($data['vehicle_year'] ?? ''),
                (string)($data['vehicle_reg'] ?? ''),
                (string)($data['gp_name'] ?? ''),
            ]];
            \Log::info('GoogleSheets appendAssessment', ['values' => $values[0]]);
            $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
            $service->spreadsheets_values->append(
                $this->spreadsheetId, 'Assessments!A1',
                $body, ['valueInputOption' => 'USER_ENTERED', 'insertDataOption' => 'INSERT_ROWS']
            );
            return true;
        } catch (\Exception $e) {
            \Log::error('GoogleSheets appendAssessment failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
