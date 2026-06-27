<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

/**
 * Migrates live data from PostgreSQL (React/Node app) → MySQL (Laravel app).
 *
 * Usage:
 *   Set POSTGRES_URL in .env (e.g. postgres://user:pass@host:5432/dbname)
 *   php artisan dai:migrate-from-postgres [--dry-run]
 *
 * Tables migrated:
 *   - assessment_applications  (unpacks basic_info / vehicle_info / medical_contact JSONB)
 *   - hcp_referrals            (best-effort field mapping)
 *   - payments                 (linked to applications via stripe_payment_intent_id)
 */
class MigrateFromPostgres extends Command
{
    protected $signature   = 'dai:migrate-from-postgres {--dry-run : Preview what would be imported without writing}';
    protected $description = 'Migrate live data from PostgreSQL to the Laravel MySQL database';

    private PDO $pg;
    private bool $dryRun;
    private array $counts = ['applications' => 0, 'hcp_referrals' => 0, 'payments' => 0, 'skipped' => 0];

    public function handle(): int
    {
        $this->dryRun = $this->option('dry-run');
        $this->dryRun && $this->warn('DRY RUN — no data will be written.');

        $url = env('POSTGRES_URL') ?: env('DATABASE_URL');
        if (!$url) {
            $this->error('Set POSTGRES_URL (or DATABASE_URL) in .env before running this command.');
            return 1;
        }

        $this->info('Connecting to PostgreSQL...');
        try {
            $this->pg = $this->connectPostgres($url);
        } catch (\Exception $e) {
            $this->error('PostgreSQL connection failed: ' . $e->getMessage());
            return 1;
        }
        $this->info('Connected.');

        $this->migrateApplications();
        $this->migrateHcpReferrals();
        $this->migratePayments();

        $this->newLine();
        $this->table(['Table', 'Imported'], [
            ['assessment_applications', $this->counts['applications']],
            ['hcp_referrals',           $this->counts['hcp_referrals']],
            ['payments',                $this->counts['payments']],
            ['skipped (duplicates)',    $this->counts['skipped']],
        ]);

        $this->info($this->dryRun ? 'Dry run complete — nothing was written.' : 'Migration complete.');
        return 0;
    }

    // ─── Assessment Applications ───────────────────────────────────────────────

