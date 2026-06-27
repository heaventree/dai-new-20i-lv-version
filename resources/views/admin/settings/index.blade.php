@extends('layouts.admin')
@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')

@php
$tabs = [
    'stripe'   => 'Payment Gateway',
    'email'    => 'Email Notifications',
    'menus'    => 'Menus & Social',
    'tracking' => 'Tracking & Analytics',
    'sheets'   => 'Google Sheets',
    'security' => 'Security',
    'code'     => 'Custom Code',
];
$s = fn($k, $d='') => \App\Models\Setting::get($k, $d);
@endphp

<p style="color:#6b7280;margin-bottom:20px;font-size:0.9375rem">Configure payment gateway, email notifications, analytics, and site integrations</p>

@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:0.9375rem;font-weight:600">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:0.9375rem">
    {{ session('error') }}
</div>
@endif

{{-- Tab bar --}}
<div style="display:flex;gap:2px;flex-wrap:wrap;border-bottom:2px solid #e5e7eb;margin-bottom:28px">
@foreach($tabs as $slug => $label)
<a href="{{ route('admin.settings.index', ['tab' => $slug]) }}"
   style="padding:9px 14px;font-size:0.875rem;font-weight:600;border-radius:6px 6px 0 0;text-decoration:none;white-space:nowrap;
          {{ $tab === $slug
              ? 'background:#fff;border:1.5px solid #e5e7eb;border-bottom:2px solid #fff;color:#111827;margin-bottom:-2px'
              : 'color:#6b7280' }}">
    {{ $label }}
</a>
@endforeach
</div>

<form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}">
@csrf
<input type="hidden" name="tab" value="{{ $tab }}">

{{-- ─────────────────────────────────────────────── --}}
{{-- PAYMENT GATEWAY                                 --}}
{{-- ─────────────────────────────────────────────── --}}
@if($tab === 'stripe')
@php
    $stripeMode = $s('stripe_mode','test');
    $testPk     = $s('stripe_publishable_key_test');
    $testSk     = $s('stripe_secret_key_test');
    $livePk     = $s('stripe_publishable_key_live');
    $liveSk     = $s('stripe_secret_key_live');
    $hasTest    = $testPk && $testSk;
    $hasLive    = $livePk && $liveSk;
@endphp

{{-- Stripe Mode card --}}
<div class="settings-card">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:18px">
        <div>
            <h2 class="card-title">Stripe Mode</h2>
            <p class="card-desc">Switch between test mode (sandbox) and live mode (real payments). Always test your integration fully before going live.</p>
        </div>
        <span class="badge-{{ $stripeMode === 'live' ? 'green' : 'amber' }}">
            {{ $stripeMode === 'live' ? '⚡ Live Mode Active' : '🧪 Test Mode Active' }}
        </span>
    </div>
    <div style="display:flex;gap:10px;max-width:300px;margin-bottom:18px">
        <label class="mode-btn {{ $stripeMode==='test'?'mode-btn-amber':'' }}">
            <input type="radio" name="stripe_mode" value="test" {{ $stripeMode==='test'?'checked':'' }}>
            🧪 Test Mode
        </label>
        <label class="mode-btn {{ $stripeMode==='live'?'mode-btn-green':'' }}">
            <input type="radio" name="stripe_mode" value="live" {{ $stripeMode==='live'?'checked':'' }}>
            ⚡ Live Mode
        </label>
    </div>
    @if($stripeMode === 'test')
    <div class="info-amber">
        <strong>Test mode:</strong> Use Stripe test card <code>4242 4242 4242 4242</code> with any future date and CVC. No real charges are made.
    </div>
    @else
    <div class="info-green">
        <strong>Live mode:</strong> Real card charges will be processed. Ensure you have tested thoroughly in test mode first.
    </div>
    @endif
</div>

{{-- Test Keys --}}
<div class="settings-card" style="{{ $stripeMode==='test'?'outline:2px solid #f59e0b;outline-offset:2px':'' }}">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px">
        <h2 class="card-title" style="margin:0">Test Keys</h2>
        <span class="badge-amber-sm">🧪 Sandbox</span>
        @if($stripeMode==='test') <span class="badge-orange-sm">Active</span> @endif
        @if($hasTest) <span class="badge-green-sm">✓ Configured</span> @endif
    </div>
    <p class="card-desc" style="margin-bottom:20px">Get your test keys from the <a href="https://dashboard.stripe.com/test/apikeys" target="_blank" class="link">Stripe Dashboard → Developers → API Keys</a> (toggle to Test Mode in the Stripe dashboard).</p>
    <div class="form-field">
        <label class="field-label">Test Publishable Key</label>
        <input type="text" name="stripe_publishable_key_test" value="{{ $testPk }}"
               placeholder="pk_test_…" class="field-input" style="font-family:monospace">
        <p class="field-hint">Starts with <code>pk_test_</code> — safe to expose in the browser.</p>
    </div>
    <div class="form-field">
        <label class="field-label">Test Secret Key</label>
        <div class="secret-wrap">
            <input type="password" id="test-sk" name="stripe_secret_key_test" value="{{ $testSk }}"
                   placeholder="sk_test_…" class="field-input" style="font-family:monospace;padding-right:42px">
            <button type="button" onclick="toggleSecret('test-sk',this)" class="eye-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        </div>
        <p class="field-hint">Starts with <code>sk_test_</code> — kept server-side only, never exposed.</p>
    </div>
