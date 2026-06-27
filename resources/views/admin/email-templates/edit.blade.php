@extends('layouts.admin')
@section('title', 'Edit Template')
@section('page-title', 'Edit Email Template: ' . $template->name)
@section('page-actions')
<button type="submit" form="main-form" class="header-btn">Save Changes</button>
@endsection

@section('content')
<div class="mb-4"><a href="{{ route('admin.email-templates.index') }}" class="text-navy hover:underline text-sm">← Back</a></div>
<div class="bg-white rounded-xl shadow p-8 max-w-3xl">
    <p class="text-sm text-gray-500 mb-5">Available variables: <code class="bg-gray-100 px-1 rounded">&#123;&#123;name&#125;&#125;</code> <code class="bg-gray-100 px-1 rounded">&#123;&#123;amount&#125;&#125;</code> <code class="bg-gray-100 px-1 rounded">&#123;&#123;order_id&#125;&#125;</code> <code class="bg-gray-100 px-1 rounded">&#123;&#123;hcp_name&#125;&#125;</code> <code class="bg-gray-100 px-1 rounded">&#123;&#123;patient_name&#125;&#125;</code></p>
    <form id="main-form" method="POST" action="{{ route('admin.email-templates.update', $template) }}" class="space-y-5">
        @csrf
        <div>
            <label class="block font-semibold text-gray-700 mb-2 text-sm">Subject</label>
            <input type="text" name="subject" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm" value="{{ old('subject', $template->subject) }}" required>
        </div>
        <div>
            <label class="block font-semibold text-gray-700 mb-2 text-sm">Email Body (plain text)</label>
            <textarea name="body" rows="14" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm font-mono">{{ old('body', $template->body) }}</textarea>
        </div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
            <span class="text-sm font-medium text-gray-700">Active (will be sent)</span>
        </label>
        <button type="submit" class="bg-navy text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-navy-light">Save Changes</button>
    </form>
</div>
@endsection
