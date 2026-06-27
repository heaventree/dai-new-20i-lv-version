<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\AssessmentApplication;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\EmailService;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class AssessmentController extends Controller
{
    public function index()
    {
        $fee = number_format((float)Setting::get('assessment_fee', '235'), 0);
        return view('public.assessment.index', compact('fee'));
    }

    public function testBypass()
    {
        if (app()->environment('production') && env('STRIPE_SECRET')) {
            abort(404);
        }
        $token = Str::uuid()->toString();
        $app = AssessmentApplication::create([
            'token'                    => $token,
            'stripe_session_id'        => 'test_' . Str::random(24),
            'stripe_payment_intent_id' => 'pi_test_' . Str::random(24),
            'payment_status'           => 'paid',
            'amount_paid'              => 235.00,
            'email'                    => 'test@dai.ie',
            'status'                   => 'pending',
        ]);
        return redirect()->route('assessment.application', ['token' => $token]);
    }

    public function createCheckout(Request $request)
    {
        $request->validate(['email' => 'required|email', 'name' => 'required|string|max:200']);
        $fee = (int)((float)Setting::get('assessment_fee', '235') * 100);
        $stripeMode   = Setting::get('stripe_mode', 'test');
        $stripeSecret = $stripeMode === 'live'
            ? (Setting::get('stripe_secret_key_live') ?: env('STRIPE_SECRET'))
            : (Setting::get('stripe_secret_key_test') ?: env('STRIPE_SECRET'));
        Stripe::setApiKey($stripeSecret);
        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'eur',
                        'unit_amount'  => $fee,
                        'product_data' => ['name' => 'Driver Assessment - Driver Assessments Ireland'],
                    ],
                    'quantity' => 1,
                ]],
                'mode'           => 'payment',
                'customer_email' => $request->email,
                'metadata'       => ['customer_name' => $request->name],
                'success_url'    => route('assessment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'     => route('assessment.index'),
            ]);
            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment could not be initiated. Please try again.');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->session_id;
        if (!$sessionId) return redirect()->route('assessment.index');
        $existing = AssessmentApplication::where('stripe_session_id', $sessionId)->first();
        if ($existing) return view('public.assessment.application', ['application' => $existing, 'step' => 1]);
        try {
            $stripeMode   = Setting::get('stripe_mode', 'test');
            $stripeSecret = $stripeMode === 'live'
                ? (Setting::get('stripe_secret_key_live') ?: env('STRIPE_SECRET'))
                : (Setting::get('stripe_secret_key_test') ?: env('STRIPE_SECRET'));
            Stripe::setApiKey($stripeSecret);
            $session = StripeSession::retrieve($sessionId);
            if ($session->payment_status !== 'paid') return redirect()->route('assessment.index')->with('error', 'Payment not completed.');

            $customerName = $session->metadata->customer_name ?? $session->customer_email;
            $token = Str::uuid()->toString();

            $app = AssessmentApplication::create([
                'token'                    => $token,
                'stripe_session_id'        => $sessionId,
                'stripe_payment_intent_id' => $session->payment_intent,
                'payment_status'           => 'paid',
                'amount_paid'              => $session->amount_total / 100,
                'email'                    => $session->customer_email,
                'status'                   => 'pending',
            ]);

            Payment::create([
                'stripe_payment_intent_id' => $session->payment_intent,
                'stripe_session_id'        => $sessionId,
                'customer_email'           => $session->customer_email,
                'customer_name'            => $customerName,
                'amount'                   => $session->amount_total / 100,
                'currency'                 => 'eur',
                'status'                   => 'succeeded',
                'application_id'           => $app->id,
            ]);

            $emailService = app(EmailService::class);
            $emailService->sendFromTemplate('assessment-confirmation', $session->customer_email, $customerName, [
                'name'     => $customerName,
                'amount'   => number_format($app->amount_paid, 2),
                'order_id' => $token,
            ]);

            return redirect()->route('assessment.application', ['token' => $token]);
        } catch (\Exception $e) {
            return redirect()->route('assessment.index')->with('error', 'Could not verify payment. Contact us at info@dai.ie');
        }
    }

    public function application(Request $request, string $token)
    {
        $application = AssessmentApplication::where('token', $token)->where('payment_status', 'paid')->firstOrFail();
        $step = (int)$request->query('step', 1);
        return view('public.assessment.application', compact('application', 'step'));
    }

    public function saveStep(Request $request, string $token)
    {
        $application = AssessmentApplication::where('token', $token)->where('payment_status', 'paid')->firstOrFail();
        $step = (int)$request->input('step', 1);

        $rules = [];
        if ($step === 2) {
            $rules = [
                'full_name' => 'required|string|max:200',
                'email'     => 'required|email',
                'phone'     => 'required|string|max:50',
                'dob'       => 'required|date|before_or_equal:today',
                'address'   => 'required|string|max:500',
            ];
        } elseif ($step === 3) {
            $rules = [
                'license_number' => 'required|string|max:50',
                'license_expiry' => 'required|date',
            ];
        } elseif ($step === 4) {
            $rules = [
                'referral_reason' => 'required|string|max:2000',
                'signature_data'  => 'required|string',
                'signature_date'  => 'required|date',
            ];
        }

        $validated = $request->validate($rules);

        $data = [];
        if ($step === 2) {
            $nameParts = explode(' ', trim($request->input('full_name', '')), 2);
            $data['title']      = $request->input('title');
            $data['first_name'] = $nameParts[0];
            $data['last_name']  = $nameParts[1] ?? '';
            $data['email']      = $request->input('email');
            $data['phone']      = $request->input('phone');
            $data['dob']        = $request->input('dob');
            $data['address']    = $request->input('address');
            $data['eircode']    = $request->input('eircode');
        } elseif ($step === 3) {
            $data['license_number']           = $request->input('license_number');
            $data['license_expiry']           = $request->input('license_expiry');
            $data['motor_tax_expiry']         = $request->input('motor_tax_expiry') ?: null;
            $data['vehicle_insurance_expiry'] = $request->input('vehicle_insurance_expiry') ?: null;
            $data['insurance_company']        = $request->input('insurance_company');
            $data['nct_due']                  = $request->input('nct_due') ?: null;
        } elseif ($step === 4) {
            $data['referral_reason']         = $request->input('referral_reason');
            $data['gp_name_address']         = $request->input('gp_name_address');
            $data['consultant_name_address'] = $request->input('consultant_name_address');
            $data['alt_contact_name']        = $request->input('alt_contact_name');
            $data['alt_contact_phone']       = $request->input('alt_contact_phone');
            $data['signature_data']          = $request->input('signature_data');
            $data['signature_date']          = $request->input('signature_date');
        }

        $application->update(array_filter($data, fn($v) => !is_null($v)));

        return redirect()->route('assessment.application', ['token' => $token, 'step' => $step + 1]);
    }

    public function submit(Request $request, string $token)
    {
        $application = AssessmentApplication::where('token', $token)->where('payment_status', 'paid')->firstOrFail();
        $application->update(['status' => 'submitted', 'submitted_at' => now()]);

        $sheets = app(GoogleSheetsService::class);
        if ($sheets->appendAssessment($application->toArray())) {
            $application->update(['synced_to_sheets' => true]);
        }

        $fullName    = trim(($application->title ? $application->title . ' ' : '') . $application->first_name . ' ' . $application->last_name);
        $adminEmail  = Setting::get('notification_email', 'info@dai.ie');
        $emailService = app(EmailService::class);

        // Send confirmation to customer
        $emailService->sendFromTemplate('assessment-submitted', $application->email, $fullName, [
            'name'     => $fullName,
            'order_id' => $token,
        ]);

        // Notify admin
        $emailService->send(
            $adminEmail,
            'DAI Admin',
            'Assessment Application Submitted – ' . $fullName,
            "A new assessment application has been submitted.\n\nApplicant: {$fullName}\nEmail: {$application->email}\nToken: {$token}\n\nLogin to the admin panel to view full details."
        );

        return redirect()->route('assessment.application', ['token' => $token, 'step' => 6]);
    }
}
