@extends('layouts.admin')
@section('title', 'Referral Detail')
@section('page-title', 'HCP Referral')
@section('content')
<div class="mb-4"><a href="{{ route('admin.hcp-referrals.index') }}" class="text-navy hover:underline text-sm">← Back</a></div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-bold text-lg text-navy mb-4">Referring Clinician</h2>
        <dl class="grid grid-cols-2 gap-3 text-sm">
            <dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $hcpReferral->hcp_name }}</dd>
            <dt class="text-gray-500">Practice</dt><dd>{{ $hcpReferral->hcp_practice }}</dd>
            <dt class="text-gray-500">Email</dt><dd>{{ $hcpReferral->hcp_email }}</dd>
            <dt class="text-gray-500">Phone</dt><dd>{{ $hcpReferral->hcp_phone }}</dd>
            @if($hcpReferral->alt_contact_name)
            <dt class="text-gray-500">Alt. Contact Name</dt><dd>{{ $hcpReferral->alt_contact_name }}</dd>
            @endif
            @if($hcpReferral->alt_contact_details)
            <dt class="text-gray-500">Alt. Contact Details</dt><dd>{{ $hcpReferral->alt_contact_details }}</dd>
            @endif
        </dl>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-bold text-lg text-navy mb-4">Patient</h2>
        <dl class="grid grid-cols-2 gap-3 text-sm">
            <dt class="text-gray-500">Full Name</dt><dd class="font-medium">{{ $hcpReferral->patient_full_name }}</dd>
            <dt class="text-gray-500">Date of Birth</dt><dd>{{ $hcpReferral->patient_dob?->format('d/m/Y') }}</dd>
            <dt class="text-gray-500">PPS No.</dt><dd>{{ $hcpReferral->patient_pps ?: '—' }}</dd>
            <dt class="text-gray-500">Consent Given</dt><dd>{{ $hcpReferral->consent ? '✅ Yes' : '❌ No' }}</dd>
        </dl>
    </div>
    <div class="bg-white rounded-xl shadow p-6 lg:col-span-2">
        <h2 class="font-bold text-lg text-navy mb-4">Clinical Information</h2>
        <dl class="space-y-4 text-sm">
            <div>
                <dt class="text-gray-500 font-medium mb-1">Reason for Referral</dt>
                <dd class="bg-gray-50 rounded p-3 whitespace-pre-wrap">{{ $hcpReferral->reason_for_referral }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 font-medium mb-1">Clinical Notes & Relevant History</dt>
                <dd class="bg-gray-50 rounded p-3 whitespace-pre-wrap">{{ $hcpReferral->clinical_notes }}</dd>
            </div>
            {{-- Uploaded document — DAI feedback 26-06-28 --}}
            @if($hcpReferral->document_path)
            <div>
                <dt class="text-gray-500 font-medium mb-1">Supporting Document</dt>
                <dd class="bg-gray-50 rounded p-3 flex items-center gap-3">
                    <svg class="w-5 h-5 text-navy shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <a href="{{ route('admin.hcp-referrals.document', $hcpReferral) }}" target="_blank" class="text-navy font-semibold hover:underline">
                        {{ $hcpReferral->document_name ?: basename($hcpReferral->document_path) }}
                    </a>
                </dd>
            </div>
            @endif
        </dl>
        <div class="mt-4 flex gap-4 text-xs text-gray-500">
            <span>Status: <strong>{{ ucfirst($hcpReferral->status) }}</strong></span>
            <span>Sheets Sync: <strong>{{ $hcpReferral->synced_to_sheets ? '✅ Yes' : '❌ No' }}</strong></span>
            <span>Submitted: <strong>{{ $hcpReferral->created_at->format('d/m/Y H:i') }}</strong></span>
        </div>
    </div>
</div>
@endsection