</div>

{{-- Live Keys --}}
<div class="settings-card" style="{{ $stripeMode==='live'?'outline:2px solid #22c55e;outline-offset:2px':'' }}">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px">
        <h2 class="card-title" style="margin:0">Live Keys</h2>
        <span class="badge-green-sm">⚡ Production</span>
        @if($stripeMode==='live') <span class="badge-green-solid-sm">Active</span> @endif
        @if($hasLive) <span class="badge-green-sm">✓ Configured</span> @endif
    </div>
    <p class="card-desc" style="margin-bottom:20px">Get your live keys from the <a href="https://dashboard.stripe.com/apikeys" target="_blank" class="link">Stripe Dashboard → Developers → API Keys</a> (switch to Live Mode in the Stripe dashboard).</p>
    <div class="form-field">
        <label class="field-label">Live Publishable Key</label>
        <input type="text" name="stripe_publishable_key_live" value="{{ $livePk }}"
               placeholder="pk_live_…" class="field-input" style="font-family:monospace">
        <p class="field-hint">Starts with <code>pk_live_</code> — safe to expose in the browser.</p>
    </div>
    <div class="form-field">
        <label class="field-label">Live Secret Key</label>
        <div class="secret-wrap">
            <input type="password" id="live-sk" name="stripe_secret_key_live" value="{{ $liveSk }}"
                   placeholder="sk_live_…" class="field-input" style="font-family:monospace;padding-right:42px">
            <button type="button" onclick="toggleSecret('live-sk',this)" class="eye-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        </div>
        <p class="field-hint">Starts with <code>sk_live_</code> — kept server-side only, never exposed.</p>
    </div>
</div>

{{-- How to find keys --}}
<div class="settings-card" style="background:#f9fafb">
    <p class="card-title">How to find your Stripe API Keys:</p>
    <ol style="font-size:0.9rem;color:#374151;padding-left:18px;margin:8px 0 0;line-height:1.8">
        <li>Log in to <a href="https://dashboard.stripe.com" target="_blank" class="link">dashboard.stripe.com</a></li>
        <li>Click <strong>Developers</strong> in the top menu</li>
        <li>Click <strong>API Keys</strong></li>
        <li>Toggle between <strong>Test</strong> and <strong>Live</strong> mode at the top of the page</li>
        <li>Copy the Publishable Key and reveal/copy the Secret Key</li>
    </ol>
</div>

{{-- ─────────────────────────────────────────────── --}}
{{-- EMAIL NOTIFICATIONS                             --}}
{{-- ─────────────────────────────────────────────── --}}
@elseif($tab === 'email')

<div class="settings-card">
    <h2 class="card-title">Notification Email</h2>
    <p class="card-desc" style="margin-bottom:20px">All form submissions (HCP Referral, Payment, Assessment, Contact) will send a notification to this address.</p>
    <div class="form-field">
        <label class="field-label">Primary Admin Email Address</label>
        <input type="email" name="notification_email" value="{{ $s('notification_email') }}"
               placeholder="info@driverassessmentsireland.ie" class="field-input">
        <p class="field-hint">All admin notification emails (HCP Referral, Payment, Assessment, Contact) are sent to this address.</p>
    </div>
    <div class="form-field">
        <label class="field-label">CC Email Address <span style="font-weight:400;color:#9ca3af">(optional)</span></label>
        <input type="email" name="cc_email" value="{{ $s('cc_email') }}"
               placeholder="e.g. manager@driverassessmentsireland.ie" class="field-input">
        <p class="field-hint">If set, all admin notification emails will also be CC'd to this address.</p>
    </div>
    <div class="info-blue">
        <strong>Customer copies:</strong> Customers automatically receive a confirmation copy at their own email address after each form submission.
    </div>
</div>

<div class="settings-card">
    <h2 class="card-title">SMTP Configuration</h2>
    <p class="card-desc" style="margin-bottom:20px">Set up your outgoing email server. Contact your email provider for these details. Gmail users: use <code style="font-size:0.8rem;background:#f3f4f6;padding:1px 5px;border-radius:4px">smtp.gmail.com</code> with an App Password.</p>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
            <label class="field-label">SMTP Host</label>
            <input type="text" name="mail_host" value="{{ $s('mail_host') }}" placeholder="smtp.dai.ie" class="field-input">
        </div>
        <div>
            <label class="field-label">SMTP Port</label>
            <input type="number" name="mail_port" value="{{ $s('mail_port','465') }}" placeholder="465" class="field-input">
        </div>
        <div>
            <label class="field-label">SMTP Username</label>
            <input type="text" name="mail_username" value="{{ $s('mail_username') }}" autocomplete="off" class="field-input">
        </div>
        <div>
            <label class="field-label">SMTP Password / App Password</label>
            <div class="secret-wrap">
                <input type="password" id="smtp-pw" name="mail_password" autocomplete="new-password"
                       placeholder="{{ $s('mail_password') ? '••••••••' : 'Enter password' }}"
                       class="field-input" style="padding-right:42px">
                <button type="button" onclick="toggleSecret('smtp-pw',this)" class="eye-btn">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
        </div>
        <div style="grid-column:1/-1">
            <label class="field-label">From Address <span style="font-weight:400;color:#9ca3af">(optional)</span></label>
            <input type="email" name="mail_from_address" value="{{ $s('mail_from_address') }}"
                   placeholder="{{ $s('mail_username') ?: 'info@driverassessmentsireland.ie' }}" class="field-input">
            <p class="field-hint">Defaults to your SMTP username if left blank. Most mail servers (including 20i) only allow sending from the address you authenticated with — if you get a 550 error, clear this field or set it to match your SMTP username.</p>
        </div>
    </div>
    <div class="info-blue" style="margin-top:16px">
        <strong>Forms covered:</strong> HCP Referral, Payment Confirmation, Assessment Submission, Contact Enquiry
    </div>
