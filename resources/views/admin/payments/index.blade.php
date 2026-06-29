@extends('layouts.admin')
@section('title', 'Payments')
@section('page-title', 'Payments')
@section('content')
<div class="bg-white rounded-xl shadow p-4 mb-5 flex items-center justify-between">
    <p class="text-sm text-gray-500">Total Revenue (succeeded):</p>
    <p class="text-2xl font-bold text-green-700">€{{ number_format($total, 2) }}</p>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left text-gray-500">
            <th class="px-4 py-3 font-medium">App ID</th>
            <th class="px-4 py-3 font-medium">Customer</th>
            <th class="px-4 py-3 font-medium">Amount</th>
            <th class="px-4 py-3 font-medium">Status</th>
            <th class="px-4 py-3 font-medium">Stripe PI</th>
            <th class="px-4 py-3 font-medium">Date</th>
            <th class="px-4 py-3 font-medium">Application</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($payments as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    @if($p->application)
                        <a href="{{ route('admin.applications.show', $p->application) }}" class="font-mono text-xs text-navy font-semibold hover:underline">DAI-{{ $p->application->created_at->format('Y') }}-{{ str_pad($p->application->id, 4, '0', STR_PAD_LEFT) }}</a>
                    @else
                        <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">{{ $p->customer_name ?: $p->customer_email }}</td>
                <td class="px-4 py-3 font-medium">€{{ number_format($p->amount, 2) }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $p->status === 'succeeded' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($p->status) }}</span></td>
                <td class="px-4 py-3 text-gray-400 font-mono text-xs">{{ substr($p->stripe_payment_intent_id, 0, 20) }}...</td>
                <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    @if($p->application)
                        <a href="{{ route('admin.applications.show', $p->application) }}" class="text-navy hover:underline font-semibold text-xs">View Application →</a>
                    @else
                        <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No payments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-4 border-t">{{ $payments->links() }}</div>
</div>
@endsection
