@extends('layouts.admin')
@section('title', 'Add Team Member')
@section('page-title', 'Add Team Member')
@section('page-actions')
<button type="submit" form="main-form" class="header-btn">Save Team Member</button>
@endsection

@section('content')
<div class="mb-4"><a href="{{ route('admin.team-members.index') }}" class="text-navy hover:underline text-sm">← Back to Team Members</a></div>

<div class="bg-white rounded-xl shadow p-8 max-w-2xl">
    @if($errors->any())
    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif

    <form id="main-form" method="POST" action="{{ route('admin.team-members.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Job Title / Role</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Driving Assessor & Instructor" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Profile Photo</label>
            <div id="photo-preview" class="hidden mb-3">
                <img id="photo-img" src="" alt="Preview" class="w-28 h-28 rounded-full object-cover border-2 border-gray-200">
            </div>
            <input type="file" id="photo-file" accept="image/jpeg,image/png,image/webp" class="text-sm text-gray-600">
            <input type="hidden" name="photo_data" id="photo_data">
            <p class="text-xs text-gray-400 mt-1">Recommended: square, min 400×400px. JPG, PNG or WebP.</p>
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Biography</label>
            <textarea name="bio" rows="8" placeholder="Write the team member's biography here. Separate paragraphs with a blank line." class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy">{{ old('bio') }}</textarea>
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Business Website URL</label>
            <input type="url" name="website" value="{{ old('website') }}" placeholder="https://www.example.ie" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
            <p class="text-xs text-gray-400 mt-1">If set, a "Visit Website" button will appear on their public profile.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
                <p class="text-xs text-gray-400 mt-1">Lower = appears first on team page.</p>
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-4 h-4 text-navy rounded">
                    <span class="text-sm font-medium text-gray-700">Visible on website</span>
                </label>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="bg-navy text-white px-7 py-2.5 rounded-lg text-sm font-semibold hover:bg-navy-light">Add Team Member</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('photo-file').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photo_data').value = e.target.result;
        document.getElementById('photo-img').src = e.target.result;
        document.getElementById('photo-preview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