</div>

{{-- Send Test Email --}}
<div class="settings-card">
    <h2 class="card-title">Send Test Email</h2>
    <p class="card-desc" style="margin-bottom:16px">Verify your SMTP settings are working by sending a test email. Make sure you have saved your SMTP settings above first.</p>
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
        <div style="flex:1;min-width:220px">
            <label class="field-label">Recipient Address</label>
            <input type="email" id="test-email-addr" placeholder="your@email.com" class="field-input">
        </div>
        <button type="button" onclick="sendTestEmail()"
                style="padding:10px 20px;background:#132d5e;color:#fff;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:7px;white-space:nowrap">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Send Test Email
        </button>
    </div>
    <p id="test-email-result" style="margin-top:10px;font-size:0.875rem;color:#6b7280">The result will also appear in Email Logs.</p>
</div>

{{-- ─────────────────────────────────────────────── --}}
{{-- MENUS & SOCIAL                                  --}}
{{-- ─────────────────────────────────────────────── --}}
@elseif($tab === 'menus')
@php
    $headerMenu = json_decode($s('header_menu'), true) ?? [
        ['label'=>'Home','href'=>'/'],['label'=>'Services','href'=>'/services'],
        ['label'=>'HCP Referral Form','href'=>'/hcp-referral-form'],['label'=>'About DAI','href'=>'/about'],
        ['label'=>'DAI Team','href'=>'/team'],['label'=>'FAQ','href'=>'/faq'],['label'=>'Contact','href'=>'/contact'],
    ];
    $footerMain = json_decode($s('footer_main_menu'), true) ?? $headerMenu;
    $footerUser = json_decode($s('footer_user_menu'), true) ?? [
        ['label'=>'Cookie Policy','href'=>'/cookie-policy'],
        ['label'=>'Privacy Policy','href'=>'/privacy-policy'],
        ['label'=>'Terms Of Use','href'=>'/terms-of-use'],
    ];
@endphp

{{-- Social Media Links --}}
<div class="settings-card">
    <h2 class="card-title">Social Media Links</h2>
    <p class="card-desc" style="margin-bottom:20px">Add your social media profile URLs. Icons will appear in the header and footer. Leave blank to hide an icon.</p>
    @foreach([
        ['name'=>'social_facebook','label'=>'Facebook URL','icon'=>'f'],
        ['name'=>'social_twitter', 'label'=>'Twitter / X URL','icon'=>'x'],
        ['name'=>'social_linkedin','label'=>'LinkedIn URL','icon'=>'in'],
    ] as $social)
    <div class="form-field">
        <label class="field-label">{{ $social['label'] }}</label>
        <input type="url" name="{{ $social['name'] }}" value="{{ $s($social['name']) }}"
               placeholder="https://…" class="field-input">
    </div>
    @endforeach
</div>

{{-- Footer Tagline --}}
<div class="settings-card">
    <h2 class="card-title">Footer Tagline</h2>
    <p class="card-desc" style="margin-bottom:12px">The text shown across the top of the footer, next to the social icons.</p>
    <input type="text" name="footer_tagline"
           value="{{ $s('footer_tagline','Have questions about this service? Email our team for assistance.') }}"
           class="field-input">
</div>

{{-- Partner / Enterprise Image --}}
<div class="settings-card">
    <h2 class="card-title">Partner / Enterprise Image</h2>
    <p class="card-desc" style="margin-bottom:12px">Paste the URL of a partner logo (e.g. Local Enterprise Office Galway) to display it in the footer. Leave blank to hide it.</p>
    <input type="url" name="partner_image_url" value="{{ $s('partner_image_url') }}"
           placeholder="https://example.com/partner-logo.png" class="field-input">
</div>

{{-- Header Navigation --}}
<div class="settings-card">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px">
        <div>
            <h2 class="card-title">Header Navigation</h2>
            <p class="card-desc">Links shown in the top navigation bar. Changes take effect immediately after saving.</p>
        </div>
        <button type="button" onclick="addMenuRow('header_menu')" class="btn-outline-sm">+ Add Link</button>
    </div>
    <div id="menu-header_menu" style="display:flex;flex-direction:column;gap:6px;margin-top:14px">
        @foreach($headerMenu as $item)
        <div class="menu-row">
            <svg style="width:14px;height:14px;color:#9ca3af;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
            <input type="text" name="header_menu_label[]" value="{{ $item['label'] }}" placeholder="Label" class="menu-input">
            <input type="text" name="header_menu_href[]" value="{{ $item['href'] }}" placeholder="/url" class="menu-input mono">
            <button type="button" onclick="this.closest('.menu-row').remove()" class="menu-del">×</button>
        </div>
        @endforeach
    </div>
    <button type="button" onclick="addMenuRow('header_menu')"
            style="margin-top:10px;padding:8px;width:100%;border:1.5px dashed #d1d5db;border-radius:7px;background:#fafafa;font-size:0.875rem;color:#6b7280;cursor:pointer">
        + Add Another Link
    </button>
