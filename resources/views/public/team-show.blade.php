@extends('layouts.app')
@section('title', $member->name . ' — Driver Assessments Ireland')
@section('meta_description', $member->name . ' is a Driver Assessor & Instructor at Driver Assessments Ireland.')

@section('content')

@php
$bioParagraphs = collect(preg_split('/\n\s*\n/', trim(strip_tags($member->bio ?? ''))))
    ->map(fn($p) => trim($p))
    ->filter()
    ->values();
@endphp

<div class="min-h-screen" style="background-color:#eef2f8">
    <div class="container mx-auto px-4 max-w-6xl py-12 md:py-20">

        {{-- Back link --}}
        <a href="{{ route('team') }}"
           class="inline-flex items-center gap-2 text-sm font-medium mb-10 transition-opacity hover:opacity-70"
           style="color:#0b3168">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Our Team
        </a>

        <div class="grid grid-cols-1 md:grid-cols-[420px_1fr] gap-12 lg:gap-20 items-start">

            {{-- Left: Photo card --}}
            <div class="flex justify-center md:justify-start">
                <div class="relative" style="filter:drop-shadow(0 8px 40px rgba(11,49,104,0.18))">
                    {{-- Gold glow border --}}
                    <div class="absolute inset-0 rounded-2xl pointer-events-none" style="box-shadow:0 0 0 3px #ffcf00,0 0 0 6px rgba(255,207,0,0.18);z-index:1"></div>

                    {{-- White card frame --}}
                    <div class="bg-white rounded-2xl p-2.5 relative overflow-hidden" style="width:380px;max-width:100%">
                        {{-- Photo --}}
                        @if($member->photo)
                            <img src="{{ $member->photo }}" alt="{{ $member->name }}"
                                 class="w-full rounded-xl object-cover" style="aspect-ratio:3/4;display:block">
                        @else
                            <div class="w-full rounded-xl flex items-center justify-center bg-slate-100" style="aspect-ratio:3/4">
                                <svg style="width:80px;height:80px;color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- RSA badge --}}
                        <div class="absolute bottom-5 left-5 flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-bold uppercase"
                             style="background:rgba(11,49,104,0.88);color:#fff;letter-spacing:0.12em">
                            <span class="inline-block rounded-full flex-shrink-0" style="width:7px;height:7px;background:#ffcf00"></span>
                            RSA-Approved
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Content --}}
            <div class="flex flex-col justify-start">

                {{-- Role pill --}}
                <div class="mb-4">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold uppercase"
                          style="background:#e3e9f4;color:#0b3168;letter-spacing:0.1em">
                        <span class="inline-block rounded-full flex-shrink-0" style="width:6px;height:6px;background:#0b3168"></span>
                        Driver Assessor &amp; Instructor
                    </span>
                </div>

                {{-- Name --}}
                <h1 class="font-extrabold leading-none mb-6"
                    style="color:#0b3168;font-size:clamp(2.4rem,5vw,3.75rem);line-height:1.05;font-family:Manrope,sans-serif">
                    {{ $member->name }}
                </h1>

                {{-- Professional Overview divider --}}
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex-1 h-px" style="background:#d1dae8"></div>
                    <span class="text-xs font-bold uppercase shrink-0" style="color:#8fa3c0;letter-spacing:0.16em">
                        Professional Overview
                    </span>
                    <div class="flex-1 h-px" style="background:#d1dae8"></div>
                </div>

                {{-- Bio --}}
                <div class="space-y-4 mb-8">
                    @if($bioParagraphs->count())
                        @foreach($bioParagraphs as $para)
                        <p class="leading-relaxed" style="color:#374151;font-size:1rem">{{ $para }}</p>
                        @endforeach
                    @else
                        <p class="leading-relaxed" style="color:#9ca3af">Full biography coming soon.</p>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div class="flex flex-wrap gap-3 mb-10">
                    <a href="{{ route('assessment.index') }}"
                       class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full font-bold text-sm transition-all hover:brightness-95 active:scale-95"
                       style="background:#ffcf00;color:#231b00">
                        <svg style="width:17px;height:17px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Book Assessment
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full font-bold text-sm transition-all hover:bg-slate-50 active:scale-95"
                       style="background:#fff;color:#0b3168;border:2px solid #d1dae8">
                        <svg style="width:17px;height:17px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact DAI
                    </a>
                    @if($member->website)
                    <a href="{{ $member->website }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full font-bold text-sm transition-all hover:bg-slate-50 active:scale-95"
                       style="background:#fff;color:#0b3168;border:2px solid #d1dae8">
                        <svg style="width:17px;height:17px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Visit Website
                    </a>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 pt-6" style="border-top:1px solid #d1dae8">
                    @foreach([
                        ['RSA-Approved', 'Driving Instructor'],
                        ['€235', 'Assessment Fee'],
                        ['Nationwide', 'Service'],
                    ] as [$value, $label])
                    <div class="text-left">
                        <div class="font-extrabold leading-none mb-1"
                             style="color:#0b3168;font-size:clamp(1.2rem,2.5vw,1.6rem);font-family:Manrope,sans-serif">
                            {{ $value }}
                        </div>
                        <div class="text-sm" style="color:#6b7280">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
