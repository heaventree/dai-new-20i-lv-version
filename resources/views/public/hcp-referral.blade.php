@extends('layouts.app')
@section('title', 'HCP Referral Portal — Driver Assessments Ireland')
@section('meta_description', 'Secure, clinical-grade referral submission for driver medical assessments. Partnering with healthcare professionals for patient safety.')

@section('content')

{{-- HERO --}}
<section class="relative py-10 overflow-hidden"
    style="background-image:url('{{ asset('images/hcp-form.jpg') }}');background-size:cover;background-position:top right">
    <div class="absolute inset-0"
        style="background:linear-gradient(105deg,hsl(215 81% 14% / 0.97) 45%,hsl(215 81% 23% / 0.70) 100%)"></div>
    <div class="relative container mx-auto px-6">
        <h1 class="font-display text-4xl md:text-5xl xl:text-6xl font-extrabold text-white leading-[1.1] mb-5 max-w-xl"
            style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">
            HCP Referral <span style="color:#ffcf00">Portal</span>
        </h1>
        <p class="text-white/70 text-base max-w-md leading-relaxed">
            Secure submission for driver assessments and consultations. Partnering with you for patient safety.
        </p>
    </div>
</section>

{{-- MAIN --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-10 items-start">

            {{-- LEFT SIDEBAR --}}
            <aside class="space-y-6">
                <div>
                    <h2 class="font-display text-xl font-extrabold mb-2" style="letter-spacing:-0.02em;font-family:Manrope,sans-serif;color:#1a1a2e">Submission Guide</h2>
                    <p class="text-gray-500 text-base leading-relaxed">
                        Please ensure all mandatory fields (marked with an asterisk) are completed to avoid delays in patient assessment scheduling.
                    </p>
                </div>

                @foreach([
                    {{-- ORIGINAL: DAI feedback 26-06-26 'compliance with medical privacy standards' --}}
                    ['Secure Handling','Data is encrypted and stored securely in compliance with GDPR.','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['24-Hour Review','Referrals are typically processed by our specialist team within one business day.','M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as [$title,$desc,$path])
                <div class="flex gap-3 bg-white rounded-xl p-4" style="box-shadow:0px 4px 12px rgba(25,28,29,0.06)">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 mt-0.5" style="background:hsl(210 11% 96%)">
                        <svg class="h-4 w-4" style="color:hsl(215 81% 23%)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 mb-0.5">{{ $title }}</p>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach

                <p class="text-xs text-gray-400 leading-relaxed">
                    DAI is a Healthmail white site — you can also email
                    <a href="mailto:info@dai.ie" class="underline" style="color:hsl(215 81% 23%)">info@dai.ie</a>
                    securely from any Healthmail address.
                </p>
            </aside>

            {{-- RIGHT FORM --}}
            <div class="bg-white rounded-2xl p-8" style="box-shadow:0px 4px 12px rgba(25,28,29,0.06)">

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                    <p class="font-semibold mb-1">Please correct the following:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- ORIGINAL: DAI feedback 26-06-26 added enctype for file upload --}}
                <form method="POST" action="{{ route('hcp-referral.submit') }}" enctype="multipart/form-data" class="space-y-8" id="hcp-referral-form">
                    @csrf
                    @include('partials.recaptcha', ['formId' => 'hcp-referral-form', 'action' => 'hcp_referral'])

                    {{-- Section 1 — HCP Details --}}
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                  style="background:hsl(215 81% 23%)">1</span>
                            <h3 class="font-display font-bold text-base" style="letter-spacing:-0.01em;font-family:Manrope,sans-serif;color:#1a1a2e">Health Care Professional Details</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Full Name <span class="text-red-500">*</span></label>
                                <input name="hcp_name" type="text" required value="{{ old('hcp_name') }}" placeholder="Dr. Jane Smith"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 placeholder-gray-400 @error('hcp_name') border-red-400 @else border-gray-200 @enderror">
                                @error('hcp_name')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Practice / Hospital Name <span class="text-red-500">*</span></label>
                                <input name="hcp_practice" type="text" required value="{{ old('hcp_practice') }}" placeholder="Central Clinic"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 placeholder-gray-400 @error('hcp_practice') border-red-400 @else border-gray-200 @enderror">
                                @error('hcp_practice')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Contact Email <span class="text-red-500">*</span></label>
                                <input name="hcp_email" type="email" required value="{{ old('hcp_email') }}" placeholder="jane.smith@hse.ie"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 placeholder-gray-400 @error('hcp_email') border-red-400 @else border-gray-200 @enderror">
                                @error('hcp_email')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Contact Phone <span class="text-red-500">*</span></label>
                                <input name="hcp_phone" type="tel" required value="{{ old('hcp_phone') }}" placeholder="e.g. 086 0422535"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 placeholder-gray-400 @error('hcp_phone') border-red-400 @else border-gray-200 @enderror">
                                @error('hcp_phone')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Alternative Contact Name</label>
                                <input name="alt_contact_name" type="text" value="{{ old('alt_contact_name') }}" placeholder="e.g. Practice Manager"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border border-gray-200 outline-none transition-all focus:ring-2 placeholder-gray-400">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Alternative Contact Ph or Email</label>
                                <input name="alt_contact_details" type="text" value="{{ old('alt_contact_details') }}" placeholder="Phone or email"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border border-gray-200 outline-none transition-all focus:ring-2 placeholder-gray-400">
                            </div>
                        </div>
                    </div>

                    <div style="border-top:1px solid rgba(25,28,29,0.08)"></div>

                    {{-- Section 2 — Patient Details --}}
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                  style="background:hsl(215 81% 23%)">2</span>
                            <h3 class="font-display font-bold text-base" style="letter-spacing:-0.01em;font-family:Manrope,sans-serif;color:#1a1a2e">Patient Details</h3>
                        </div>
                        <div class="space-y-5">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Patient Full Name <span class="text-red-500">*</span></label>
                                <input name="patient_full_name" type="text" required value="{{ old('patient_full_name') }}"
                                       placeholder="Legal name as it appears on licence"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 placeholder-gray-400 @error('patient_full_name') border-red-400 @else border-gray-200 @enderror">
                                @error('patient_full_name')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Date of Birth <span class="text-red-500">*</span></label>
                                <input name="patient_dob" type="date" required
                                       max="{{ date('Y-m-d') }}"
                                       value="{{ old('patient_dob') }}"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 @error('patient_dob') border-red-400 @else border-gray-200 @enderror">
                                @error('patient_dob')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Reason for Referral <span class="text-red-500">*</span></label>
                                <select name="reason_for_referral" required
                                        class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 @error('reason_for_referral') border-red-400 @else border-gray-200 @enderror">
                                    <option value="">Select an assessment type...</option>
                                    {{-- ORIGINAL: DAI feedback 26-06-26 old dropdown options --}}
                                    @foreach(['Cognitive Impairment','Dementia & Memory Loss','Stroke & ABI','Neurological Disorders','Cardiovascular Disorders','Psychiatric Conditions','Renal Conditions','Respiratory & Sleep Disorders','Epilepsy','Diabetes','Physical Disabilities','Learning Difficulties','Age-Related Assessment','Visual & Hearing Impairment','Medication Review','Other'] as $opt)
                                    <option value="{{ $opt }}" {{ old('reason_for_referral') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @error('reason_for_referral')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Clinical Notes &amp; Relevant History <span class="text-red-500">*</span></label>
                                <textarea name="clinical_notes" rows="5" required
                                          placeholder="Brief summary of condition and specific concerns regarding driving safety..."
                                          class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border outline-none transition-all focus:ring-2 resize-vertical placeholder-gray-400 @error('clinical_notes') border-red-400 @else border-gray-200 @enderror">{{ old('clinical_notes') }}</textarea>
                                @error('clinical_notes')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            {{-- Document upload — DAI feedback 26-06-26 --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-gray-800">Upload Supporting Documents <span style="font-weight:400;color:#9ca3af">(optional)</span></label>
                                <input name="document" type="file" accept=".pdf,.doc,.docx,.jpg,.png"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-900 text-sm border border-gray-200 outline-none transition-all focus:ring-2">
                                <p class="text-xs text-gray-400">PDF, DOC, DOCX, JPG or PNG. Max 5 MB.</p>
                                @error('document')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Consent + Submit --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 pt-2">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="consent" required
                                   class="mt-0.5 w-4 h-4 shrink-0 @error('consent') border-red-400 @enderror"
                                   style="accent-color:hsl(215 81% 23%)">
                            <span class="text-xs text-gray-500 leading-relaxed">
                                I confirm that I have obtained necessary patient consent for this referral and data processing
                            </span>
                        </label>
                        @error('consent')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror

                        <button type="submit"
                                class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-bold text-base transition-all hover:brightness-105 active:scale-95 shrink-0"
                                style="background:#ffcf00;color:#231b00">
                            Submit Referral
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

{{-- GDPR STRIP --}}
<div class="py-5" style="background:hsl(215 81% 14%)">
    <div class="container mx-auto px-6 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.10)">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                {{-- ORIGINAL: DAI feedback 26-06-26 --}}
                {{-- <p class="text-white font-bold text-sm">GDPR &amp; HIPAA Compliant</p> --}}
                <p class="text-white font-bold text-sm">GDPR &amp; Data Protection Act 2018</p>
                {{-- ORIGINAL: DAI feedback 26-06-26 All transmissions are AES-256 encrypted --}}
                <p class="text-white/50 text-xs">Your data security is our highest priority. All data is encrypted and stored securely in compliance with GDPR.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var form = document.querySelector('form[action*="hcp-referral"]');
    if (!form) return;
    form.addEventListener('submit', function () {
        var btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.6';
            btn.style.cursor  = 'not-allowed';
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v8H4z"/></svg> Submitting…';
        }
    });
})();
</script>
@endpush
@endsection