</div>

{{-- Footer Our Menus --}}
<div class="settings-card">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px">
        <div>
            <h2 class="card-title">Footer — Our Menus</h2>
            <p class="card-desc">Links shown in the 'Our Menus' column of the footer.</p>
        </div>
        <button type="button" onclick="addMenuRow('footer_main_menu')" class="btn-outline-sm">+ Add Link</button>
    </div>
    <div id="menu-footer_main_menu" style="display:flex;flex-direction:column;gap:6px;margin-top:14px">
        @foreach($footerMain as $item)
        <div class="menu-row">
            <svg style="width:14px;height:14px;color:#9ca3af;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
            <input type="text" name="footer_main_menu_label[]" value="{{ $item['label'] }}" placeholder="Label" class="menu-input">
            <input type="text" name="footer_main_menu_href[]" value="{{ $item['href'] }}" placeholder="/url" class="menu-input mono">
            <button type="button" onclick="this.closest('.menu-row').remove()" class="menu-del">×</button>
        </div>
        @endforeach
    </div>
    <button type="button" onclick="addMenuRow('footer_main_menu')"
            style="margin-top:10px;padding:8px;width:100%;border:1.5px dashed #d1d5db;border-radius:7px;background:#fafafa;font-size:0.875rem;color:#6b7280;cursor:pointer">
        + Add Another Link
    </button>
</div>

{{-- Footer User Menus --}}
<div class="settings-card">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px">
        <div>
            <h2 class="card-title">Footer — User Menus</h2>
            <p class="card-desc">Links shown in the 'User Menus' column of the footer (e.g. Cookie Policy, Privacy Policy).</p>
        </div>
        <button type="button" onclick="addMenuRow('footer_user_menu')" class="btn-outline-sm">+ Add Link</button>
    </div>
    <div id="menu-footer_user_menu" style="display:flex;flex-direction:column;gap:6px;margin-top:14px">
        @foreach($footerUser as $item)
        <div class="menu-row">
            <svg style="width:14px;height:14px;color:#9ca3af;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
            <input type="text" name="footer_user_menu_label[]" value="{{ $item['label'] }}" placeholder="Label" class="menu-input">
            <input type="text" name="footer_user_menu_href[]" value="{{ $item['href'] }}" placeholder="/url" class="menu-input mono">
            <button type="button" onclick="this.closest('.menu-row').remove()" class="menu-del">×</button>
        </div>
        @endforeach
    </div>
    <button type="button" onclick="addMenuRow('footer_user_menu')"
            style="margin-top:10px;padding:8px;width:100%;border:1.5px dashed #d1d5db;border-radius:7px;background:#fafafa;font-size:0.875rem;color:#6b7280;cursor:pointer">
        + Add Another Link
    </button>
</div>

{{-- ─────────────────────────────────────────────── --}}
{{-- TRACKING & ANALYTICS                            --}}
{{-- ─────────────────────────────────────────────── --}}
@elseif($tab === 'tracking')

<div class="settings-card">
    <h2 class="card-title">Google Tag Manager</h2>
    <p class="card-desc" style="margin-bottom:16px">Enter your GTM Container ID (e.g. <code class="code-inline">GTM-XXXXXXX</code>). The GTM snippet will be injected automatically into every page.</p>
    <label class="field-label">GTM Container ID</label>
    <input type="text" name="gtm_id" value="{{ $s('gtm_id') }}" placeholder="GTM-XXXXXXX" class="field-input" style="font-family:monospace">
</div>

<div class="settings-card">
    <h2 class="card-title">Google Analytics 4</h2>
    <p class="card-desc" style="margin-bottom:16px">Enter your GA4 Measurement ID (e.g. <code class="code-inline">G-XXXXXXXXXX</code>). Only needed if you are not using GTM to fire GA4.</p>
    <label class="field-label">GA4 Measurement ID</label>
    <input type="text" name="ga4_id" value="{{ $s('ga4_id') }}" placeholder="G-XXXXXXXXXX" class="field-input" style="font-family:monospace">
</div>

<div class="settings-card">
    <h2 class="card-title">Facebook / Meta Pixel</h2>
    <p class="card-desc" style="margin-bottom:16px">Enter your Meta Pixel ID (numbers only, e.g. <code class="code-inline">1234567890123456</code>). The Pixel base code will be injected automatically.</p>
    <label class="field-label">Meta Pixel ID</label>
    <input type="text" name="pixel_id" value="{{ $s('pixel_id') }}" placeholder="1234567890123456" class="field-input" style="font-family:monospace">
</div>

{{-- ─────────────────────────────────────────────── --}}
{{-- GOOGLE SHEETS                                   --}}
{{-- ─────────────────────────────────────────────── --}}
@elseif($tab === 'sheets')
@php
    $hasServiceJson = !empty($s('google_service_account_json'));
    $sheetsId       = $s('google_sheets_id');
    $assessSheetId  = $s('assessments_sheet_id');
