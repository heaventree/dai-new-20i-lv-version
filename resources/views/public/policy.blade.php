@extends('layouts.app')
@section('title', $title . ' — Driver Assessments Ireland')

@section('content')
@php $page = \App\Models\CmsPage::where('slug', $slug)->first(); @endphp

<div class="max-w-4xl mx-auto px-6 md:px-8 py-16">
    <h1 class="font-headline text-4xl font-extrabold text-primary mb-8">{{ $page->title ?? $title }}</h1>
    @if($page && $page->content)
    <div class="prose prose-lg max-w-none text-on-surface-variant leading-relaxed">
        {!! $page->content !!}
    </div>
    @else
    <p class="text-on-surface-variant">This page is being updated. Please check back soon.</p>
    @endif
</div>
@endsection
