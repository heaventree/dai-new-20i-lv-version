@extends('layouts.admin')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<p style="color:#6b7280;margin-bottom:24px;font-size:0.9375rem">Site health checks and analytics status</p>

{{-- Tab bar --}}
@php $activeTab = request()->get('tab', 'links'); @endphp
<div style="display:flex;gap:4px;border-bottom:2px solid #e5e7eb;margin-bottom:28px">
    @foreach(['links' => 'Broken Link Checker', 'analytics' => 'Analytics Status'] as $slug => $label)
    <a href="{{ route('admin.reports.index', ['tab' => $slug]) }}"
       style="padding:9px 18px;font-size:0.875rem;font-weight:600;border-radius:6px 6px 0 0;text-decoration:none;
              {{ $activeTab === $slug
                  ? 'background:#fff;border:1.5px solid #e5e7eb;border-bottom:2px solid #fff;color:#111827;margin-bottom:-2px'
                  : 'color:#6b7280' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

@if($activeTab === 'links')
{{-- ── BROKEN LINK CHECKER ── --}}
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:28px 32px">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap">
        <div>
            <h2 style="font-family:'Manrope',sans-serif;font-size:1.1rem;font-weight:700;color:#111827;margin:0 0 4px">Broken Link Checker</h2>
            <p style="font-size:0.875rem;color:#6b7280;margin:0">Checks all core pages, service pages and team member profiles via HTTP HEAD requests.</p>
        </div>
        <button id="btn-check" onclick="checkLinks()"
                style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:#132d5e;color:#fff;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;flex-shrink:0">
            <svg id="btn-icon" style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span id="btn-label">Check All Links</span>
        </button>
    </div>

    <div id="results-area" style="display:none">
        <div id="summary-box" style="margin-bottom:16px"></div>
        <div id="filter-bar" style="display:none;gap:8px;margin-bottom:14px"></div>
        <div id="results-table"></div>
    </div>

    <div id="empty-state" style="text-align:center;padding:48px;color:#9ca3af;border:2px dashed #e5e7eb;border-radius:10px">
        <svg style="width:40px;height:40px;margin:0 auto 12px;opacity:0.3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
        </svg>
        <p>Click "Check All Links" to scan all pages</p>
    </div>
</div>

@else
{{-- ── ANALYTICS STATUS ── --}}
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:28px 32px">
    <h2 style="font-family:'Manrope',sans-serif;font-size:1.1rem;font-weight:700;color:#111827;margin:0 0 20px">Analytics & Tracking Status</h2>

    @php
        $checks = [
            ['label' => 'Google Analytics 4', 'value' => $ga4Id,  'hint' => 'Configure in Settings → Tracking & Analytics', 'prefix' => 'G-'],
            ['label' => 'Google Tag Manager',  'value' => $gtmId,  'hint' => 'Configure in Settings → Tracking & Analytics', 'prefix' => 'GTM-'],
        ];
    @endphp

    <div style="display:flex;flex-direction:column;gap:12px">
    @foreach($checks as $check)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border:1.5px solid {{ $check['value'] ? '#bbf7d0' : '#e5e7eb' }};border-radius:10px;background:{{ $check['value'] ? '#f0fdf4' : '#fafafa' }}">
        <div style="display:flex;align-items:center;gap:12px">
            @if($check['value'])
            <svg style="width:20px;height:20px;color:#16a34a;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            @else
            <svg style="width:20px;height:20px;color:#d97706;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            @endif
            <div>
                <p style="font-size:0.9375rem;font-weight:600;color:#111827;margin:0 0 2px">{{ $check['label'] }}</p>
                <p style="font-size:0.8125rem;color:{{ $check['value'] ? '#16a34a' : '#9ca3af' }};margin:0">
                    @if($check['value'])
                        ID: <code style="background:rgba(255,255,255,0.7);padding:1px 5px;border-radius:4px">{{ $check['value'] }}</code>
                    @else
                        Not configured — {{ $check['hint'] }}
                    @endif
                </p>
            </div>
        </div>
        <span style="font-size:0.8125rem;font-weight:700;padding:4px 10px;border-radius:20px;{{ $check['value'] ? 'background:#dcfce7;color:#15803d' : 'background:#fef3c7;color:#92400e' }}">
            {{ $check['value'] ? 'Connected' : 'Not Set' }}
        </span>
    </div>
    @endforeach
    </div>

    {{-- External links --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px">
        <a href="https://analytics.google.com" target="_blank" rel="noreferrer"
           style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border:1.5px solid #e5e7eb;border-radius:10px;background:#fff;text-decoration:none;color:#111827;font-size:0.9375rem;font-weight:600;transition:border-color 0.15s">
            <div style="display:flex;align-items:center;gap:10px">
                <svg style="width:18px;height:18px;color:#6b7280" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Open Google Analytics
            </div>
            <svg style="width:14px;height:14px;color:#9ca3af" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
        <a href="https://search.google.com/search-console" target="_blank" rel="noreferrer"
           style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border:1.5px solid #e5e7eb;border-radius:10px;background:#fff;text-decoration:none;color:#111827;font-size:0.9375rem;font-weight:600;transition:border-color 0.15s">
            <div style="display:flex;align-items:center;gap:10px">
                <svg style="width:18px;height:18px;color:#6b7280" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Google Search Console
            </div>
            <svg style="width:14px;height:14px;color:#9ca3af" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    <div style="margin-top:14px;padding:14px 18px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;font-size:0.875rem;color:#1e40af">
        GTM and GA4 Measurement IDs are configured in <a href="{{ route('admin.settings.index', ['tab' => 'tracking']) }}" style="font-weight:700;color:#1e40af">Settings → Tracking & Analytics</a>.
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
const PAGES = [
    { url: '/',                             label: 'Home' },
    { url: '/about',                        label: 'About' },
    { url: '/services',                     label: 'Services' },
    { url: '/team',                         label: 'Team' },
    { url: '/faq',                          label: 'FAQ' },
    { url: '/contact',                      label: 'Contact' },
    { url: '/hcp-referral-form',            label: 'HCP Referral Form' },
    { url: '/arrange-assessment',           label: 'Arrange Assessment' },
    { url: '/cookie-policy',                label: 'Cookie Policy' },
    { url: '/privacy-policy',               label: 'Privacy Policy' },
    { url: '/terms-of-use',                 label: 'Terms of Use' },
    { url: '/service/dementia',             label: 'Service: Dementia' },
    { url: '/service/stroke',               label: 'Service: Stroke' },
    { url: '/service/epilepsy',             label: 'Service: Epilepsy' },
    { url: '/service/diabetes-mellitus',    label: 'Service: Diabetes Mellitus' },
    { url: '/service/brain-injury',         label: 'Service: Brain Injury' },
    { url: '/service/neurological-disorders', label: 'Service: Neurological Disorders' },
    { url: '/service/cardiovascular-disorders', label: 'Service: Cardiovascular Disorders' },
    { url: '/service/psychiatric-disorders', label: 'Service: Psychiatric Disorders' },
    { url: '/service/respiratory-sleep-disorders', label: 'Service: Respiratory & Sleep Disorders' },
    { url: '/service/congenital-disorders', label: 'Service: Congenital Disorders' },
    { url: '/service/learning-difficulties', label: 'Service: Learning Difficulties' },
    { url: '/service/renal-disorders',      label: 'Service: Renal Disorders' },
    { url: '/service/auditory-visual-sensory-loss', label: 'Service: Auditory/Visual/Sensory Loss' },
    { url: '/service/your-health-and-driving', label: 'Service: Your Health & Driving' },
];

let filter = 'all';
let results = null;

async function checkLinks() {
    const btn = document.getElementById('btn-check');
    const btnLabel = document.getElementById('btn-label');
    const emptyState = document.getElementById('empty-state');
    const resultsArea = document.getElementById('results-area');

    btn.disabled = true;
    btnLabel.textContent = 'Checking…';
    emptyState.style.display = 'none';

    const base = window.location.origin;
    const checks = await Promise.all(PAGES.map(async ({ url, label }) => {
        try {
            const r = await fetch(base + url, { method: 'HEAD', signal: AbortSignal.timeout(8000) });
            return { url, label, status: r.status, ok: r.ok };
        } catch {
            return { url, label, status: 'error', ok: false };
        }
    }));

    results = checks;
    filter = 'all';
    btn.disabled = false;
    btnLabel.textContent = 'Re-check All';
    resultsArea.style.display = 'block';
    renderResults();
}

function renderResults() {
    if (!results) return;
    const broken = results.filter(r => !r.ok);
    const shown  = filter === 'broken' ? broken : results;

    // Summary
    const sb = document.getElementById('summary-box');
    if (broken.length === 0) {
        sb.innerHTML = `<div style="display:flex;align-items:center;gap:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px 18px;margin-bottom:4px">
            <svg style="width:22px;height:22px;color:#16a34a;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p style="font-weight:700;color:#15803d;margin:0">All links OK</p><p style="font-size:0.875rem;color:#16a34a;margin:0">All ${results.length} pages returned a successful response.</p></div>
        </div>`;
    } else {
        sb.innerHTML = `<div style="display:flex;align-items:center;gap:12px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:14px 18px;margin-bottom:4px">
            <svg style="width:22px;height:22px;color:#d97706;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <p style="font-weight:700;color:#92400e;margin:0">${broken.length} broken link${broken.length > 1 ? 's' : ''} found out of ${results.length} checked</p>
        </div>`;
    }

    // Filter bar
    const fb = document.getElementById('filter-bar');
    if (broken.length > 0) {
        fb.style.display = 'flex';
        fb.innerHTML = `
            <button onclick="setFilter('all')" style="padding:6px 14px;border-radius:6px;font-size:0.8125rem;font-weight:600;cursor:pointer;border:1.5px solid ${filter==='all'?'#132d5e':'#d1d5db'};background:${filter==='all'?'#132d5e':'#fff'};color:${filter==='all'?'#fff':'#374151'}">All (${results.length})</button>
            <button onclick="setFilter('broken')" style="padding:6px 14px;border-radius:6px;font-size:0.8125rem;font-weight:600;cursor:pointer;border:1.5px solid ${filter==='broken'?'#dc2626':'#d1d5db'};background:${filter==='broken'?'#dc2626':'#fff'};color:${filter==='broken'?'#fff':'#374151'}">Broken only (${broken.length})</button>`;
    } else {
        fb.style.display = 'none';
    }

    // Table
    const rows = shown.map(r => `<tr style="border-bottom:1px solid #f3f4f6${r.ok?'':';background:#fef2f2'}">
        <td style="padding:10px 14px;font-weight:500;color:#111827">${r.label}</td>
        <td style="padding:10px 14px;font-family:monospace;font-size:0.8125rem;color:#6b7280">${r.url}</td>
        <td style="padding:10px 14px;font-family:monospace;font-size:0.8125rem">${r.status}</td>
        <td style="padding:10px 14px">${r.ok
            ? '<span style="display:flex;align-items:center;gap:5px;color:#16a34a;font-size:0.875rem;font-weight:600">✓ OK</span>'
            : '<span style="display:flex;align-items:center;gap:5px;color:#dc2626;font-size:0.875rem;font-weight:600">✗ Error</span>'
        }</td>
    </tr>`).join('');

    document.getElementById('results-table').innerHTML = `<div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden">
        <table style="width:100%;border-collapse:collapse">
            <thead><tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">
                <th style="padding:10px 14px;text-align:left;font-size:0.8125rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280">Page</th>
                <th style="padding:10px 14px;text-align:left;font-size:0.8125rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280">URL</th>
                <th style="padding:10px 14px;text-align:left;font-size:0.8125rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280">Status</th>
                <th style="padding:10px 14px;text-align:left;font-size:0.8125rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280">Result</th>
            </tr></thead>
            <tbody>${rows}</tbody>
        </table>
    </div>`;
}

function setFilter(f) { filter = f; renderResults(); }
</script>
@endpush
