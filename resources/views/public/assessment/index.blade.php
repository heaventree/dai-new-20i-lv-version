@extends('layouts.app')
@section('title', 'Book a Driver Assessment — Driver Assessments Ireland')
@section('meta_description', 'Book a specialist driver assessment online. Secure Stripe payment, €235. Nationwide service across all 32 counties of Ireland.')

@section('content')

{{-- Hero --}}
<section class="py-16"
    style="background:linear-gradient(135deg,hsl(215 80% 18%) 0%,hsl(215 80% 30%) 100%)">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white mb-2"
            style="font-family:Manrope,sans-serif">
            <span style="color:#ffcf00">Book an Assessment</span>
        </h1>
        <p class="text-white/70">Enter your details and proceed to Stripe's secure payment page to pay the €{{ $fee }} assessment fee.</p>
    </div>
</section>

{{-- Main content --}}
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">

        {{-- LEFT — Payment form --}}
        <div>
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm flex items-start gap-2">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <div class="p-6 space-y-5">
                    <form method="POST" action="{{ route('assessment.checkout') }}" id="assessment-payment-form">
                        @csrf
                        @include('partials.recaptcha', ['formId' => 'assessment-payment-form', 'action' => 'payment'])

                        <div class="space-y-5">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Your Full Name *
                                </label>
                                <input id="name" name="name" type="text" required
                                    value="{{ old('name') }}"
                                    placeholder="Your Full Name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent @error('name') border-red-400 @enderror"
                                    style="@error('name') @else focus-ring-color:hsl(215 81% 14%); @enderror">
                                @error('name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Your Email Address *
                                </label>
                                <input id="email" name="email" type="email" required
                                    value="{{ old('email') }}"
                                    placeholder="your@email.com"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none @error('email') border-red-400 @enderror">
                                @error('email')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Security notice --}}
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-600 flex items-start gap-2">
                                <svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>You will be redirected to Stripe's secure payment page. We do not store your card details.</span>
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-lg text-base font-semibold transition-opacity hover:opacity-90"
                                style="background:hsl(215 81% 14%);color:#fff">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Proceed to Pay €{{ $fee }}
                            </button>

                            <p class="text-center text-xs text-gray-400">
                                You will be redirected to Stripe's secure payment page.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT — Info cards --}}
        <div class="space-y-6">

            {{-- Before you pay checklist --}}
            <div class="border rounded-xl p-6" style="border-color:hsl(215 81% 14% / 0.2);background:hsl(215 81% 14% / 0.05)">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" style="color:hsl(215 81% 14%)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Before going ahead with payment, ensure you have the following details
                </h3>
                <ol class="space-y-2 text-gray-800">
                    <li class="flex items-start gap-2">
                        <span class="font-semibold mt-0.5" style="color:hsl(215 81% 14%)">1.</span>
                        <span>Eircode</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-semibold mt-0.5" style="color:hsl(215 81% 14%)">2.</span>
                        {{-- ORIGINAL: DAI feedback 26-06-26 Driving License Details --}}
                        <span>Driving Licence Details</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-semibold mt-0.5" style="color:hsl(215 81% 14%)">3.</span>
                        <span>Motor Insurance, NCT, Motor Tax Details</span>
                    </li>
                </ol>
            </div>

            {{-- How it works --}}
            <div class="border border-gray-200 rounded-xl p-6 bg-white">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    How the booking process works
                </h3>
                <ol class="space-y-3 text-gray-500 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:hsl(215 81% 14%)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Enter your details and proceed to Stripe's secure payment page to pay the €{{ $fee }} assessment fee.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:hsl(215 81% 14%)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>After payment you will be asked to fill out your personal information.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:hsl(215 81% 14%)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>A member of our team will then contact you to arrange a suitable date and time.</span>
                    </li>
                </ol>
            </div>

            {{-- Contact note --}}
            <p class="text-center text-gray-500 text-sm">
                If you require assistance, please email our office on
                <a href="mailto:info@dai.ie"
                   class="font-medium" style="color:hsl(215 81% 14%)">
                    info@dai.ie
                </a>
            </p>

            {{-- ORIGINAL: DAI feedback 26-06-26 @if(!env('STRIPE_SECRET') || app()->environment('local','testing')) --}}
            @if(!\App\Models\Setting::get('stripe_secret_key_test') || app()->environment('local','testing'))
            {{-- Dev-only bypass --}}
            <div class="border border-dashed border-amber-300 rounded-xl p-4 bg-amber-50 text-center">
                <p class="text-xs font-bold text-amber-700 uppercase tracking-widest mb-2">Dev / Test Mode</p>
                <p class="text-xs text-amber-600 mb-3">Stripe is not configured. Use this to preview the post-payment application form.</p>
                <a href="{{ route('assessment.test-bypass') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90"
                   style="background:#b45309">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Preview Application Form (bypass payment)
                </a>
            </div>
            @endif
        </div>

    </div>
</div>

@push('scripts')
<script>
(function () {
    var form = document.querySelector('form[action*="arrange-assessment"]');
    if (!form) return;
    form.addEventListener('submit', function () {
        var btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.6';
            btn.style.cursor  = 'not-allowed';
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v8H4z"/></svg> Redirecting to payment…';
        }
    });
})();
</script>
@endpush
@endsection
