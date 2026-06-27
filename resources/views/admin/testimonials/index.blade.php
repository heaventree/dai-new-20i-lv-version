@extends('layouts.admin')
@section('title', 'Testimonials')
@section('page-title', 'Testimonials')
@section('page-actions')
<a href="{{ route('admin.testimonials.create') }}" class="header-btn">+ Add Testimonial</a>
@endsection

@section('content')
<p class="text-sm text-gray-500 mb-4">{{ $testimonials->count() }} testimonial(s) — highlighted ones appear on the homepage.</p>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left text-gray-500">
            <th class="px-4 py-3 font-medium">#</th>
            <th class="px-4 py-3 font-medium">Name</th>
            <th class="px-4 py-3 font-medium">Location</th>
            <th class="px-4 py-3 font-medium">Stars</th>
            <th class="px-4 py-3 font-medium">Highlight</th>
            <th class="px-4 py-3 font-medium">Quote (preview)</th>
            <th class="px-4 py-3 font-medium"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($testimonials as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-400">{{ $t->sort_order }}</td>
                <td class="px-4 py-3 font-medium">{{ $t->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->location }}</td>
                <td class="px-4 py-3">{{ str_repeat('★', $t->stars) }}</td>
                <td class="px-4 py-3">
                    @if($t->highlight)
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Yes</span>
                    @else
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">No</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500 max-w-xs truncate">"{{ Str::limit($t->quote, 60) }}"</td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.testimonials.edit', $t) }}" class="text-navy hover:underline text-xs font-semibold">Edit</a>
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete this testimonial?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline text-xs font-semibold">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No testimonials yet. Add one above.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