@endphp

<div class="settings-card">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:6px;flex-wrap:wrap">
        <div>
            <h2 class="card-title">Google Sheets Integration</h2>
            <p class="card-desc">Automatically sync form submissions to your Google Spreadsheets. HCP Referral submissions are written to the HCP sheet, and Assessment applications (after payment) are written to the Assessments sheet.</p>
        </div>
        @if($hasServiceJson && $sheetsId)
            <span class="badge-green-sm" style="font-size:0.8rem;padding:4px 12px">✓ Configured</span>
        @else
            <span class="badge-amber-sm" style="font-size:0.8rem;padding:4px 12px">Not configured</span>
        @endif
    </div>
</div>

<div class="settings-card">
    <h2 class="card-title">Spreadsheet IDs</h2>
    <p class="card-desc" style="margin-bottom:6px">Create two sheets in your Google Spreadsheet — one for HCP Referrals and one for Assessments. Copy the spreadsheet ID from the URL:</p>
    <p style="font-size:0.8125rem;color:#374151;background:#f3f4f6;padding:8px 12px;border-radius:6px;margin-bottom:18px;font-family:monospace">https://docs.google.com/spreadsheets/d/<strong>SPREADSHEET_ID</strong>/edit</p>
    <p class="field-hint" style="margin-bottom:18px">The sheet tabs must be named <strong>HCP Referrals</strong> and <strong>Assessments</strong> exactly.</p>
    <div class="form-field">
        <label class="field-label">HCP Referrals — Spreadsheet ID</label>
        <input type="text" name="google_sheets_id" value="{{ $sheetsId }}"
               placeholder="e.g. 1hvbRmC5qMUctk0733OK0283nEAvCWyRgLsoZiO0G7mw"
               class="field-input" style="font-family:monospace">
        <p class="field-hint">Columns synced: ID, Date, HCP Name, Address, Phone, Email, Patient Name, DOB, Address, Eircode, Phone, Email, Medical Condition, Medication, Other Info.</p>
    </div>
    <div class="form-field">
        <label class="field-label">Assessments — Spreadsheet ID</label>
        <input type="text" name="assessments_sheet_id" value="{{ $assessSheetId }}"
               placeholder="e.g. 1hvbRmC5qMUctk0733OK0283nEAvCWyRgLsoZiO0G7mw"
               class="field-input" style="font-family:monospace">
        <p class="field-hint">Synced on payment confirmation (basic data) and again on form submission (full data: Order ID, Payer, Amount, Vehicle, Medical Contact).</p>
    </div>
</div>

<div class="settings-card">
    <h2 class="card-title">Service Account JSON Key</h2>
    <p class="card-desc" style="margin-bottom:14px">Paste the full JSON key downloaded from Google Cloud Console. Steps to create one:</p>
    <ol style="font-size:0.875rem;color:#374151;padding-left:18px;margin:0 0 18px;line-height:2">
        <li>Go to <strong>Google Cloud Console → IAM & Admin → Service Accounts</strong></li>
        <li>Click <strong>Create Service Account</strong> — give it any name</li>
        <li>Open the account → <strong>Keys</strong> tab → <strong>Add Key → Create new key → JSON</strong></li>
        <li>Download the <code class="code-inline">.json</code> file and paste its contents below</li>
        <li>Copy the <code class="code-inline">client_email</code> value from the JSON and share your Google Spreadsheet with that email as <strong>Editor</strong></li>
    </ol>
    <label class="field-label">
        Service Account JSON
        @if($hasServiceJson)
            <span style="font-weight:400;color:#16a34a;font-size:0.8125rem;margin-left:8px">✓ Service account JSON is set</span>
        @endif
    </label>
    <textarea name="google_service_account_json" rows="10"
              placeholder='{"type":"service_account","project_id":"…"}&#10;Paste full JSON here to update (leave blank to keep existing)'
              style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:10px 13px;font-size:0.8125rem;font-family:monospace;resize:vertical;margin-top:6px;line-height:1.5"></textarea>
    <p class="field-hint">Leave blank to keep the existing key. Paste the full JSON only when replacing.</p>
</div>

