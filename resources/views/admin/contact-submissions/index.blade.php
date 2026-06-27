@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')
@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left text-gray-500">
            <th class="px-4 py-3 font-medium">Name</th>
            <th class="px-4 py-3 font-medium">Email</th>
            <th class="px-4 py-3 font-medium">Phone</th>
            <th class="px-4 py-3 font-medium">Message</th>
            <th class="px-4 py-3 font-medium">Date</th>
            <th class="px-4 py-3 font-medium"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($submissions as $s)
            <tr class="hover:bg-gray-50 {{ !$s->read ? 'bg-blue-50' : '' }}">
                <td class="px-4 py-3 font-medium">{{ $s->first_name }} {{ $s->last_name }} @if(!$s->read)<span class="text-blue-600 text-xs ml-1">•New</span>@endif</td>
                <td class="px-4 py-3">{{ $s->email }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $s->phone ?: '—' }}</td>
                <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ Str::limit($s->message, 60) }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $s->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3"><a href="{{ route('admin.contact-submissions.show', $s) }}" class="text-navy hover:underline font-semibold text-xs">View →</a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No messages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-4 border-t">{{ $submissions->links() }}</div>
</div>
@endsection