    private function migrateApplications(): void
    {
        $this->info('Migrating assessment_applications...');
        $rows = $this->pg->query("SELECT * FROM assessment_applications ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
        $bar  = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            $token = $row['token'] ?? null;
            if ($token && DB::table('assessment_applications')->where('token', $token)->exists()) {
                $this->counts['skipped']++;
                $bar->advance();
                continue;
            }

            // Unpack JSONB columns
            $basic    = $this->decodeJson($row['basic_info']   ?? null);
            $vehicle  = $this->decodeJson($row['vehicle_info'] ?? null);
            $medical  = $this->decodeJson($row['medical_contact'] ?? null);

            $record = [
                'token'                    => $token,
                'order_id'                 => $row['order_id'] ?? null,
                'stripe_payment_intent_id' => $row['stripe_payment_intent_id'] ?? null,
                'stripe_session_id'        => $row['stripe_session_id'] ?? null,
                'payment_status'           => $row['payment_status'] ?? 'pending',
                'amount_paid'              => $row['amount_paid'] ?? null,
                // basic_info
                'first_name'   => $basic['firstName']   ?? $row['first_name']   ?? null,
                'last_name'    => $basic['lastName']    ?? $row['last_name']    ?? null,
                'email'        => $basic['email']       ?? $row['email']        ?? null,
                'phone'        => $basic['phone']       ?? $row['phone']        ?? null,
                'dob'          => $this->parseDate($basic['dateOfBirth'] ?? $row['dob'] ?? null),
                'address'      => $basic['address']     ?? $row['address']      ?? null,
                'eircode'      => $basic['eircode']     ?? $row['eircode']      ?? null,
                // vehicle_info
                'license_number'   => $vehicle['licenseNumber']  ?? $row['license_number']   ?? null,
                'license_category' => $vehicle['licenseCategory']?? $row['license_category']  ?? null,
                'license_expiry'   => $this->parseDate($vehicle['licenseExpiry'] ?? $row['license_expiry'] ?? null),
                'vehicle_make'     => $vehicle['vehicleMake']    ?? $row['vehicle_make']      ?? null,
                'vehicle_model'    => $vehicle['vehicleModel']   ?? $row['vehicle_model']     ?? null,
                'vehicle_year'     => $vehicle['vehicleYear']    ?? $row['vehicle_year']      ?? null,
                'vehicle_reg'      => $vehicle['vehicleReg']     ?? $row['vehicle_reg']       ?? null,
                'insurance_valid'  => $this->parseBool($vehicle['hasInsurance'] ?? $row['insurance_valid'] ?? null),
                'nct_valid'        => $this->parseBool($vehicle['hasNct']       ?? $row['nct_valid']       ?? null),
                'tax_valid'        => $this->parseBool($vehicle['hasTax']       ?? $row['tax_valid']       ?? null),
                // medical_contact
                'referral_reason'    => $medical['referralReason']    ?? $row['referral_reason']    ?? null,
                'gp_name'            => $medical['gpName']            ?? $row['gp_name']            ?? null,
                'gp_phone'           => $medical['gpPhone']           ?? $row['gp_phone']           ?? null,
                'gp_address'         => $medical['gpAddress']         ?? $row['gp_address']         ?? null,
                'consultant_name'    => $medical['consultantName']    ?? $row['consultant_name']    ?? null,
                'consultant_phone'   => $medical['consultantPhone']   ?? $row['consultant_phone']   ?? null,
                'medical_notes'      => $medical['medicalNotes']      ?? $row['medical_notes']      ?? null,
                'status'             => $row['status']             ?? 'pending',
                'submitted_at'       => $row['submitted_at']      ?? null,
                'created_at'         => $row['created_at']        ?? now(),
                'updated_at'         => $row['updated_at']        ?? now(),
            ];

            if (!$this->dryRun) {
                DB::table('assessment_applications')->insert($record);
            }
            $this->counts['applications']++;
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    // ─── HCP Referrals ─────────────────────────────────────────────────────────

    private function migrateHcpReferrals(): void
    {
        $this->info('Migrating hcp_referrals...');

        // Detect which columns exist in the source table
        $cols = $this->pg->query("SELECT column_name FROM information_schema.columns WHERE table_name='hcp_referrals'")->fetchAll(PDO::FETCH_COLUMN);
        $rows = $this->pg->query("SELECT * FROM hcp_referrals ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
        $bar  = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            // Best-effort field mapping from old schema → new schema
            $record = [
                'hcp_name'            => $row['hcp_name']            ?? $row['name']         ?? 'Unknown',
                'hcp_registration_no' => $row['hcp_registration_no'] ?? $row['registration_no'] ?? '',
                'hcp_practice'        => $row['hcp_practice']        ?? $row['hcp_organisation'] ?? $row['practice'] ?? '',
                'hcp_email'           => $row['hcp_email']           ?? $row['email']         ?? '',
                'hcp_phone'           => $row['hcp_phone']           ?? $row['phone']         ?? '',
                'patient_full_name'   => $row['patient_full_name']   ?? trim(($row['patient_first_name'] ?? '') . ' ' . ($row['patient_last_name'] ?? '')) ?: 'Unknown',
                'patient_dob'         => $this->parseDate($row['patient_dob'] ?? null) ?? '1900-01-01',
                'patient_pps'         => $row['patient_pps']         ?? null,
                'reason_for_referral' => $row['reason_for_referral'] ?? $row['diagnosis']    ?? $row['reason'] ?? '',
                'clinical_notes'      => $row['clinical_notes']      ?? $row['additional_notes'] ?? $row['notes'] ?? '',
                'consent'             => $this->parseBool($row['consent'] ?? true),
                'status'              => $row['status']              ?? 'new',
                'synced_to_sheets'    => $this->parseBool($row['synced_to_sheets'] ?? false),
                'created_at'          => $row['created_at'] ?? now(),
                'updated_at'          => $row['updated_at'] ?? now(),
            ];

            if (!$this->dryRun) {
                DB::table('hcp_referrals')->insert($record);
            }
            $this->counts['hcp_referrals']++;
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    // ─── Payments ─────────────────────────────────────────────────────────────

    private function migratePayments(): void
    {
        $this->info('Migrating payments...');
        $rows = $this->pg->query("SELECT * FROM payments ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
        $bar  = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            $stripeId = $row['stripe_payment_intent_id'] ?? null;
            if ($stripeId && DB::table('payments')->where('stripe_payment_intent_id', $stripeId)->exists()) {
                $this->counts['skipped']++;
                $bar->advance();
                continue;
            }

            // Resolve application_id from Laravel DB
            $appId = null;
            if ($stripeId) {
                $appId = DB::table('assessment_applications')
                    ->where('stripe_payment_intent_id', $stripeId)
                    ->value('id');
            }

            $record = [
                'application_id'           => $appId,
                'stripe_payment_intent_id' => $stripeId,
                'stripe_session_id'        => $row['stripe_session_id'] ?? null,
                'amount'                   => $row['amount'] ?? $row['amount_paid'] ?? 0,
                'currency'                 => $row['currency'] ?? 'eur',
                'status'                   => $row['status'] ?? 'succeeded',
                'customer_email'           => $row['customer_email'] ?? $row['email'] ?? null,
                'customer_name'            => $row['customer_name'] ?? null,
                'created_at'               => $row['created_at'] ?? now(),
                'updated_at'               => $row['updated_at'] ?? now(),
            ];

            if (!$this->dryRun) {
                DB::table('payments')->insert($record);
            }
            $this->counts['payments']++;
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    private function connectPostgres(string $url): PDO
    {
        $parsed = parse_url($url);
        $dsn    = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $parsed['host'] ?? 'localhost',
            $parsed['port'] ?? 5432,
            ltrim($parsed['path'] ?? '', '/')
        );
        return new PDO($dsn, $parsed['user'] ?? '', $parsed['pass'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    private function decodeJson(?string $value): array
    {
        if (!$value) return [];
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function parseDate(?string $value): ?string
    {
        if (!$value) return null;
        try {
            return (new \DateTime($value))->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }

    private function parseBool(mixed $value): bool
    {
        if (is_bool($value)) return $value;
        if (in_array($value, [1, '1', 't', 'true', 'yes'], true)) return true;
        return false;
    }
}
