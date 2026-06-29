@extends('layouts.app')
@section('title', 'About DAI — Driver Assessments Ireland')
@section('meta_description', 'Driver Assessments Ireland is a specialist, independent service providing professional on-road driving assessments across all 32 counties, led by qualified occupational therapists.')

@section('content')

{{-- 1. HERO --}}
<section class="relative py-10 overflow-hidden"
    style="background-image:url('{{ asset('images/assessment-scene.png') }}');background-size:cover;background-position:center right">
    <div class="absolute inset-0"
        style="background:linear-gradient(105deg,hsl(215 81% 14% / 0.97) 45%,hsl(215 81% 23% / 0.70) 100%)"></div>
    <div class="relative container mx-auto px-6">
        <p class="text-xs font-bold uppercase tracking-widest mb-5" style="color:#ffcf00">About DAI</p>
        <h1 class="font-display text-4xl md:text-5xl xl:text-6xl font-extrabold text-white leading-[1.1] mb-5 max-w-2xl"
            style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">
            A Nationwide Service <span style="color:#ffcf00">Built Around You.</span>
        </h1>
        <p class="text-white/70 text-base leading-relaxed max-w-md">
            Driver Assessments Ireland provides independent, specialist driving assessments for individuals who self-refer or have been referred by healthcare professionals — operating out of our Galway office and serving the country, nationwide.
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-14 max-w-xl">
            @foreach([
                ['Nationwide',  'Service'],
                ['100%',        'Independent Assessments'],
                ['RSA',         'Compliant Protocol'],
                ['Qualified',   'Assessors'],
            ] as [$val,$label])
            <div class="min-w-0">
                <p class="font-display font-extrabold truncate" style="color:#ffcf00;letter-spacing:-0.02em;font-family:Manrope,sans-serif;font-size:clamp(1.25rem,2.5vw,1.35rem)">{{ $val }}</p>
                <p class="text-white/60 text-xs mt-1 leading-snug">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 2. PROFESSIONAL --}}
<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div class="rounded-2xl overflow-hidden shadow-xl" style="box-shadow:0px 24px 48px rgba(25,28,29,0.12)">
                <img src="{{ asset('images/About-us-image-1-1.png.png') }}" alt="DAI professional assessment"
                     class="w-full h-full object-cover" style="min-height:380px">
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#ffcf00">About DAI</p>
                <h2 class="font-display text-3xl font-extrabold mb-2" style="letter-spacing:-0.02em;font-family:Manrope,sans-serif;color:#1a1a2e">Professional</h2>
                <div class="w-10 h-1 rounded-full mb-6" style="background:#ffcf00"></div>
                <div class="space-y-4 text-gray-500 leading-relaxed text-base">
                    <p>Our professional integrity is at the heart of everything we do.</p>
                    <p>Our team is committed to delivering assessments of the highest quality, treating every individual with respect, dignity, and confidentiality in a safe and supportive environment.</p>
                    {{-- ORIGINAL: DAI feedback 26-06-26 Their clinical rigour ensures --}}
                    <p>Our assessors operate throughout the country, fully qualified and highly experienced, specialising in driver assessment.<br><br>Their experience and training ensures you receive an objective, evidence-based evaluation that is trusted by HCPs, the NDLS and families.</p>
                    <p>All assessors follow the RSA Medical Fitness to Drive Guidelines 2026, and our reports meet the standards required by the National Driver Licence Service and referring health professionals.</p>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    {{-- ORIGINAL: DAI feedback 26-06-26 --}}
                    {{-- @foreach(['RSA Compliant Protocol','Qualified Assessors','NDLS Accepted Reports'] as $badge) --}}
                    @foreach(['RSA Registered','Qualified Assessors','NDLS Accepted Reports'] as $badge)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold"
                          style="background:hsl(210 11% 96%);color:hsl(215 81% 23%)">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @if($badge === 'RSA Registered')
                            <a href="https://www.rsa.ie/docs/default-source/road-safety/slainte-agus-tiomaint/medical-fitness-to-drive-guidelines-2026.pdf?sfvrsn=a3301610_2" target="_blank" class="hover:underline">{{ $badge }}</a>
                        @else
                            {{ $badge }}
                        @endif
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. FORWARD THINKING --}}
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#ffcf00">About DAI</p>
                <h2 class="font-display text-3xl font-extrabold mb-2" style="letter-spacing:-0.02em;font-family:Manrope,sans-serif;color:#1a1a2e">Forward Thinking</h2>
                <div class="w-10 h-1 rounded-full mb-6" style="background:#ffcf00"></div>
                <div class="space-y-4 text-gray-500 leading-relaxed text-base mb-8">
                    {{-- ORIGINAL: DAI feedback 26-06-26 As clinicians... --}}
                    <p>As leaders in providing on-road driver assessments, we are committed to remaining agile and forward thinking in this rapidly changing landscape.<br><br>We are constantly improving our knowledge, acquiring best practice, and ensuring a high-quality service and experience for our clients.</p>
                    {{-- ORIGINAL: DAI feedback 26-06-26 As a company, we tend to deliver...clinical assessment training. --}}
                    {{-- ORIGINAL: As a company, we deliver objective, professional, and the best standard of assessment training. --}}
                    <p>As a company, we deliver objective, professional on-road assessments to the highest standard.</p>
                    {{-- ORIGINAL: DAI feedback 26-06-26 REMOVED: 'We can ensure our drivers...' and 'We have striven to develop training...' --}}
                </div>
                <ul class="space-y-3">
                    {{-- ORIGINAL: DAI feedback 26-06-26 'Advancing OT driver assessment' → 'Advancing driver assessment' --}}
                    @foreach([
                        'Continuous professional development for all assessors',
                        'Advancing driver assessment pathways nationally',
                        'Embracing technology to improve client outcomes',
                    ] as $item)
                    <li class="flex items-center gap-3 text-base text-gray-800">
                        <svg class="h-4 w-4 shrink-0" style="color:hsl(215 81% 23%)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-xl" style="box-shadow:0px 24px 48px rgba(25,28,29,0.10)">
                <img src="{{ asset('images/About-us-image3.png.webp') }}" alt="Forward thinking approach"
                     class="w-full h-full object-cover" style="min-height:380px">
            </div>
        </div>
    </div>
