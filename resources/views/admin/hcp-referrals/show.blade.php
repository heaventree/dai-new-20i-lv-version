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
        </dl>
        <div class="mt-4 flex gap-4 text-xs text-gray-500">
            <span>Status: <strong>{{ ucfirst($hcpReferral->status) }}</strong></span>
            <span>Sheets Sync: <strong>{{ $hcpReferral->synced_to_sheets ? '✅ Yes' : '❌ No' }}</strong></span>
            <span>Submitted: <strong>{{ $hcpReferral->created_at->format('d/m/Y H:i') }}</strong></span>
        </div>
    </div>
</div>
@endsection
