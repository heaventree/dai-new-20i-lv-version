@extends('layouts.admin')
@section('title', 'Applications')
@section('page-title', 'Assessment Applications')
@section('content')
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" class="border border-gray-300 rounded-lg px-4 py-2 text-sm flex-1" placeholder="Search by name, email or token..." value="{{ request('search') }}">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Statuses</option>
            @foreach(['pending','submitted','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-navy text-white px-4 py-2 rounded-lg text-sm font-semibold">Search</button>
        @if(request('search') || request('status'))<a href="{{ route('admin.applications.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm">Clear</a>@endif
    </form>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b border-gray-200 text-left text-gray-500">
            <th class="px-4 py-3 font-medium">App ID</th>
            <th class="px-4 py-3 font-medium">Applicant</th>
            <th class="px-4 py-3 font-medium">Email</th>
            <th class="px-4 py-3 font-medium">Payment</th>
            <th class="px-4 py-3 font-medium">Status</th>
            <th class="px-4 py-3 font-medium">Date</th>
            <th class="px-4 py-3 font-medium"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($applications as $app)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-navy font-semibold">DAI-{{ date('Y', strtotime($app->created_at)) }}-{{ str_pad($app->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="px-4 py-3 font-medium">{{ $app->first_name ?: '—' }} {{ $app->last_name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $app->email }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $app->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($app->payment_status) }}</span></td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $app->status === 'completed' ? 'bg-green-100 text-green-700' : ($app->status === 'submitted' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($app->status) }}</span></td>
                <td class="px-4 py-3 text-gray-500">{{ $app->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3"><a href="{{ route('admin.applications.show', $app) }}" class="text-navy hover:underline font-semibold text-xs">View →</a></td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No applications found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-4 border-t border-gray-200">{{ $applications->links() }}</div>
</div>
@endsection
