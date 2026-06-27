@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    @foreach([
        ['Total Applications', $stats['total_applications'], 'bg-blue-100 text-blue-800'],
        ['Pending Applications', $stats['pending_applications'], 'bg-yellow-100 text-yellow-800'],
        ['Completed', $stats['completed_applications'], 'bg-green-100 text-green-800'],
        ['HCP Referrals', $stats['hcp_referrals'], 'bg-purple-100 text-purple-800'],
        ['Total Revenue', '€'.number_format($stats['total_revenue'], 2), 'bg-emerald-100 text-emerald-800'],
        ['Unread Messages', $stats['unread_contacts'], 'bg-red-100 text-red-800'],
    ] as $stat)
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">{{ $stat[0] }}</p>
        <p class="text-3xl font-bold {{ $stat[2] }} rounded-lg px-3 py-1 inline-block">{{ $stat[1] }}</p>
    </div>
    @endforeach
</div>
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Recent Applications</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-gray-200 text-left text-gray-500">
                <th class="pb-2 font-medium">Applicant</th>
                <th class="pb-2 font-medium">Email</th>
                <th class="pb-2 font-medium">Status</th>
                <th class="pb-2 font-medium">Date</th>
                <th class="pb-2 font-medium"></th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recent_applications as $app)
                <tr>
                    <td class="py-3">{{ $app->first_name ?: '—' }} {{ $app->last_name }}</td>
                    <td class="py-3 text-gray-500">{{ $app->email }}</td>
                    <td class="py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $app->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($app->status) }}</span></td>
                    <td class="py-3 text-gray-500">{{ $app->created_at->format('d/m/Y') }}</td>
                    <td class="py-3"><a href="{{ route('admin.applications.show', $app) }}" class="text-navy hover:underline text-xs font-semibold">View →</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-400">No applications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
