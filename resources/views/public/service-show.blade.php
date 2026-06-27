@extends('layouts.app')
@section('title', $service->title . ' Driving Assessment — Driver Assessments Ireland')
@section('meta_description', $service->meta_description ?: ($service->content_json['summary'] ?? 'Driver assessment for ' . $service->title . ' — serving all 32 counties of Ireland.'))

@section('content')

@php
$cj = $service->content_json ?? [];
$title      = $service->title;
$overview   = $cj['overview'] ?? '';
$impact     = $cj['impact'] ?? '';
$assessment = $cj['assessment'] ?? '';
$buttons    = array_filter($cj['buttons'] ?? [], fn($b) => !empty($b['url']) && !empty($b['label']));

// Parse impact string into up to 4 items (mirrors React parseImpactItems)
$impactItems = [];
if ($impact) {
    $raw = rtrim(trim($impact), '.');
    $parts = preg_split('/,\s*(?:and\s+)?/', $raw);
    $parts = array_slice(array_filter(array_map('trim', $parts)), 0, 4);
    foreach ($parts as $item) {
        $words = explode(' ', $item);
        $label  = implode(' ', array_slice($words, 0, 4));
        $detail = count($words) > 4 ? implode(' ', array_slice($words, 4)) : $item;
        $impactItems[] = ['label' => $label, 'detail' => $detail ?: $item];
    }
}

$impactIcons = [
    'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2', // Brain
    'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', // Eye
    'M13 10V3L4 14h7v7l9-11h-7z', // Zap
    'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', // Activity
];

