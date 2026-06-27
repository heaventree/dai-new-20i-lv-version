@extends('layouts.app')
@section('title', 'Our Services — Driver Assessments Ireland')
@section('meta_description', 'Comprehensive driving assessments for dementia, stroke, neurological disorders, physical disabilities and more. Professional on-road assessments across all 32 counties of Ireland.')

@section('content')

@php
$featured = [
    ['slug' => 'dementia',    'title' => 'Dementia & Memory Loss',    'desc' => 'Comprehensive cognitive screening focusing on judgement, processing speed, and visual-spatial navigation.',         'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2'],
    ['slug' => 'stroke',      'title' => 'Stroke & ABI',              'desc' => 'Evaluation of physical motor control, visual field integrity, and executive function following brain injury.',           'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
    ['slug' => 'neurological-disorders', 'title' => 'Neurological Disorders', 'desc' => "Assessment for Parkinson's, MS, and other progressive conditions affecting coordination and reaction time.", 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
    ['slug' => 'congenital-disorders',   'title' => 'Physical Disabilities',  'desc' => 'Identifying necessary vehicle modifications and controls for drivers with congenital or acquired mobility limitations.', 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
    {{-- ORIGINAL: DAI feedback 26-06-26 --}}
    {{-- ['slug' => 'your-health-and-driving','title' => 'The Aging Driver',        'desc' => 'Supportive evaluations for seniors to extend safe driving years through refresher training and environmental awareness.', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'], --}}
    ['slug' => 'your-health-and-driving','title' => 'The Ageing Driver',        'desc' => 'Supportive evaluations for seniors to extend safe driving years through refresher training and environmental awareness.', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ['slug' => 'auditory-visual-sensory-loss', 'title' => 'Vision Impairment', 'desc' => 'Functional vision assessments beyond standard eye exams, testing contrast sensitivity and peripheral awareness.', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
];

$moreConditions = [
    ['slug' => 'epilepsy',                 'title' => 'Epilepsy'],
    ['slug' => 'diabetes-mellitus',        'title' => 'Diabetes Mellitus'],
    ['slug' => 'cardiovascular-disorders', 'title' => 'Cardiovascular Disorders'],
    ['slug' => 'psychiatric-disorders',    'title' => 'Psychiatric Disorders'],
    ['slug' => 'renal-disorders',          'title' => 'Renal Disorders'],
    ['slug' => 'respiratory-sleep-disorders', 'title' => 'Respiratory & Sleep Disorders'],
    ['slug' => 'learning-difficulties',    'title' => 'Learning Difficulties'],
];
@endphp

{{-- Hero --}}
<section class="relative overflow-hidden py-10"
    style="background-image:url('{{ asset('images/hero-bg-v2.png') }}');background-size:cover;background-position:center right">
    <div class="absolute inset-0"
        style="background:linear-gradient(105deg,hsl(215 81% 14% / 0.97) 45%,hsl(215 81% 23% / 0.70) 100%)"></div>
    <div class="relative container mx-auto px-6">
        <p class="text-xs font-bold uppercase mb-5" style="color:#ffcf00;letter-spacing:0.2em">
            Independent Medical Expertise
        </p>
        <h1 class="font-display text-4xl md:text-5xl xl:text-6xl font-extrabold text-white leading-[1.1] mb-5 max-w-xl"
            style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">
            Comprehensive Driving <span style="color:#ffcf00">Assessments.</span>
        </h1>
        <p class="text-white/70 text-base leading-relaxed mb-10 max-w-md">
            Ensuring safety and independence through specialised medical evaluations for drivers with complex health conditions.
        </p>
        <a href="#specialist-assessments"
           class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-bold text-base transition-all hover:brightness-105 active:scale-95"
           style="background:#ffcf00;color:#231b00">
            View Specialist Services
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>

{{-- Our Objective & Independence --}}
<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

            {{-- Left --}}
            <div>
                <div class="mb-2">
                    <h2 class="font-display text-3xl font-extrabold mb-3"
                        style="letter-spacing:-0.02em;color:#111827;font-family:Manrope,sans-serif">
                        Our Objective &amp; Independence
                    </h2>
                    <div class="w-12 h-1 rounded-full" style="background:#ffcf00"></div>
                </div>
                <p class="leading-relaxed mt-5 mb-8 text-base" style="color:#6b7280">
                    Our sole focus is the intersection of medical health and road safety, operating in harmony with stakeholders but independently to ensure fair outcomes.
                </p>
                <ul class="space-y-3">
                    @foreach([
                        'Evidence-based clinical protocols',
                        'Multi-disciplinary specialist review',
                        'Focused on preserving driver autonomy',
                    ] as $item)
                    <li class="flex items-center gap-3 text-base" style="color:#111827">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color:hsl(215 81% 23%)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right: 2×2 feature boxes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach([
                    {{-- ORIGINAL: DAI feedback 26-06-26 --}}
                    {{-- ['Clinical Rigor',      'Assessments are conducted by our professional team of assessors. Each one fully qualified, insured, Garda Vetted and RSA approved. With an average of twelve years of experience, each assessor has a strong history in the industry.'], --}}
                    ['Clinical Rigour',      'Assessments are conducted by our professional team of assessors. Each one fully qualified, insured, Garda Vetted and RSA approved. With an average of twelve years of experience, each assessor has a strong history in the industry.'],
                    ['Safe Mobility',       'We commit to keeping you on the road safely through vehicle modification recommendations, helpful restrictive licensing or specialised training recommendations.'],
                    ['Independent Status',  'Neutral third-party perspective trusted by licensing authorities and healthcare practitioners nationwide.'],
                    ['Compassionate Care',  'We understand the importance of driving for your life. Our assessments are conducted with dignity and empathy.'],
                ] as [$title, $desc])
                <div class="rounded-xl p-5"
                     style="border-left:3px solid hsl(215 81% 23%);background:hsl(210 11% 97%)">
                    <p class="font-bold text-sm mb-2" style="color:hsl(215 81% 23%)">{{ $title }}</p>
                    <p class="text-sm leading-relaxed" style="color:#6b7280">{{ $desc }}</p>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

{{-- ORIGINAL: DAI feedback 26-06-26 --}}
{{-- Specialized Condition Assessments --}}
{{-- Specialised Condition Assessments --}}
<section id="specialist-assessments" class="py-24" style="background:hsl(210 11% 97%)">
    <div class="container mx-auto px-6">

        {{-- Header row --}}
        <div class="flex items-start justify-between mb-10">
            <div class="max-w-sm">
                <h2 class="font-display text-3xl font-extrabold leading-tight mb-3"
                    style="letter-spacing:-0.02em;color:hsl(215 81% 23%);font-family:Manrope,sans-serif">
                    {{-- ORIGINAL: DAI feedback 26-06-26 Specialized Condition Assessments --}}
                    Specialised Condition Assessments
                </h2>
                <p class="text-base leading-relaxed" style="color:#6b7280">
                    Targeted evaluations designed for specific medical presentations and their impact on operational driving skills.
                </p>
            </div>
        </div>

        {{-- 6-card grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
            @foreach($featured as $item)
            <a href="{{ route('service.show', $item['slug']) }}"
               class="bg-white rounded-xl p-6 flex flex-col gap-4 transition-all hover:-translate-y-0.5 hover:shadow-md cursor-pointer"
               style="box-shadow:0px 4px 12px rgba(25,28,29,0.06);border:1px solid rgba(25,28,29,0.08)">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                     style="background:hsl(210 11% 96%)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color:hsl(215 81% 23%)" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="font-display font-bold text-base mb-2"
                       style="letter-spacing:-0.01em;color:#111827;font-family:Manrope,sans-serif">
                        {{ $item['title'] }}
                    </p>
                    <p class="text-base leading-relaxed" style="color:#6b7280">{{ $item['desc'] }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-sm font-semibold mt-auto"
                      style="color:hsl(215 81% 23%)">
                    Learn more
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>
            @endforeach
        </div>

        {{-- More conditions --}}
        <div class="text-center">
            <p class="text-base mb-3" style="color:#6b7280">
                We assess many other conditions — including epilepsy, diabetes, cardiovascular disorders, and more.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($moreConditions as $cond)
                <a href="{{ route('service.show', $cond['slug']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-white text-sm font-medium border hover:border-primary/40 transition-colors"
                   style="color:hsl(215 81% 23%);border-color:rgba(25,28,29,0.12)">
                    {{ $cond['title'] }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Need a specialist referral? --}}
<section class="py-16" style="background:hsl(210 11% 97%)">
    <div class="container mx-auto px-6">
        <div class="rounded-2xl px-8 py-14 text-center relative overflow-hidden"
             style="background:radial-gradient(ellipse at 70% 50%,hsl(215 81% 23%) 0%,hsl(215 81% 14%) 100%)">
            <div class="absolute inset-0 pointer-events-none opacity-10"
                 style="background-image:repeating-linear-gradient(-45deg,rgba(255,255,255,0.08) 0px,rgba(255,255,255,0.08) 1px,transparent 1px,transparent 28px)"></div>
            <div class="relative z-10 max-w-lg mx-auto">
                <h2 class="font-display text-3xl font-extrabold text-white mb-4"
                    style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">
                    Need a specialist referral?
                </h2>
                <p class="text-base mb-8 leading-relaxed" style="color:rgba(255,255,255,0.7)">
                    Our team is available to discuss specific patient requirements or corporate assessment packages.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('hcp-referral') }}"
                       class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-bold text-base transition-all hover:brightness-105 active:scale-95"
                       style="background:#ffcf00;color:#231b00">
                        {{-- ORIGINAL: DAI feedback 26-06-26 Download Referral Form --}}
                        HCP Secure Referral
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-bold text-base text-white transition-all hover:bg-white/10 active:scale-95"
                       style="border:2px solid rgba(255,255,255,0.35)">
                        {{-- ORIGINAL: DAI feedback 26-06-26 Contact our Team --}}
                        Book an Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
