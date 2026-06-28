<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\EmailService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private const TABS = ['stripe', 'email', 'menus', 'tracking', 'sheets', 'security', 'code'];

    public function index(Request $request)
    {
        $tab = in_array($request->get('tab'), self::TABS) ? $request->get('tab') : 'stripe';
        return view('admin.settings.index', compact('tab'));
    }

    public function update(Request $request)
    {
        $tab = $request->get('tab', 'stripe');

        switch ($tab) {

            case 'stripe':
                $this->setIfPresent($request, [
                    'assessment_fee',
                    'stripe_mode',
                    'stripe_publishable_key_test', 'stripe_secret_key_test',
                    'stripe_publishable_key_live', 'stripe_secret_key_live',
                ]);
                break;

            case 'email':
                $this->setIfPresent($request, [
                    'notification_email', 'cc_email',
                    'mail_from_address', 'mail_host', 'mail_port', 'mail_username',
                ]);
                if ($request->filled('mail_password')) {
                    Setting::set('mail_password', $request->input('mail_password'));
                }
                break;

            case 'menus':
                $this->setMenuJson($request, 'header_menu');
                $this->setMenuJson($request, 'footer_main_menu');
                $this->setMenuJson($request, 'footer_user_menu');
                $this->setIfPresent($request, [
                    'social_facebook', 'social_twitter', 'social_linkedin',
                    'footer_tagline', 'partner_image_url',
                ]);
                break;

            case 'tracking':
                $this->setIfPresent($request, ['ga4_id', 'gtm_id', 'pixel_id']);
                break;

            case 'sheets':
                $this->setIfPresent($request, ['google_sheets_id', 'assessments_sheet_id']);
                if ($request->filled('google_service_account_json')) {
                    $json = trim($request->input('google_service_account_json'));
                    if (!json_decode($json)) {
                        return redirect()
                            ->route('admin.settings.index', ['tab' => $tab])
                            ->with('error', 'Invalid JSON — service account not saved.');
                    }
                    Setting::set('google_service_account_json', $json);
                }
                break;

            case 'security':
                Setting::set('recaptcha_enabled', $request->has('recaptcha_enabled') ? '1' : '0');
                $this->setIfPresent($request, [
                    'recaptcha_version', 'recaptcha_site_key',
                    'recaptcha_threshold',
                ]);
                if ($request->filled('recaptcha_secret_key')) {
                    Setting::set('recaptcha_secret_key', $request->input('recaptcha_secret_key'));
                }
                break;

            case 'code':
                Setting::set('custom_head_code', $request->input('custom_head_code', ''));
                break;
        }

        return redirect()
            ->route('admin.settings.index', ['tab' => $tab])
            ->with('success', 'Settings saved.');
    }

    public function testEmail(Request $request)
    {
        $request->validate(['to' => 'required|email']);
        $emailService = app(EmailService::class);
        $ok = $emailService->send(
            $request->input('to'),
            'DAI Admin',
            'DAI Admin – SMTP Test Email',
            '<p>This is a test email from your DAI admin panel. If you received this, your SMTP settings are working correctly.</p>',
            'Test Email'
        );
        if ($ok) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'error' => $emailService->getLastError() ?: 'Send failed — check your SMTP settings.'], 500);
    }

    private function setIfPresent(Request $request, array $keys): void
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key, ''));
            }
        }
    }

    private function setMenuJson(Request $request, string $key): void
    {
        $labels = $request->input("{$key}_label", []);
        $hrefs  = $request->input("{$key}_href", []);
        $items  = [];
        foreach ($labels as $i => $label) {
            if (!empty(trim($label)) || !empty(trim($hrefs[$i] ?? ''))) {
                $items[] = ['label' => trim($label), 'href' => trim($hrefs[$i] ?? '')];
            }
        }
        Setting::set($key, json_encode($items));
    }
}