{{-- Expected Column Headers --}}
<div class="settings-card">
    <h2 class="card-title">Expected Column Headers</h2>
    <p class="card-desc" style="margin-bottom:14px">Add a header row to your Google Sheets to label each column. The data is appended in this order:</p>

    <p style="font-size:0.875rem;font-weight:700;color:#374151;margin:0 0 6px">HCP Referrals sheet — tab name: <code class="code-inline">HCP Referrals</code></p>
    <div style="overflow-x:auto;margin-bottom:20px">
        <div style="display:flex;flex-wrap:wrap;gap:4px;font-size:0.75rem">
            @foreach(['ID','Date','HCP Name','HCP Address','HCP Phone','HCP Email','Patient Title','Patient Name','Patient Address','Patient Eircode','Patient DOB','Patient Phone','Patient Email','Alt Contact Name','Alt Contact Phone','Medical Condition','Medication Impairment','Medication Details','Other Info'] as $col)
            <span style="background:#f3f4f6;border:1px solid #e5e7eb;border-radius:4px;padding:3px 8px;font-family:monospace;color:#374151">{{ $col }}</span>
            @endforeach
        </div>
    </div>

    <p style="font-size:0.875rem;font-weight:700;color:#374151;margin:0 0 6px">Assessments sheet — tab name: <code class="code-inline">Assessments</code></p>
    <p style="font-size:0.8125rem;color:#6b7280;margin:0 0 5px">After payment (basic row):</p>
    <div style="display:flex;flex-wrap:wrap;gap:4px;font-size:0.75rem;margin-bottom:10px">
        @foreach(['ID','Order ID','Date','Payer Name','Payer Email','Booking #','Amount','Payment Status','Status','Payment Intent ID','Event'] as $col)
        <span style="background:#f3f4f6;border:1px solid #e5e7eb;border-radius:4px;padding:3px 8px;font-family:monospace;color:#374151">{{ $col }}</span>
        @endforeach
    </div>
    <p style="font-size:0.8125rem;color:#6b7280;margin:0 0 5px">After form submitted (full row):</p>
    <div style="display:flex;flex-wrap:wrap;gap:4px;font-size:0.75rem">
        @foreach(['ID','Order ID','Date','Payer Name','Payer Email','Booking #','Amount','Payment Status','Status','Title','First Name','Last Name','Phone','Address','DOB','Licence No','Vehicle Make','Model','Year','Reg','GP Name'] as $col)
        <span style="background:#f3f4f6;border:1px solid #e5e7eb;border-radius:4px;padding:3px 8px;font-family:monospace;color:#374151">{{ $col }}</span>
        @endforeach
    </div>
</div>

{{-- ─────────────────────────────────────────────── --}}
{{-- SECURITY                                        --}}
{{-- ─────────────────────────────────────────────── --}}
@elseif($tab === 'security')
@php
    $rcEnabled   = $s('recaptcha_enabled','0') === '1';
    $rcVersion   = $s('recaptcha_version','v3');
    $rcSiteKey   = $s('recaptcha_site_key');
    $rcSecretKey = $s('recaptcha_secret_key');
    $rcThreshold = $s('recaptcha_threshold','0.5');
@endphp

<div class="settings-card">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:16px;flex-wrap:wrap">
        <div>
            <h2 class="card-title">Google reCAPTCHA</h2>
            <p class="card-desc">Protect your public forms from spam bots and automated abuse. reCAPTCHA runs on the HCP Referral Form, Assessment Form, Contact form, and the Payment page.</p>
        </div>
        <span class="{{ $rcEnabled ? 'badge-green-sm' : 'badge-grey-sm' }}" style="font-size:0.8rem;padding:4px 12px">
            {{ $rcEnabled ? '✓ Enabled' : 'Disabled' }}
        </span>
    </div>
    {{-- Toggle switch --}}
    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:14px 18px;background:#f9fafb;border-radius:8px;border:1.5px solid #e5e7eb">
        <div onclick="document.getElementById('rc-chk').click()"
             style="position:relative;width:44px;height:24px;flex-shrink:0;cursor:pointer">
            <input type="checkbox" id="rc-chk" name="recaptcha_enabled" value="1"
                   {{ $rcEnabled?'checked':'' }}
                   style="position:absolute;opacity:0;width:100%;height:100%;cursor:pointer;z-index:2">
            <div id="rc-track"
                 style="width:44px;height:24px;border-radius:12px;background:{{ $rcEnabled?'#132d5e':'#d1d5db' }};transition:background 0.2s;position:relative">
                <div id="rc-thumb"
                     style="position:absolute;top:3px;left:{{ $rcEnabled?'23':'3' }}px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.2)"></div>
            </div>
        </div>
        <span style="font-size:0.9375rem;font-weight:600;color:#374151">reCAPTCHA is active on all public forms</span>
    </label>
</div>

<div class="settings-card">
    <h2 class="card-title">reCAPTCHA Version</h2>
    <p class="card-desc" style="margin-bottom:16px">Choose which version of reCAPTCHA to use. v2 Checkbox is the easiest to set up. v3 runs invisibly in the background and scores user behaviour.</p>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px">
        @foreach([
            ['v'=>'v2-checkbox','title'=>'v2 — Checkbox','desc'=>"Classic 'I'm not a robot' tick box. Simple and reliable."],
            ['v'=>'v2-invisible','title'=>'v2 — Invisible','desc'=>'No user interaction. Challenge shown only if suspicious.'],
            ['v'=>'v3','title'=>'v3 — Score','desc'=>'Fully invisible. Returns a score (0–1). You set the threshold.'],
        ] as $ver)
        <label style="display:block;padding:14px;border-radius:10px;border:2px solid {{ $rcVersion===$ver['v']?'#132d5e':'#e5e7eb' }};background:{{ $rcVersion===$ver['v']?'#eff6ff':'#fff' }};cursor:pointer;transition:all 0.15s">
            <input type="radio" name="recaptcha_version" value="{{ $ver['v'] }}" {{ $rcVersion===$ver['v']?'checked':'' }} style="display:none">
            <p style="font-size:0.875rem;font-weight:700;color:#111827;margin:0 0 4px">{{ $ver['title'] }}</p>
            <p style="font-size:0.8125rem;color:#6b7280;margin:0">{{ $ver['desc'] }}</p>
        </label>
        @endforeach
    </div>

    {{-- Threshold slider --}}
    <div style="padding:16px;background:#f9fafb;border-radius:8px;border:1.5px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <label class="field-label" style="margin:0">Score Threshold: <strong id="threshold-val">{{ $rcThreshold }}</strong></label>
        </div>
        <input type="range" name="recaptcha_threshold" id="threshold-slider" min="0" max="1" step="0.1"
               value="{{ $rcThreshold }}"
               oninput="document.getElementById('threshold-val').textContent=this.value"
               style="width:100%;accent-color:#132d5e">
        <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#9ca3af;margin-top:5px">
            <span>0.0 — allow all</span>
            <span>0.5 — recommended</span>
            <span>1.0 — block all</span>
        </div>
        <p class="field-hint" style="margin-top:8px">Requests with a score below this threshold will be rejected. Google recommends 0.5 as a starting point.</p>
    </div>
