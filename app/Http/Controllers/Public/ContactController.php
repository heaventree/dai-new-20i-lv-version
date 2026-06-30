<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\CmsPage;
use App\Models\Setting;
use App\Services\EmailService;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
class ContactController extends Controller
{
    public function index()
    {
        $page = CmsPage::where('slug', 'contact')->first();
        return view('public.contact', compact('page'));
    }
    public function submit(Request $request, RecaptchaService $recaptcha)
    {
        if ($request->filled('website_url')) {
            \Log::info('Contact submit blocked by honeypot', ['website_url' => $request->input('website_url')]);
            return back()->with('success', 'Thank you for your message. We will get back to you within 2 business days.');
        }
        if (!$recaptcha->verify($request->input('recaptcha_token'), 'contact')) {
            return back()->withInput()->with('error', 'We could not verify your submission. Please try again.');
        }
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'subject'    => 'required|string|max:200',
            'message'    => 'required|string|max:2000',
        ]);
        $fullMessage = $request->subject
            ? "Subject: {$request->subject}\n\n{$request->message}"
            : $request->message;
        ContactSubmission::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => null,
            'message'    => $fullMessage,
        ]);
        $fullName    = $request->first_name . ' ' . $request->last_name;
        // ORIGINAL: DAI feedback 26-06-26 default was info@driverassessmentsireland.ie
        $adminEmail  = Setting::get('notification_email', 'info@dai.ie');
        $emailService = app(EmailService::class);
        $emailService->sendFromTemplate('contact-auto-reply', $request->email, $fullName, [
            'name' => $fullName,
        ]);
        $emailService->send(
            $adminEmail,
            'DAI Admin',
            'New Contact Enquiry – ' . $fullName,
            "A new contact enquiry has been submitted.\n\nFrom: {$fullName} <{$request->email}>\nSubject: {$request->subject}\n\nMessage:\n{$request->message}"
        );
        return back()->with('success', 'Thank you for your message. We will get back to you within 2 business days.');
    }
}
