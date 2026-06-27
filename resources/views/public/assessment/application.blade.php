@extends('layouts.app')
@section('title', 'Assessment Application — Driver Assessments Ireland')

@section('content')
@php $step = $step ?? 1; @endphp

{{-- Header --}}
<div style="background:hsl(215 81% 14%)">
    <div class="max-w-5xl mx-auto px-6 md:px-8 py-8">
        <p class="text-yellow-400 text-xs font-bold tracking-widest uppercase mb-1">Assessment Application</p>
        <h1 class="text-2xl font-extrabold text-white" style="font-family:Manrope,sans-serif">Driver Assessments Ireland</h1>
        <p class="text-white/60 text-sm mt-1">Reference: <span class="font-mono">{{ substr($application->token, 0, 8) }}...</span></p>
    </div>
</div>

{{-- Progress bar --}}
<div class="bg-white border-b shadow-sm sticky top-0 z-10">
    <div class="max-w-5xl mx-auto px-6 md:px-8 py-3 overflow-x-auto">
        <div class="flex items-center min-w-max gap-0">
            @php $steps = ['Payment','Personal','Vehicle','Medical','Review','Done']; @endphp
            @foreach($steps as $i => $label)
            @php $n = $i + 1; @endphp
            <div class="flex items-center">
                @if($i > 0)<div class="w-10 md:w-14 h-0.5 {{ $step > $i ? 'bg-blue-900' : 'bg-gray-200' }}"></div>@endif
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                        {{ $step === $n ? 'text-white shadow' : ($step > $n ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400') }}"
                         style="{{ $step === $n ? 'background:hsl(215 81% 14%)' : '' }}">
                        @if($step > $n)<span class="text-sm">✓</span>@else{{ $n }}@endif
                    </div>
                    <span class="text-xs mt-1 font-medium hidden sm:block {{ $step === $n ? 'text-blue-900' : 'text-gray-400' }}">{{ $label }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@php
$ic = "w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10 rounded-xl outline-none transition-all text-gray-900";
$lc = "block text-sm font-semibold text-gray-700 mb-1.5";
@endphp

<div class="max-w-5xl mx-auto px-6 md:px-8 py-10">

{{-- ══════════════════════════════════════════════════════ --}}
{{--  STEP 1 – Payment Confirmed                           --}}
{{-- ══════════════════════════════════════════════════════ --}}
@if($step === 1)
<div class="bg-white rounded-2xl shadow-md p-8 md:p-12 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h2 class="text-3xl font-bold mb-2" style="color:hsl(215 81% 14%);font-family:Manrope,sans-serif">Payment Confirmed!</h2>
    <p class="text-xl text-gray-600 mb-2">Amount paid: <strong class="text-gray-900">€{{ number_format($application->amount_paid, 2) }}</strong></p>
    <p class="text-sm text-gray-400 mb-8">Order ID: <code class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $application->token }}</code></p>
    <div class="bg-gray-50 rounded-xl p-6 text-left space-y-3 mb-8 max-w-lg mx-auto">
        <p class="font-semibold text-gray-900">What happens next?</p>
        <p class="text-sm text-gray-600">Please complete your assessment application below. It takes about 5–10 minutes. You can save your progress at each step and return using your unique link.</p>
        <p class="text-sm text-gray-600">A confirmation email has been sent to <strong>{{ $application->email }}</strong>.</p>
    </div>
    <a href="{{ route('assessment.application', ['token' => $application->token, 'step' => 2]) }}"
       class="inline-flex items-center gap-2 text-white font-bold text-lg px-10 py-4 rounded-xl hover:brightness-95 transition-all"
       style="background:hsl(215 81% 14%)">
        Start Application
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
    </a>
</div>

{{-- ══════════════════════════════════════════════════════ --}}
{{--  STEP 2 – Personal Information                        --}}
{{-- ══════════════════════════════════════════════════════ --}}
@elseif($step === 2)
<div class="bg-white rounded-2xl shadow-md p-8 md:p-10">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0" style="background:hsl(215 81% 14%)">2</div>
        <div>
            <h2 class="text-2xl font-bold" style="color:hsl(215 81% 14%);font-family:Manrope,sans-serif">Personal Information</h2>
            <p class="text-gray-500 text-sm mt-0.5">We have a few questions we'd like you to answer:</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <ul class="text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('assessment.save-step', $application->token) }}" class="space-y-6">
        @csrf
        <input type="hidden" name="step" value="2">

        {{-- Title + Full Name --}}
        <div class="grid grid-cols-1 sm:grid-cols-[160px_1fr] gap-4">
            <div>
                <label class="{{ $lc }}">Title</label>
                <select name="title" class="{{ $ic }}">
                    <option value="">-- Select --</option>
                    @foreach(['Mr','Mrs','Ms','Dr'] as $t)
                    <option value="{{ $t }}" {{ old('title', $application->title) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $lc }}">What is your Name: <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" required placeholder="Your Full Name"
                       value="{{ old('full_name', trim(($application->first_name ?? '') . ' ' . ($application->last_name ?? ''))) }}"
                       class="{{ $ic }} @error('full_name') border-red-400 bg-red-50 @enderror">
                @error('full_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Phone + Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="{{ $lc }}">What is your Telephone Number: <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" required placeholder="Your Telephone Number"
                       value="{{ old('phone', $application->phone) }}"
                       class="{{ $ic }} @error('phone') border-red-400 bg-red-50 @enderror">
                @error('phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $lc }}">What is your email address: <span class="text-red-500">*</span></label>
                <input type="email" name="email" required placeholder="Your Email Address"
                       value="{{ old('email', $application->email) }}"
                       class="{{ $ic }} @error('email') border-red-400 bg-red-50 @enderror">
                @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- DOB + Eircode --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="{{ $lc }}">What is your Date of Birth: <span class="text-red-500">*</span></label>
                <input type="date" name="dob" required max="{{ date('Y-m-d') }}"
                       value="{{ old('dob', $application->dob?->format('Y-m-d')) }}"
                       class="{{ $ic }} @error('dob') border-red-400 bg-red-50 @enderror">
                @error('dob')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $lc }}">Eircode for your home:</label>
                <input type="text" name="eircode" placeholder="H91 XXXX" maxlength="10"
                       value="{{ old('eircode', $application->eircode) }}"
                       class="{{ $ic }}">
            </div>
        </div>

        {{-- Address --}}
        <div>
            <label class="{{ $lc }}">What is your address: <span class="text-red-500">*</span></label>
            <textarea name="address" rows="2" required placeholder="Your full address"
                      class="{{ $ic }} @error('address') border-red-400 bg-red-50 @enderror">{{ old('address', $application->address) }}</textarea>
            @error('address')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full text-white font-bold text-lg py-4 rounded-xl hover:brightness-95 transition-all flex items-center justify-center gap-2"
                style="background:hsl(215 81% 14%)">
            Save &amp; Continue
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </button>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════ --}}
{{--  STEP 3 – Vehicle Information                         --}}
{{-- ══════════════════════════════════════════════════════ --}}
@elseif($step === 3)
<div class="bg-white rounded-2xl shadow-md p-8 md:p-10">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0" style="background:hsl(215 81% 14%)">3</div>
        <div>
            <h2 class="text-2xl font-bold" style="color:hsl(215 81% 14%);font-family:Manrope,sans-serif">Vehicle Information</h2>
            <p class="text-gray-500 text-sm mt-0.5">Please provide your licence and vehicle details.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <ul class="text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('assessment.save-step', $application->token) }}" class="space-y-6">
        @csrf
        <input type="hidden" name="step" value="3">

        {{-- Licence Number + Expiry --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                {{-- ORIGINAL: DAI feedback 26-06-26 What is your driving license number --}}
                <label class="{{ $lc }}">What is your driving licence number: <span class="text-red-500">*</span></label>
                <input type="text" name="license_number" required placeholder="Your driving licence number"
                       value="{{ old('license_number', $application->license_number) }}"
                       class="{{ $ic }} @error('license_number') border-red-400 bg-red-50 @enderror">
                @error('license_number')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $lc }}">When does this Licence expire: <span class="text-red-500">*</span></label>
                <input type="date" name="license_expiry" required
                       value="{{ old('license_expiry', $application->license_expiry?->format('Y-m-d')) }}"
                       class="{{ $ic }} @error('license_expiry') border-red-400 bg-red-50 @enderror">
                @error('license_expiry')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Motor Tax + Insurance Expiry --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="{{ $lc }}">When does your Motor Tax expire:</label>
                <input type="date" name="motor_tax_expiry"
                       value="{{ old('motor_tax_expiry', $application->motor_tax_expiry?->format('Y-m-d')) }}"
                       class="{{ $ic }}">
            </div>
            <div>
                <label class="{{ $lc }}">When does your Vehicle Insurance expire:</label>
                <input type="date" name="vehicle_insurance_expiry"
                       value="{{ old('vehicle_insurance_expiry', $application->vehicle_insurance_expiry?->format('Y-m-d')) }}"
                       class="{{ $ic }}">
            </div>
        </div>

        {{-- Insurance Company + NCT Due --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="{{ $lc }}">Insurance Company Name:</label>
                <input type="text" name="insurance_company" placeholder="Insurance Company Name"
                       value="{{ old('insurance_company', $application->insurance_company) }}"
                       class="{{ $ic }}">
            </div>
            <div>
                <label class="{{ $lc }}">When is your next NCT due:</label>
                <input type="date" name="nct_due"
                       value="{{ old('nct_due', $application->nct_due?->format('Y-m-d')) }}"
                       class="{{ $ic }}">
            </div>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('assessment.application', ['token' => $application->token, 'step' => 2]) }}"
               class="flex-1 text-center border-2 font-bold text-lg py-4 rounded-xl hover:bg-gray-50 transition-all"
               style="border-color:hsl(215 81% 14%);color:hsl(215 81% 14%)">← PREV</a>
            <button type="submit"
                    class="flex-1 text-white font-bold text-lg py-4 rounded-xl hover:brightness-95 transition-all"
                    style="background:hsl(215 81% 14%)">NEXT &amp; SAVE →</button>
        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════ --}}
{{--  STEP 4 – Medical & Contact                           --}}
{{-- ══════════════════════════════════════════════════════ --}}
@elseif($step === 4)
<div class="bg-white rounded-2xl shadow-md p-8 md:p-10">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0" style="background:hsl(215 81% 14%)">4</div>
        <div>
            <h2 class="text-2xl font-bold" style="color:hsl(215 81% 14%);font-family:Manrope,sans-serif">Medical &amp; Contact Details</h2>
            <p class="text-gray-500 text-sm mt-0.5">Please complete your medical and contact information below.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <ul class="text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('assessment.save-step', $application->token) }}" id="step4form" class="space-y-6">
        @csrf
        <input type="hidden" name="step" value="4">
        <input type="hidden" name="signature_data" id="signature_data" value="{{ old('signature_data', $application->signature_data) }}">

        {{-- Referral Reason + GP --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="{{ $lc }}">Reason for your Referral: <span class="text-red-500">*</span></label>
                <textarea name="referral_reason" rows="5" required
                          placeholder="Describe the reason for referral"
                          class="{{ $ic }} @error('referral_reason') border-red-400 bg-red-50 @enderror">{{ old('referral_reason', $application->referral_reason) }}</textarea>
                @error('referral_reason')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-4">
                <div>
                    <label class="{{ $lc }}">Your GP's Name and Address:</label>
                    <input type="text" name="gp_name_address" placeholder="Your GP's Name and Address"
                           value="{{ old('gp_name_address', $application->gp_name_address) }}"
                           class="{{ $ic }}">
                </div>
                <div>
                    <label class="{{ $lc }}">Your Hospital Consultant's/Contact's Name and Address:</label>
                    <input type="text" name="consultant_name_address"
                           placeholder="Your Hospital Consultant's/Contact's Name and Address"
                           value="{{ old('consultant_name_address', $application->consultant_name_address) }}"
                           class="{{ $ic }}">
                </div>
            </div>
        </div>

        {{-- Alternative Contact --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="{{ $lc }}">Alternative Contact Person Name:</label>
                <input type="text" name="alt_contact_name" placeholder="Contact person's name"
                       value="{{ old('alt_contact_name', $application->alt_contact_name) }}"
                       class="{{ $ic }}">
            </div>
            <div>
                <label class="{{ $lc }}">Alternative Contact Person Phone Number:</label>
                <input type="tel" name="alt_contact_phone" placeholder="Contact person's phone number"
                       value="{{ old('alt_contact_phone', $application->alt_contact_phone) }}"
                       class="{{ $ic }}">
            </div>
        </div>

        {{-- GDPR consent text --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-600">
            By signing below, you consent to giving us permission to process your personal data specifically for the purposes identified in accordance with GDPR.
        </div>

        {{-- Signature + Date --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="{{ $lc }}">Signature (please use your finger or mouse to sign): <span class="text-red-500">*</span></label>
                <div class="relative border-2 rounded-xl bg-white overflow-hidden @error('signature_data') border-red-400 @else border-gray-200 @enderror" style="touch-action:none">
                    <canvas id="sig-canvas" width="500" height="160"
                            class="w-full cursor-crosshair block" style="touch-action:none"></canvas>
                    <button type="button" id="sig-clear"
                            class="absolute top-2 right-2 text-xs text-gray-400 hover:text-red-500 bg-white/80 px-2 py-1 rounded transition-colors">
                        Clear
                    </button>
                </div>
                <p id="sig-error" class="text-red-600 text-xs mt-1 hidden">Please provide your signature before continuing.</p>
                @error('signature_data')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $lc }}">Date: <span class="text-red-500">*</span></label>
                <input type="date" name="signature_date" required
                       value="{{ old('signature_date', $application->signature_date?->format('Y-m-d') ?? date('Y-m-d')) }}"
                       class="{{ $ic }} @error('signature_date') border-red-400 bg-red-50 @enderror">
                @error('signature_date')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Terms --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 space-y-2 text-sm text-amber-900">
            <p>1. Please note a cancellation fee of €55.00 is payable if a driving assessment is cancelled with less than 48 hours notice.</p>
            <p>2. It is your responsibility to ensure all details, including: Driver's Licence, Motor Tax, NCT, and Insurance are all valid on the day of your assessment.</p>
            <p>3. A full cancellation fee will apply if the assessment cannot proceed due to invalid documents.</p>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('assessment.application', ['token' => $application->token, 'step' => 3]) }}"
               class="flex-1 text-center border-2 font-bold text-lg py-4 rounded-xl hover:bg-gray-50 transition-all"
               style="border-color:hsl(215 81% 14%);color:hsl(215 81% 14%)">← PREV</a>
            <button type="submit"
                    class="flex-1 text-white font-bold text-lg py-4 rounded-xl hover:brightness-95 transition-all"
                    style="background:hsl(215 81% 14%)">REVIEW &amp; SUBMIT →</button>
        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════ --}}
{{--  STEP 5 – Review & Submit                             --}}
{{-- ══════════════════════════════════════════════════════ --}}
@elseif($step === 5)
<div class="bg-white rounded-2xl shadow-md p-8 md:p-10">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0" style="background:hsl(215 81% 14%)">5</div>
        <h2 class="text-2xl font-bold" style="color:hsl(215 81% 14%);font-family:Manrope,sans-serif">Review &amp; Submit</h2>
    </div>
    <p class="text-gray-500 mb-8">Please review your details below before submitting. Use the <strong>Edit</strong> button to go back and make changes.</p>

    <div class="space-y-6">

        {{-- Personal --}}
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg" style="color:hsl(215 81% 14%)">Personal Information</h3>
                <a href="{{ route('assessment.application', ['token' => $application->token, 'step' => 2]) }}"
                   class="text-xs font-semibold flex items-center gap-1 hover:underline" style="color:hsl(215 81% 14%)">✎ Edit</a>
            </div>
            <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <dt class="text-gray-500">Name</dt>
                <dd class="font-medium text-gray-900">{{ $application->title ? $application->title . ' ' : '' }}{{ $application->first_name }} {{ $application->last_name }}</dd>
                <dt class="text-gray-500">Email</dt>
                <dd class="font-medium text-gray-900">{{ $application->email }}</dd>
                <dt class="text-gray-500">Phone</dt>
                <dd class="font-medium text-gray-900">{{ $application->phone }}</dd>
                <dt class="text-gray-500">Date of Birth</dt>
                <dd class="font-medium text-gray-900">{{ $application->dob?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Address</dt>
                <dd class="font-medium text-gray-900">{{ $application->address }}{{ $application->eircode ? ', ' . $application->eircode : '' }}</dd>
            </dl>
        </div>

        {{-- Vehicle --}}
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg" style="color:hsl(215 81% 14%)">Vehicle Information</h3>
                <a href="{{ route('assessment.application', ['token' => $application->token, 'step' => 3]) }}"
                   class="text-xs font-semibold flex items-center gap-1 hover:underline" style="color:hsl(215 81% 14%)">✎ Edit</a>
            </div>
            <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <dt class="text-gray-500">Licence Number</dt>
                <dd class="font-medium text-gray-900">{{ $application->license_number ?? '—' }}</dd>
                <dt class="text-gray-500">Licence Expiry</dt>
                <dd class="font-medium text-gray-900">{{ $application->license_expiry?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Motor Tax Expiry</dt>
                <dd class="font-medium text-gray-900">{{ $application->motor_tax_expiry?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Insurance Expiry</dt>
                <dd class="font-medium text-gray-900">{{ $application->vehicle_insurance_expiry?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="text-gray-500">Insurance Company</dt>
                <dd class="font-medium text-gray-900">{{ $application->insurance_company ?? '—' }}</dd>
                <dt class="text-gray-500">Next NCT Due</dt>
                <dd class="font-medium text-gray-900">{{ $application->nct_due?->format('d/m/Y') ?? '—' }}</dd>
            </dl>
        </div>

        {{-- Medical & Contact --}}
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg" style="color:hsl(215 81% 14%)">Medical &amp; Contact</h3>
                <a href="{{ route('assessment.application', ['token' => $application->token, 'step' => 4]) }}"
                   class="text-xs font-semibold flex items-center gap-1 hover:underline" style="color:hsl(215 81% 14%)">✎ Edit</a>
            </div>
            <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <dt class="text-gray-500">Referral Reason</dt>
                <dd class="font-medium text-gray-900 col-span-1">{{ $application->referral_reason ?? '—' }}</dd>
                <dt class="text-gray-500">GP</dt>
                <dd class="font-medium text-gray-900">{{ $application->gp_name_address ?? '—' }}</dd>
                <dt class="text-gray-500">Consultant</dt>
                <dd class="font-medium text-gray-900">{{ $application->consultant_name_address ?? '—' }}</dd>
                <dt class="text-gray-500">Alternative Contact</dt>
                <dd class="font-medium text-gray-900">{{ $application->alt_contact_name ?? '—' }}{{ $application->alt_contact_phone ? ' — ' . $application->alt_contact_phone : '' }}</dd>
            </dl>
        </div>

        {{-- Signature --}}
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg" style="color:hsl(215 81% 14%)">Declaration &amp; Signature</h3>
                <a href="{{ route('assessment.application', ['token' => $application->token, 'step' => 4]) }}"
                   class="text-xs font-semibold flex items-center gap-1 hover:underline" style="color:hsl(215 81% 14%)">✎ Edit</a>
            </div>
            <div class="text-sm space-y-3">
                @if($application->signature_data)
                <div>
                    <p class="font-medium text-gray-700 mb-1">Signature:</p>
                    <img src="{{ $application->signature_data }}" alt="Signature" class="border rounded-lg bg-white max-w-xs">
                </div>
                <p><span class="text-gray-500">Date signed:</span> <span class="font-medium text-gray-900">{{ $application->signature_date?->format('d/m/Y') ?? '—' }}</span></p>
                @else
                <p class="text-gray-400 italic">No signature captured — please go back to Step 4 to sign.</p>
                @endif
            </div>
        </div>

    </div>

    <div class="mt-8">
        @if(!$application->signature_data)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 text-red-700 text-sm">
            ⚠ You must complete your signature in Step 4 before you can submit.
        </div>
        @endif
        <form method="POST" action="{{ route('assessment.submit', $application->token) }}">
            @csrf
            <button type="submit"
                    {{ !$application->signature_data ? 'disabled' : '' }}
                    class="w-full text-white font-bold text-xl py-5 rounded-xl transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background:hsl(215 81% 14%)">
                Submit Application
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </button>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════ --}}
{{--  STEP 6 – Done                                        --}}
{{-- ══════════════════════════════════════════════════════ --}}
@elseif($step === 6)
<div class="bg-white rounded-2xl shadow-md p-8 md:p-12 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h2 class="text-3xl font-bold mb-3" style="color:hsl(215 81% 14%);font-family:Manrope,sans-serif">Application Submitted!</h2>
    <p class="text-lg text-gray-600 mb-2">Thank you, your application has been received.</p>
    <p class="text-sm text-gray-500 mb-8">Our team will review your details and be in touch shortly. If you have any questions, please email <a href="mailto:info@dai.ie" class="underline" style="color:hsl(215 81% 14%)">info@dai.ie</a>.</p>
    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2 text-white font-bold px-8 py-3 rounded-xl hover:brightness-95 transition-all"
       style="background:hsl(215 81% 14%)">
        Return to Home
    </a>
</div>
@endif

</div>{{-- end max-w-5xl --}}

@endsection

@push('scripts')
@if($step === 4)
<script>
(function () {
    var canvas = document.getElementById('sig-canvas');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var drawing = false;
    var hasDrawn = false;

    ctx.strokeStyle = '#1a1a2e';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    function pos(e) {
        var r = canvas.getBoundingClientRect();
        var sx = canvas.width / r.width;
        var sy = canvas.height / r.height;
        var src = e.touches ? e.touches[0] : e;
        return { x: (src.clientX - r.left) * sx, y: (src.clientY - r.top) * sy };
    }

    function start(e) {
        drawing = true;
        ctx.beginPath();
        var p = pos(e);
        ctx.moveTo(p.x, p.y);
        e.preventDefault();
    }
    function move(e) {
        if (!drawing) return;
        var p = pos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
        hasDrawn = true;
        document.getElementById('sig-error').classList.add('hidden');
        e.preventDefault();
    }
    function stop(e) { drawing = false; }

    canvas.addEventListener('mousedown',  start);
    canvas.addEventListener('mousemove',  move);
    canvas.addEventListener('mouseup',    stop);
    canvas.addEventListener('mouseleave', stop);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove',  move,  { passive: false });
    canvas.addEventListener('touchend',   stop);

    document.getElementById('sig-clear').addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasDrawn = false;
        document.getElementById('signature_data').value = '';
        document.getElementById('sig-error').classList.add('hidden');
    });

    // Restore existing signature if present
    var existing = document.getElementById('signature_data').value;
    if (existing && existing.startsWith('data:image')) {
        var img = new Image();
        img.onload = function () { ctx.drawImage(img, 0, 0); hasDrawn = true; };
        img.src = existing;
    }

    document.getElementById('step4form').addEventListener('submit', function (e) {
        var stored = document.getElementById('signature_data').value;
        if (!hasDrawn && !stored) {
            e.preventDefault();
            var el = document.getElementById('sig-error');
            el.classList.remove('hidden');
            canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        if (hasDrawn) {
            document.getElementById('signature_data').value = canvas.toDataURL('image/png');
        }
        var btn = document.querySelector('#step4form button[type="submit"]');
        if (btn) { btn.disabled = true; btn.style.opacity = '0.6'; }
    });
})();
</script>
@endif

{{-- Prevent double-submit on all step forms --}}
<script>
(function () {
    document.querySelectorAll('form[action*="step"]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                btn.style.opacity = '0.6';
                btn.style.cursor  = 'not-allowed';
            }
        });
    });
    // Final submit form (step 5)
    var finalForm = document.querySelector('form[action*="submit"]');
    if (finalForm) {
        finalForm.addEventListener('submit', function () {
            var btn = finalForm.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                btn.style.opacity = '0.6';
                btn.style.cursor  = 'not-allowed';
                btn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v8H4z"/></svg> Submitting…';
            }
        });
    }
})();
</script>
@endpush