$assessmentSteps = [
    ['num' => 1, 'title' => 'Clinical Review',
     'desc' => 'A detailed analysis of your medical history, current medications, and condition-specific concerns with our specialist assessor.',
     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
    ['num' => 2, 'title' => 'On-Road Assessment',
     'desc' => 'Complete a practical driving session in a dual-controlled vehicle, evaluating real-world performance under clinical supervision.',
     'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h10a1 1 0 001-1zM14 10h4l3 3v3h-7V10z'],
    ['num' => 3, 'title' => 'Report & Recommendations',
     'desc' => 'Receive a comprehensive written clinical report with evidence-based recommendations, forwarded to your referring HCP within 5–7 days.',
     'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
];
@endphp

{{-- Breadcrumb --}}
<div class="bg-white border-b" style="border-color:rgba(25,28,29,0.08)">
    <div class="container mx-auto px-6 py-3">
        <nav class="flex items-center gap-2 text-xs" style="color:#6b7280">
            <a href="{{ route('services') }}" class="hover:text-primary transition-colors" style="color:inherit">
                All Conditions
            </a>
            <span>/</span>
            <span class="font-medium" style="color:#111827">{{ $title }}</span>
        </nav>
    </div>
</div>

{{-- Main --}}
<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-12 items-start">

            {{-- LEFT: main content --}}
            <div>
                {{-- Heading --}}
                <div class="mb-8">
                    <h1 class="font-display text-3xl md:text-4xl font-extrabold leading-tight mb-3"
                        style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">
                        <span style="color:#111827">{{ $title }} &amp;</span>
                        <span style="color:#ffcf00"> Driving Safety</span>
                    </h1>
                </div>

                {{-- Hero image --}}
                <div class="rounded-2xl overflow-hidden mb-12" style="height:460px">
                    <img src="{{ asset('images/assessment-scene-v2.png') }}"
                         alt="{{ $title }} driving assessment"
                         class="w-full h-full object-cover"
                         onerror="this.style.background='hsl(215 81% 14%)';this.style.display='block';this.removeAttribute('src')">
                </div>

                {{-- Overview --}}
                @if($overview)
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-1 h-5 rounded-full" style="background:#ffcf00"></div>
                        <h2 class="font-display text-lg font-extrabold" style="letter-spacing:-0.01em;color:#111827;font-family:Manrope,sans-serif">
                            Overview
                        </h2>
                    </div>
                    <div class="space-y-3 text-sm leading-relaxed" style="color:#6b7280">
                        @foreach(preg_split('/\n\n+/', trim($overview)) as $para)
                            @if(trim($para))<p>{{ trim($para) }}</p>@endif
                        @endforeach
                    </div>
                    @if(count($buttons))
                    <div class="flex flex-wrap gap-3 mt-5">
                        @foreach($buttons as $i => $btn)
                        <a href="{{ $btn['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm transition-all hover:brightness-105 active:scale-95"
                           style="{{ $i === 0 ? 'background:#ffcf00;color:#231b00' : 'background:hsl(215 81% 23%);color:#fff' }}">
                            {{ $btn['label'] }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- How it Affects Driving --}}
                @if(count($impactItems))
                <div class="rounded-2xl p-6 mb-10" style="background:hsl(210 11% 96%)">
                    <h2 class="font-display text-lg font-extrabold mb-4"
                        style="letter-spacing:-0.01em;color:#111827;font-family:Manrope,sans-serif">
                        How it Affects Driving
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($impactItems as $i => $item)
                        <div class="bg-white rounded-xl p-5"
                             style="box-shadow:0px 4px 12px rgba(25,28,29,0.06);border-left:{{ $i % 2 === 1 ? '3px solid #ffcf00' : '3px solid transparent' }}">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-3"
                                 style="background:hsl(210 11% 95%)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     style="color:hsl(215 81% 23%)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="{{ $impactIcons[$i % count($impactIcons)] }}"/>
                                </svg>
                            </div>
                            <p class="font-bold text-sm mb-1 capitalize" style="color:#111827">{{ $item['label'] }}</p>
                            <p class="text-xs leading-relaxed" style="color:#6b7280">{{ $item['detail'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @elseif($impact)
                <div class="rounded-2xl p-6 mb-10" style="background:hsl(210 11% 96%)">
                    <h2 class="font-display text-lg font-extrabold mb-4"
                        style="letter-spacing:-0.01em;color:#111827;font-family:Manrope,sans-serif">
                        How it Affects Driving
                    </h2>
                    <p class="text-sm leading-relaxed" style="color:#6b7280">{{ $impact }}</p>
                </div>
                @endif

                {{-- The Assessment Process --}}
                <div class="mb-8">
                    <h2 class="font-display text-lg font-extrabold mb-5"
                        style="letter-spacing:-0.01em;color:#111827;font-family:Manrope,sans-serif">
                        The Assessment Process
                    </h2>
                    <div class="space-y-5">
                        @foreach($assessmentSteps as $step)
                        <div class="flex gap-5 items-start p-5 bg-white rounded-xl"
                             style="box-shadow:0px 4px 12px rgba(25,28,29,0.06)">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm shrink-0"
                                 style="background:hsl(215 81% 23%)">
                                {{ $step['num'] }}
                            </div>
                            <div>
                                <p class="font-bold text-sm mb-1" style="color:#111827">{{ $step['title'] }}</p>
                                <p class="text-sm leading-relaxed" style="color:#6b7280">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- RIGHT: sticky sidebar --}}
            <aside class="space-y-5 lg:sticky lg:top-24">

                {{-- Book card — navy radial gradient --}}
                <div class="rounded-2xl p-6 text-white"
                     style="background:radial-gradient(ellipse at 70% 30%,hsl(215 81% 23%) 0%,hsl(215 81% 14%) 100%)">
                    <p class="font-bold text-sm mb-1">Book an Assessment</p>
                    <div class="flex items-baseline gap-1.5 mb-5">
                        <span class="font-display text-4xl font-extrabold"
                              style="color:#ffcf00;letter-spacing:-0.02em;font-family:Manrope,sans-serif">€235</span>
                    </div>
                    <ul class="space-y-2.5 mb-6">
                        @foreach([
                            'Full clinical report provided within 5–7 days',
                            'RSA-compliant assessment protocol',
                            'Available across all 32 counties of Ireland',
                        ] as $item)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:#ffcf00">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.8)">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('assessment.index') }}"
                       class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-bold text-sm transition-all hover:brightness-105 active:scale-95"
                       style="background:#ffcf00;color:#231b00">
                        Book Appointment
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <p class="text-xs mt-3 text-center leading-relaxed" style="color:rgba(255,255,255,0.4)">
                        Secure payment processed on day of assessment.
                    </p>
                </div>

                {{-- HCP card --}}
                <div class="bg-white rounded-2xl p-6" style="box-shadow:0px 4px 12px rgba(25,28,29,0.06)">
                    <p class="font-bold text-sm mb-2" style="color:#111827">Healthcare Professionals</p>
                    <p class="text-xs leading-relaxed mb-4" style="color:#6b7280">
                        Referring a patient with this condition? We provide streamlined reporting for GPs, Occupational Therapists, and Consultants.
                    </p>
                    <a href="{{ route('hcp-referral') }}"
                       class="inline-flex items-center gap-1.5 text-sm font-bold transition-all hover:gap-2.5"
                       style="color:hsl(215 81% 23%)">
                        Professional Referral Portal
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Location card --}}
                <div class="rounded-2xl overflow-hidden relative" style="height:130px;background:radial-gradient(ellipse at 60% 50%,hsl(215 81% 23%) 0%,hsl(215 81% 14%) 100%)">
                    <div class="relative z-10 p-5">
                        <p class="text-xs uppercase mb-1" style="color:rgba(255,255,255,0.5);letter-spacing:0.1em">
                            Nationwide Coverage
                        </p>
                        <p class="font-bold text-sm text-white">All 32 Counties, Ireland</p>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</section>

{{-- Other Conditions --}}
@php
$otherServices = \App\Models\CmsPage::where('slug', 'like', 'service-%')
    ->where('slug', '!=', $service->slug)
    ->where('is_published', true)
    ->orderBy('display_order')
    ->take(4)
    ->get();
@endphp
@if($otherServices->count())
<section class="py-16" style="background:hsl(210 11% 97%)">
    <div class="container mx-auto px-6">
        <h2 class="font-display text-xl font-extrabold mb-6"
            style="letter-spacing:-0.02em;color:#111827;font-family:Manrope,sans-serif">
            Other Conditions We Assess
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($otherServices as $other)
            <a href="{{ route('service.show', Str::after($other->slug, 'service-')) }}"
               class="group bg-white rounded-xl p-5 flex flex-col justify-between transition-all hover:-translate-y-0.5"
               style="box-shadow:0px 4px 12px rgba(25,28,29,0.06)">
                <p class="font-semibold text-sm leading-snug" style="color:#111827">{{ $other->title }}</p>
                <div class="flex justify-end mt-4">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center"
                         style="background:hsl(215 81% 23%)">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
