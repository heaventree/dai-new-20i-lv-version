@extends('layouts.admin')
@section('title', 'Application Detail')
@section('page-title', 'Application: ' . (($application->title ? $application->title . ' ' : '') . ($application->first_name ?? '—') . ' ' . $application->last_name))
@section('content')
<div class="mb-4"><a href="{{ route('admin.applications.index') }}" class="text-navy hover:underline text-sm">← Back to Applications</a></div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">

        {{-- Personal --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-lg text-navy mb-4">Personal Information</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-500">Name</dt>
                <dd class="font-medium">{{ $application->title ? $application->title . ' ' : '' }}{{ $application->first_name }} {{ $application->last_name }}</dd>
                <dt class="text-gray-500">Email</dt><dd>{{ $application->email }}</dd>
                <dt class="text-gray-500">Phone</dt><dd>{{ $application->phone }}</dd>
                <dt class="text-gray-500">Date of Birth</dt><dd>{{ $application->dob?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Address</dt><dd>{{ $application->address }}{{ $application->eircode ? ', ' . $application->eircode : '' }}</dd>
            </dl>
        </div>

        {{-- Licence & Vehicle --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-lg text-navy mb-4">Licence &amp; Vehicle</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-500">Licence No</dt>
                <dd>{{ $application->license_number ?? '—' }}</dd>
                <dt class="text-gray-500">Licence Expiry</dt>
                <dd>{{ $application->license_expiry?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Motor Tax Expiry</dt>
                <dd>{{ $application->motor_tax_expiry?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Insurance Expiry</dt>
                <dd>{{ $application->vehicle_insurance_expiry?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Insurance Company</dt>
                <dd>{{ $application->insurance_company ?? '—' }}</dd>
                <dt class="text-gray-500">Next NCT Due</dt>
                <dd>{{ $application->nct_due?->format('d/m/Y') ?? '—' }}</dd>
            </dl>
        </div>

        {{-- Medical & Contact --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-lg text-navy mb-4">Medical &amp; Contact</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <dt class="text-gray-500">Referral Reason</dt>
                <dd class="col-span-1">{{ $application->referral_reason ?? '—' }}</dd>
                <dt class="text-gray-500">GP Name &amp; Address</dt>
                <dd>{{ $application->gp_name_address ?? '—' }}</dd>
                <dt class="text-gray-500">Consultant</dt>
                <dd>{{ $application->consultant_name_address ?? '—' }}</dd>
                @if($application->alt_contact_name || $application->alt_contact_phone)
                <dt class="text-gray-500">Alt. Contact</dt>
                <dd>{{ $application->alt_contact_name }}{{ $application->alt_contact_phone ? ' — ' . $application->alt_contact_phone : '' }}</dd>
                @endif
            </dl>
        </div>

        {{-- Signature --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-lg text-navy mb-4">Declaration &amp; Signature</h2>
            @if($application->signature_data)
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Signature:</p>
                    <img src="{{ $application->signature_data }}" alt="Applicant signature"
                         class="border rounded-lg bg-white max-w-xs">
                </div>
                <div class="flex gap-6 text-sm">
                    <span class="text-gray-500">Date signed:</span>
                    <span class="font-medium">{{ $application->signature_date?->format('d/m/Y') ?? '—' }}</span>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-400 italic">No signature captured.</p>
            @endif
        </div>

    </div>
    <div class="space-y-5">

        {{-- Status --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-lg text-navy mb-4">Status</h2>
            <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="space-y-3">
                @csrf
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach(['pending','submitted','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $application->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-navy text-white px-4 py-2 rounded-lg text-sm font-semibold">Update Status</button>
            </form>
        </div>

        {{-- Payment --}}
        <div class="bg-white rounded-xl shadow p-6 text-sm space-y-2">
            <h2 class="font-bold text-navy mb-3">Payment</h2>
            <div class="flex justify-between"><span class="text-gray-500">Status</span><span class="font-medium">{{ ucfirst($application->payment_status) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Amount</span><span class="font-medium">€{{ number_format($application->amount_paid, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Sheets Sync</span><span>{{ $application->synced_to_sheets ? '✅ Yes' : '❌ No' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Submitted</span><span>{{ $application->submitted_at?->format('d/m/Y H:i') ?? '—' }}</span></div>
            @if($application->payment)
            <div class="pt-2 border-t mt-2">
                <a href="{{ route('admin.payments.index') }}" class="text-navy hover:underline font-semibold text-xs">View in Payments →</a>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
