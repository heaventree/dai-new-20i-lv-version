@extends('layouts.admin')
@section('title', 'CMS Pages')
@section('page-title', 'CMS Pages')
@section('content')
@php
    $frontendUrl = function($page) {
        if ($page->slug === 'about')   return route('about');
        if ($page->slug === 'faq')     return route('faq');
        if ($page->slug === 'contact') return route('contact');
        if (str_starts_with($page->slug, 'service-')) {
            return route('service.show', substr($page->slug, 8));
        }
        return null;
    };
@endphp
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left text-gray-500">
            <th class="px-4 py-3 font-medium">Title</th>
            <th class="px-4 py-3 font-medium">Slug</th>
            <th class="px-4 py-3 font-medium">Published</th>
            <th class="px-4 py-3 font-medium">Last Updated</th>
            <th class="px-4 py-3 font-medium"></th>
            <th class="px-4 py-3 font-medium"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $page)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $page->title }}</td>
                <td class="px-4 py-3 text-gray-500 font-mono text-xs">/{{ $page->slug }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $page->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $page->is_published ? 'Published' : 'Draft' }}</span></td>
                <td class="px-4 py-3 text-gray-500">{{ $page->updated_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    @if($frontendUrl($page))
                    <a href="{{ $frontendUrl($page) }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-navy hover:underline font-semibold text-xs">View →</a>
                    @endif
                </td>
                <td class="px-4 py-3"><a href="{{ route('admin.cms-pages.edit', $page) }}" class="text-navy hover:underline font-semibold text-xs">Edit →</a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No pages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