</div>

<div class="settings-card">
    <h2 class="card-title">API Keys</h2>
    <p class="card-desc" style="margin-bottom:16px">Get your keys from the <a href="https://www.google.com/recaptcha/admin/create" target="_blank" class="link">Google reCAPTCHA Admin Console</a>. Create a new site and select the version that matches your choice above.</p>
    <div class="form-field">
        <label class="field-label">Site Key <span style="font-weight:400;color:#9ca3af">(public — used in the browser)</span></label>
        <input type="text" name="recaptcha_site_key" value="{{ $rcSiteKey }}"
               class="field-input" style="font-family:monospace">
        <p class="field-hint">Safe to expose — loaded by the browser on public form pages.</p>
    </div>
    <div class="form-field">
        <label class="field-label">Secret Key <span style="font-weight:400;color:#9ca3af">(private — server-side only)</span></label>
        <div class="secret-wrap">
            <input type="password" id="rc-sk" name="recaptcha_secret_key" value="{{ $rcSecretKey }}"
                   class="field-input" style="font-family:monospace;padding-right:42px">
            <button type="button" onclick="toggleSecret('rc-sk',this)" class="eye-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        </div>
        <p class="field-hint">Never exposed to the browser. Used by the server to verify tokens with Google.</p>
        @if($rcSecretKey)
        <div class="info-green" style="margin-top:10px">✓ Keys already saved. Secret key is masked for security.</div>
        @endif
    </div>
</div>

<div class="settings-card" style="background:#f9fafb">
    <p class="card-title">How to get your reCAPTCHA keys:</p>
    <ol style="font-size:0.9rem;color:#374151;padding-left:18px;margin:8px 0 0;line-height:2">
        <li>Go to <a href="https://google.com/recaptcha/admin/create" target="_blank" class="link">google.com/recaptcha/admin/create</a></li>
        <li>Sign in with your Google account</li>
        <li>Enter a label (e.g. "Driver Assessments Ireland")</li>
        <li>Select the reCAPTCHA type that matches your version choice above</li>
        <li>Add your domain (e.g. <code class="code-inline">dai.ie</code>)</li>
        <li>Click <strong>Submit</strong> — you'll see your Site Key and Secret Key</li>
        <li>Paste them into the fields above and click <strong>Save Settings</strong></li>
    </ol>
</div>

{{-- ─────────────────────────────────────────────── --}}
{{-- CUSTOM CODE                                     --}}
{{-- ─────────────────────────────────────────────── --}}
@elseif($tab === 'code')
<div class="settings-card">
    <h2 class="card-title">Custom Head Code</h2>
    <p class="card-desc" style="margin-bottom:16px">Inject custom HTML (scripts, meta tags, etc.) into the <code class="code-inline">&lt;head&gt;</code> of every public page. Runs before the closing <code class="code-inline">&lt;/head&gt;</code> tag.</p>
    <textarea name="custom_head_code" rows="14"
              placeholder="<!-- Paste custom scripts, meta tags or analytics snippets here -->"
              style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:12px 14px;font-size:0.8125rem;font-family:monospace;resize:vertical;line-height:1.6">{{ $s('custom_head_code') }}</textarea>
    <div class="info-amber" style="margin-top:12px">
        ⚠ <strong>Be careful:</strong> Invalid HTML or JavaScript here can break the public website. Test changes in a staging environment first.
    </div>
</div>
@endif

