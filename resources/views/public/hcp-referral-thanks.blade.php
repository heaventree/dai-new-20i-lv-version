@extends('layouts.app')
@section('title', 'Referral Submitted — Driver Assessments Ireland')

@section('content')

<section class="py-32 bg-surface">
    <div class="max-w-2xl mx-auto px-6 md:px-8 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-green-600 text-5xl">check_circle</span>
        </div>
        <h1 class="font-headline text-4xl font-extrabold text-primary mb-4">Referral Submitted</h1>
        <p class="text-xl text-on-surface-variant mb-4 leading-relaxed">
            Thank you for your referral. Our specialist team will review the clinical details and be in touch within <strong class="text-on-surface">one business day</strong>.
        </p>
        <p class="text-on-surface-variant mb-10">A confirmation has been sent to your email address.</p>

        <div class="bg-surface-container-low rounded-2xl p-8 mb-10 text-left space-y-4">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-primary mt-0.5">schedule</span>
                <div>
                    <p class="font-semibold text-on-surface">What happens next?</p>
                    <p class="text-sm text-on-surface-variant mt-1">We will contact the patient directly to arrange their driver assessment appointment at a time that suits them.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-primary mt-0.5">mail</span>
                <div>
                    <p class="font-semibold text-on-surface">Report delivery</p>
                    <p class="text-sm text-on-surface-variant mt-1">A full written assessment report will be sent directly to you following the assessment, with recommendations on driving fitness.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-primary mt-0.5">phone_in_talk</span>
                <div>
                    <p class="font-semibold text-on-surface">Questions?</p>
                    <p class="text-sm text-on-surface-variant mt-1">
                        Call us on <a href="tel:+353860422535" class="text-primary font-semibold">+353 (0)86 042 2535</a> or
                        <a href="{{ route('contact') }}" class="text-primary font-semibold underline">send us a message</a>.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('hcp-referral') }}" class="bg-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-primary-container transition-all active:scale-95">
                Submit Another Referral
            </a>
            <a href="{{ route('home') }}" class="bg-surface-container text-on-surface px-8 py-4 rounded-xl font-bold hover:bg-surface-container-high transition-all active:scale-95">
                Return to Home
            </a>
        </div>
    </div>
</section>

@endsection
