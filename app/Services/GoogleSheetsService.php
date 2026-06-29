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

    public function appendHcpReferral(array $data): bool
    {
        $service = $this->getService();
        if (!$service) return false;
        try {
            $values = [[
                now()->format('Y-m-d H:i:s'),
                $data['hcp_name'] ?? '',
                $data['hcp_registration_no'] ?? '',
                $data['hcp_practice'] ?? '',
                $data['hcp_email'] ?? '',
                $data['hcp_phone'] ?? '',
                $data['patient_full_name'] ?? '',
                $data['patient_dob'] ?? '',
                $data['patient_pps'] ?? '',
                $data['reason_for_referral'] ?? '',
                $data['clinical_notes'] ?? '',
                ($data['consent'] ?? false) ? 'Yes' : 'No',
                $data['document_name'] ?? '',
            ]];
            $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
            $service->spreadsheets_values->append(
                $this->spreadsheetId, 'HCP Referrals!A:M',
                $body, ['valueInputOption' => 'USER_ENTERED']
            );
            return true;
        } catch (\Exception $e) {
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
            }
            $values = [[
                $data['id'] ?? '',
                $data['order_id'] ?? '',
                now()->format('Y-m-d H:i:s'),
                $payerName,
                $data['email'] ?? '',
                $data['token'] ?? '',
                $data['amount_paid'] ?? '',
                $data['payment_status'] ?? '',
                $data['status'] ?? '',
                $data['title'] ?? '',
                $data['first_name'] ?? '',
                $data['last_name'] ?? '',
                $data['phone'] ?? '',
                $data['address'] ?? '',
                $dob,
                $data['license_number'] ?? '',
                $data['vehicle_make'] ?? '',
                $data['vehicle_model'] ?? '',
                $data['vehicle_year'] ?? '',
                $data['vehicle_reg'] ?? '',
                $data['gp_name'] ?? '',
            ]];
            $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
            $service->spreadsheets_values->append(
                $this->spreadsheetId, 'Assessments!A:U',
                $body, ['valueInputOption' => 'USER_ENTERED']
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