{{-- Spacer so content isn't hidden by fixed button --}}
<div style="height:72px"></div>

</form>

{{-- Fixed Save Settings button (form= attribute links it to the form by id) --}}
<div style="position:fixed;bottom:24px;right:28px;z-index:50">
    <button type="submit" form="settings-form"
            style="padding:12px 28px;background:#132d5e;color:#fff;border:none;border-radius:10px;font-size:0.9375rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(19,45,94,0.4);display:flex;align-items:center;gap:8px">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
        Save Settings
    </button>
</div>

<style>
/* ── Card ── */
.settings-card { background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:24px 28px;margin-bottom:20px }
.card-title { font-family:'Manrope',sans-serif;font-size:1rem;font-weight:700;color:#111827;margin:0 0 5px }
.card-desc { font-size:0.875rem;color:#6b7280;margin:0 }

/* ── Form fields ── */
.form-field { margin-bottom:18px }
.field-label { display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:7px }
.field-input { width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;font-family:'Inter',sans-serif;box-sizing:border-box }
.field-hint { margin:5px 0 0;font-size:0.8125rem;color:#9ca3af }

/* ── Secret input wrapper ── */
.secret-wrap { position:relative }
.eye-btn { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;display:flex;align-items:center }
.eye-btn:hover { color:#374151 }

/* ── Mode buttons ── */
.mode-btn { flex:1;display:flex;align-items:center;justify-content:center;gap:7px;padding:12px;border-radius:10px;border:2px solid #e5e7eb;background:#fff;cursor:pointer;font-size:0.875rem;font-weight:600;color:#6b7280;transition:all 0.15s }
.mode-btn-amber { border-color:#f59e0b;background:#fffbeb;color:#92400e }
.mode-btn-green { border-color:#22c55e;background:#f0fdf4;color:#15803d }

/* ── Badges ── */
.badge-green    { padding:5px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;background:#dcfce7;color:#15803d;border:1px solid #bbf7d0 }
.badge-amber    { padding:5px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;background:#fef3c7;color:#92400e;border:1px solid #fde68a }
.badge-green-sm { padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:#dcfce7;color:#15803d;border:1px solid #bbf7d0 }
.badge-amber-sm { padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:#fef3c7;color:#92400e;border:1px solid #fde68a }
.badge-orange-sm { padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:#f59e0b;color:#fff }
.badge-green-solid-sm { padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:#22c55e;color:#fff }
.badge-grey-sm  { padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb }

/* ── Info banners ── */
.info-blue   { background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px 16px;font-size:0.875rem;color:#1e40af }
.info-amber  { background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px 16px;font-size:0.875rem;color:#92400e }
.info-green  { background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;font-size:0.875rem;color:#15803d }

/* ── Links ── */
.link { color:#132d5e;font-weight:600;text-decoration:underline }
.link:hover { opacity:0.75 }
.code-inline { font-family:monospace;background:#f3f4f6;padding:1px 5px;border-radius:4px;font-size:0.85em }

/* ── Button outline sm ── */
.btn-outline-sm { display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border:1.5px solid #d1d5db;border-radius:7px;background:#fff;font-size:0.8125rem;font-weight:600;color:#374151;cursor:pointer;white-space:nowrap }
.btn-outline-sm:hover { background:#f9fafb }

/* ── Menu rows ── */
.menu-row { display:flex;align-items:center;gap:8px;padding:8px 10px;border:1.5px solid #e5e7eb;border-radius:8px;background:#fafafa }
.menu-input { flex:1;border:1.5px solid #d1d5db;border-radius:6px;padding:7px 10px;font-size:0.875rem;font-family:'Inter',sans-serif }
.menu-input.mono { font-family:monospace }
.menu-del { width:28px;height:28px;border:none;background:none;cursor:pointer;color:#ef4444;font-size:1.2rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:4px }
.menu-del:hover { background:#fef2f2 }
</style>

@endsection

@push('scripts')
<script>
/* Show/hide secret inputs */
function toggleSecret(id, btn) {
    const inp = document.getElementById(id);
    if (!inp) return;
    const showing = inp.type === 'text';
    inp.type = showing ? 'password' : 'text';
    btn.innerHTML = showing
        ? '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>'
        : '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';
}

/* reCAPTCHA toggle animation */
const rcChk = document.getElementById('rc-chk');
if (rcChk) {
    rcChk.addEventListener('change', function() {
        const track = document.getElementById('rc-track');
        const thumb = document.getElementById('rc-thumb');
        if (this.checked) {
            track.style.background = '#132d5e';
            thumb.style.left = '23px';
        } else {
            track.style.background = '#d1d5db';
            thumb.style.left = '3px';
        }
    });
}

/* Menu row add */
function addMenuRow(key) {
    const container = document.getElementById('menu-' + key);
    if (!container) return;
    const row = document.createElement('div');
    row.className = 'menu-row';
    row.innerHTML = `
        <svg style="width:14px;height:14px;color:#9ca3af;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
        <input type="text" name="${key}_label[]" placeholder="Label" class="menu-input">
        <input type="text" name="${key}_href[]" placeholder="/url" class="menu-input mono">
        <button type="button" onclick="this.closest('.menu-row').remove()" class="menu-del">×</button>`;
    container.appendChild(row);
}

/* Send Test Email */
async function sendTestEmail() {
    const addr = document.getElementById('test-email-addr')?.value?.trim();
    const result = document.getElementById('test-email-result');
    if (!addr) { result.textContent = 'Please enter an email address.'; result.style.color='#dc2626'; return; }
    result.textContent = 'Sending…';
    result.style.color = '#6b7280';
    try {
        const res = await fetch('{{ route("admin.settings.test-email") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ to: addr }),
        });
        const data = await res.json();
        if (data.success) {
            result.textContent = '✓ Test email sent to ' + addr + '. Check the inbox and Email Logs.';
            result.style.color = '#15803d';
        } else {
            result.textContent = '✗ ' + (data.error || 'Failed to send.');
            result.style.color = '#dc2626';
        }
    } catch(e) {
        result.textContent = '✗ Request failed — check SMTP settings are saved.';
        result.style.color = '#dc2626';
    }
}
</script>
@endpush