</section>

{{-- 4. INDUSTRY LEADERS --}}
<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div class="rounded-2xl overflow-hidden shadow-xl" style="box-shadow:0px 24px 48px rgba(25,28,29,0.12)">
                <img src="{{ asset('images/About-us-image4-1.png.png') }}" alt="Industry leaders in driver assessment"
                     class="w-full h-full object-cover" style="min-height:420px">
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#ffcf00">About DAI</p>
                <h2 class="font-display text-3xl font-extrabold mb-2" style="letter-spacing:-0.02em;font-family:Manrope,sans-serif;color:#1a1a2e">Industry Leaders</h2>
                <div class="w-10 h-1 rounded-full mb-6" style="background:#ffcf00"></div>
                <div class="space-y-4 text-gray-500 leading-relaxed text-base mb-8">
                    <p>Driver Assessments Ireland are leaders in the field of driving assessments in Ireland.</p>
                    <p>All our Assessors' individual compassion, empathy, and understanding of clients' individual circumstances is a key attribute brought to every assessment conducted by Driver Assessments Ireland.</p>
                    <p>We continually strive to be the best possible service for our clients, combining expertise and in-depth knowledge of the landscape within which we operate.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- ORIGINAL: DAI feedback 26-06-26 RSA Compliant→RSA Registered, Guidelines 2025→2026, removed 'clinical' from assessment standards --}}
                    @foreach([
                        ['Shield','Confidentiality','We maintain the confidentiality of any personal, privileged information to the highest standards.','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['Users','Fair Treatment','As a company, DAI strives to deliver objective, understanding, and fair treatment of all who may require a Driving Assessment.','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        ['Shield','RSA Registered','All assessments follow RSA Medical Fitness to Drive Guidelines 2026.','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['Lightbulb','Research-Led','Committed to evidence-based, best-practice assessment standards.','M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                    ] as [$icon,$title,$desc,$path])
                    <div class="rounded-xl p-5" style="border-left:3px solid hsl(215 81% 23%);background:hsl(210 11% 97%)">
                        <svg class="h-4 w-4 mb-2" style="color:hsl(215 81% 23%)" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/></svg>
                        <p class="font-bold text-sm mb-1" style="color:hsl(215 81% 23%)">@if($title === 'RSA Registered')<a href="https://www.rsa.ie/docs/default-source/road-safety/slainte-agus-tiomaint/medical-fitness-to-drive-guidelines-2026.pdf?sfvrsn=a3301610_2" target="_blank" class="hover:underline">{{ $title }}</a>@else{{ $title }}@endif</p>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 5. ENGAGED --}}
