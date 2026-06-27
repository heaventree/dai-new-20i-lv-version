@extends('layouts.admin')
@section('title', 'Email Logs')
@section('page-title', 'Email Logs')
@section('content')

<p class="text-gray-500 text-sm -mt-1 mb-6">History of all emails sent by the system</p>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background:#f1f5f9">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Emails</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-green-50">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-green-600">{{ $sent }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Sent</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-red-50">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-red-500">{{ $failed }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Failed</p>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="flex items-center gap-3 mb-4">
    <form method="GET" action="{{ route('admin.email-logs.index') }}" class="flex-1 max-w-xs">
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by email, subject, type..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>
    </form>
    <a href="{{ route('admin.email-logs.index') }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium border border-gray-200 rounded-lg bg-white hover:bg-gray-50 text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Refresh
    </a>
    <form method="POST" action="{{ route('admin.email-logs.clear') }}"
          onsubmit="return confirm('Delete all email logs? This cannot be undone.')">
        @csrf @method('DELETE')
        <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium border border-red-200 rounded-lg bg-white hover:bg-red-50 text-red-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Clear All
        </button>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b text-left text-gray-500 text-xs uppercase tracking-wide">
                <th class="px-4 py-3 font-semibold">Status</th>
                <th class="px-4 py-3 font-semibold">Type</th>
                <th class="px-4 py-3 font-semibold">To</th>
                <th class="px-4 py-3 font-semibold">Subject</th>
                <th class="px-4 py-3 font-semibold">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            @php
                $typeColors = [
                    'contact'                  => 'bg-gray-100 text-gray-700',
                    'contact-auto-reply'       => 'bg-gray-100 text-gray-600',
                    'hcp-referral'             => 'bg-purple-100 text-purple-700',
                    'hcp-referral-confirmation'=> 'bg-purple-100 text-purple-600',
                    'assessment-confirmation'  => 'bg-blue-100 text-blue-700',
                    'assessment'               => 'bg-blue-100 text-blue-700',
                    'payment-receipt'          => 'bg-green-100 text-green-700',
                    'payment'                  => 'bg-green-100 text-green-700',
                ];
                $badge = $typeColors[$log->template] ?? 'bg-gray-100 text-gray-600';
                $label = match($log->template) {
                    'hcp-referral'              => 'HCP Referral',
                    'hcp-referral-confirmation' => 'HCP Referral',
                    'assessment-confirmation'   => 'Assessment',
                    'assessment'                => 'Assessment',
                    'payment-receipt'           => 'Payment',
                    'payment'                   => 'Payment',
                    'contact'                   => 'Contact',
                    'contact-auto-reply'        => 'contact-autoreply',
                    default                     => $log->template ?: '—',
                };
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $log->status === 'sent' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        @if($log->status === 'sent')
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        @endif
                        {{ ucfirst($log->status) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-md text-xs font-medium {{ $badge }}">{{ $label }}</span>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $log->to_email }}</td>
                <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $log->subject }}</td>
                <td class="px-4 py-3 text-gray-400 whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">No email logs yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($logs->hasPages())
    <div class="px-4 py-4 border-t">{{ $logs->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
