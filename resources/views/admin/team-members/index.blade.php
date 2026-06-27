@extends('layouts.admin')
@section('title', 'Team Members')
@section('page-title', 'Team Members')
@section('page-actions')
<a href="{{ route('admin.team-members.create') }}" class="header-btn">+ Add Member</a>
@endsection

@section('content')
<p class="text-sm text-gray-500 mb-6">Manage public team profiles — name, title, bio and photo.</p>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow overflow-hidden">
    @if($members->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p class="font-medium">No team members yet.</p>
        <a href="{{ route('admin.team-members.create') }}" class="text-navy text-sm hover:underline mt-1 inline-block">Add the first one</a>
    </div>
    @else
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-gray-600 w-12">#</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Photo</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Name</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($members as $member)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-400">{{ $member->display_order }}</td>
                <td class="px-5 py-3">
                    @if($member->photo)
                        <img src="{{ $member->photo }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    @endif
                </td>
                <td class="px-5 py-3 font-medium text-gray-900">{{ $member->name }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $member->title ?? '—' }}</td>
                <td class="px-5 py-3">
                    @if($member->is_active)
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Active</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-full">Hidden</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.team-members.edit', $member) }}" class="text-navy hover:underline">Edit</a>
                        <a href="{{ route('team.show', $member->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-700">View</a>
                        <form method="POST" action="{{ route('admin.team-members.destroy', $member) }}" onsubmit="return confirm('Delete {{ $member->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
