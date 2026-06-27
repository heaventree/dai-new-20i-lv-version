<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplatesController extends Controller
{
    private const TAB_ORDER = [
        'hcp-referral-confirmation',
        'payment-received',
        'assessment-confirmation',
        'contact-auto-reply',
    ];

    private const TAB_META = [
        'hcp-referral-confirmation' => [
            'label'       => 'HCP Referral',
            'heading'     => 'HCP Referral Email',
            'description' => 'Sent when a healthcare professional submits a patient referral.',
            'variables'   => ['{{patientName}}', '{{hcpName}}', '{{hcpEmail}}', '{{hcpPhone}}'],
        ],
        'payment-received' => [
            'label'       => 'Payment Received',
            'heading'     => 'Payment Received Email',
            'description' => 'Sent to the client when their assessment payment is confirmed.',
            'variables'   => ['{{name}}', '{{amount}}', '{{orderId}}'],
        ],
        'assessment-confirmation' => [
            'label'       => 'Assessment Submitted',
            'heading'     => 'Assessment Submitted Email',
            'description' => 'Sent when a client completes the 5-step assessment application form.',
            'variables'   => ['{{name}}', '{{orderId}}'],
        ],
        'contact-auto-reply' => [
            'label'       => 'Contact Enquiry',
            'heading'     => 'Contact Enquiry Auto-Reply',
            'description' => 'Sent automatically when someone submits the contact form.',
            'variables'   => ['{{name}}'],
        ],
    ];

    public function index(Request $request)
    {
        $templates = EmailTemplate::whereIn('slug', self::TAB_ORDER)
            ->get()->keyBy('slug');

        $activeTab = $request->get('tab', self::TAB_ORDER[0]);
        if (!array_key_exists($activeTab, self::TAB_META)) {
            $activeTab = self::TAB_ORDER[0];
        }

        return view('admin.email-templates.index', [
            'templates' => $templates,
            'tabOrder'  => self::TAB_ORDER,
            'tabMeta'   => self::TAB_META,
            'activeTab' => $activeTab,
        ]);
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return redirect()->route('admin.email-templates.index', ['tab' => $emailTemplate->slug]);
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'subject' => 'required|string|max:500',
            'body'    => 'required|string',
        ]);

        $emailTemplate->update([
            'subject'    => $request->subject,
            'body'       => $request->body,
            'is_active'  => $request->boolean('is_active'),
            'is_default' => false,
        ]);

        return redirect()
            ->route('admin.email-templates.index', ['tab' => $emailTemplate->slug])
            ->with('success', '"' . $emailTemplate->name . '" template saved.');
    }
}
