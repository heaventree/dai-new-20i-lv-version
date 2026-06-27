@extends('layouts.admin')
@section('title', $testimonial->id ? 'Edit Testimonial' : 'Add Testimonial')
@section('page-title', $testimonial->id ? 'Edit Testimonial' : 'Add Testimonial')
@section('page-actions')
<button type="submit" form="main-form" class="header-btn">{{ $testimonial->id ? 'Save Changes' : 'Add Testimonial' }}</button>
@endsection

@section('content')
<div class="mb-4"><a href="{{ route('admin.testimonials.index') }}" class="text-navy hover:underline text-sm">← Back to Testimonials</a></div>
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form id="main-form" method="POST" action="{{ $testimonial->id ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
        @csrf
        @if($testimonial->id) @method('PUT') @endif
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Quote *</label>
                <textarea name="quote" rows="4" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-navy/20 focus:border-navy outline-none">{{ old('quote', $testimonial->quote) }}</textarea>
                @error('quote')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Name *</label>
                    <input name="name" type="text" required value="{{ old('name', $testimonial->name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-navy/20 focus:border-navy outline-none">
                    @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Location *</label>
                    <input name="location" type="text" required value="{{ old('location', $testimonial->location ?? 'Ireland') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-navy/20 focus:border-navy outline-none" placeholder="Co. Dublin">
                    @error('location')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stars (1–5) *</label>
                    <select name="stars" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-navy/20 focus:border-navy outline-none">
                        @foreach([5,4,3,2,1] as $s)
                        <option value="{{ $s }}" {{ old('stars', $testimonial->stars ?? 5) == $s ? 'selected' : '' }}>{{ $s }} ★</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                    <input name="sort_order" type="number" min="0" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-navy/20 focus:border-navy outline-none">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="highlight" id="highlight" class="w-4 h-4 rounded border-gray-300 text-navy focus:ring-navy" {{ old('highlight', $testimonial->highlight) ? 'checked' : '' }}>
                <label for="highlight" class="text-sm font-semibold text-gray-700">Show on homepage (highlighted)</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-navy text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-navy-light transition">
                {{ $testimonial->id ? 'Save Changes' : 'Add Testimonial' }}
            </button>
            <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-2 rounded-lg text-sm font-semibold border border-gray-200 hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