<section class="py-24 relative overflow-hidden"
    style="background:radial-gradient(ellipse at 30% 60%,hsl(215 81% 23%) 0%,hsl(215 81% 14%) 100%)">
    <div class="absolute inset-0 pointer-events-none opacity-10"
        style="background-image:repeating-linear-gradient(-45deg,rgba(255,255,255,0.06) 0px,rgba(255,255,255,0.06) 1px,transparent 1px,transparent 28px)"></div>
    <div class="relative container mx-auto px-6">
        <div class="text-center mb-12">
            <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#ffcf00">About DAI</p>
            <h2 class="font-display text-3xl font-extrabold text-white mb-4" style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">Engaged</h2>
            <p class="text-white/70 text-base leading-relaxed max-w-lg mx-auto">
                DAI is an active participant in the Irish healthcare and driving assessment community. We work collaboratively with GPs, occupational therapists, specialists, and statutory bodies to deliver coordinated outcomes for our clients.
            </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 max-w-4xl mx-auto">
            @foreach([
                ['Healthcare Collaboration','We work hand-in-hand with referring HCPs, sharing structured reports and maintaining open communication throughout the process.'],
                ['The NDLS & RSA','Our assessments are conducted in accordance with RSA standards and our reports support clients\' applications to the National Driver Licence Service.'],
                ['Client & Family Support','We are committed to keeping clients and their families informed, guiding them through every step of the assessment and decision-making process.'],
            ] as [$title,$desc])
            <div class="rounded-xl p-6 text-center" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-4" style="background:#ffcf00">
                    <svg class="h-5 w-5" style="color:#231b00" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-bold text-white text-base mb-2">{{ $title }}</p>
                <p class="text-white/60 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-10 max-w-lg mx-auto rounded-xl px-6 py-4 text-center text-xs"
             style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12)">
            <span class="text-white/50">Healthmail: </span>
            <span class="text-white/80">
                DAI is a Healthmail white site — you can email
                <a href="mailto:info@dai.ie" class="text-white font-semibold hover:underline">info@dai.ie</a>
                securely from any Healthmail address.
            </span>
        </div>
    </div>
</section>

{{-- 6. KEEPING YOU DRIVING --}}
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#ffcf00">About DAI</p>
                <h2 class="font-display text-3xl font-extrabold mb-2" style="letter-spacing:-0.02em;font-family:Manrope,sans-serif;color:#1a1a2e">Keeping You Driving</h2>
                <div class="w-10 h-1 rounded-full mb-6" style="background:#ffcf00"></div>
                <div class="space-y-4 text-gray-500 leading-relaxed text-base mb-8">
                    <p>A person diagnosed with a disability, illness or impairment may not be aware of the constraints that their medical condition could be placing on their driving ability or their safety while driving.</p>
                    <p>Changes, difficulties or other obstacles can prevent drivers from having the ability to drive safely.</p>
                    <p>Our driving assessors and report writing following RSA Medical Fitness to Drive guidelines has helped countless drivers to assess their ability to drive safely.</p>
                </div>
                <ul class="space-y-3">
                    @foreach([
                        'Preserving independence for as long as safely possible',
                        'Clear, honest recommendations that protect all road users',
                        'Supporting clients through the NDLS process',
                    ] as $item)
                    <li class="flex items-center gap-3 text-base text-gray-800">
                        <svg class="h-4 w-4 shrink-0" style="color:hsl(215 81% 23%)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-xl" style="box-shadow:0px 24px 48px rgba(25,28,29,0.10)">
                <img src="{{ asset('images/96-1.png.png') }}" alt="Keeping you driving safely"
                     class="w-full h-full object-cover" style="min-height:400px">
            </div>
        </div>
    </div>
</section>

{{-- 7. CONTACT CTA --}}
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="rounded-2xl px-8 py-12 flex flex-col sm:flex-row items-center justify-between gap-6"
             style="background:#ffcf00">
            <div>
                <p class="font-display text-2xl font-extrabold leading-tight" style="color:#231b00;letter-spacing:-0.02em;font-family:Manrope,sans-serif">Have questions about our service?</p>
                {{-- ORIGINAL: DAI feedback 26-06-26 Email our team for assistance. --}}
                <p class="text-base mt-1" style="color:#5a4600">Visit our <a href="{{ route('faq') }}" style="color:#231b00;font-weight:700;text-decoration:underline;text-underline-offset:2px">FAQ page</a> or email our team for assistance.</p>
            </div>
            <div class="flex flex-wrap gap-3 shrink-0">
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                   style="background:hsl(215 81% 23%)">
                    Contact Us
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('assessment.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all hover:opacity-80"
                   style="border:2px solid rgba(35,27,0,0.3);color:#231b00">
                    Book an Assessment
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
