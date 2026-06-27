@extends('layouts.admin')
@section('title', 'HCP Referrals')
@section('page-title', 'HCP Referrals')
@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left text-gray-500">
            <th class="px-4 py-3 font-medium">HCP Name</th>
            <th class="px-4 py-3 font-medium">Reg. No.</th>
            <th class="px-4 py-3 font-medium">Patient</th>
            <th class="px-4 py-3 font-medium">Status</th>
            <th class="px-4 py-3 font-medium">Date</th>
            <th class="px-4 py-3 font-medium"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($referrals as $r)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $r->hcp_name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $r->hcp_registration_no }}</td>
                <td class="px-4 py-3">{{ $r->patient_full_name }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ ucfirst($r->status) }}</span></td>
                <td class="px-4 py-3 text-gray-500">{{ $r->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3"><a href="{{ route('admin.hcp-referrals.show', $r) }}" class="text-navy hover:underline font-semibold text-xs">View →</a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No referrals yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-4 border-t">{{ $referrals->links() }}</div>
</div>
@endsection
