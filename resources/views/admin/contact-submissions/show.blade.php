@extends('layouts.admin')
@section('title', 'Message Detail')
@section('page-title', 'Contact Message')
@section('content')
<div class="mb-4"><a href="{{ route('admin.contact-submissions.index') }}" class="text-navy hover:underline text-sm">← Back</a></div>
<div class="bg-white rounded-xl shadow p-8 max-w-2xl">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-xl font-bold text-navy">{{ $contactSubmission->first_name }} {{ $contactSubmission->last_name }}</h2>
            <p class="text-gray-500 text-sm">{{ $contactSubmission->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="space-x-2">
            <a href="mailto:{{ $contactSubmission->email }}" class="bg-navy text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-navy-light">Reply by Email</a>
            @if($contactSubmission->phone)<a href="tel:{{ $contactSubmission->phone }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200">Call</a>@endif
        </div>
    </div>
    <dl class="grid grid-cols-2 gap-3 text-sm mb-6 bg-gray-50 rounded-xl p-4">
        <dt class="text-gray-500">Email</dt><dd><a href="mailto:{{ $contactSubmission->email }}" class="text-navy hover:underline">{{ $contactSubmission->email }}</a></dd>
        <dt class="text-gray-500">Phone</dt><dd>{{ $contactSubmission->phone ?: '—' }}</dd>
    </dl>
    <div>
        <p class="font-semibold text-gray-700 mb-2">Message:</p>
        <div class="bg-gray-50 rounded-xl p-5 text-base text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $contactSubmission->message }}</div>
    </div>
</div>
@endsection
