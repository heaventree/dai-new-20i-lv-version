@extends('layouts.admin')
@section('title', 'Edit: ' . $teamMember->name)
@section('page-title', 'Edit Team Member')
@section('page-actions')
<button type="submit" form="main-form" class="header-btn">Save Changes</button>
@endsection

@section('content')
<div class="mb-4"><a href="{{ route('admin.team-members.index') }}" class="text-navy hover:underline text-sm">← Back to Team Members</a></div>

<div class="bg-white rounded-xl shadow p-8 max-w-2xl">
    @if($errors->any())
    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif

    <form id="main-form" method="POST" action="{{ route('admin.team-members.update', $teamMember) }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $teamMember->name) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Job Title / Role</label>
            <input type="text" name="title" value="{{ old('title', $teamMember->title) }}" placeholder="e.g. Driving Assessor & Instructor" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Profile Photo</label>
            <div class="flex items-start gap-4 mb-3">
                @if($teamMember->photo)
                    <div id="current-photo-wrap">
                        <img id="photo-img" src="{{ $teamMember->photo }}" alt="{{ $teamMember->name }}" class="w-28 h-28 rounded-full object-cover border-2 border-gray-200">
                    </div>
                    <div class="flex flex-col gap-2 justify-end pb-1">
                        <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg border border-gray-300">
                            Change Photo
                            <input type="file" id="photo-file" accept="image/jpeg,image/png,image/webp" class="hidden">
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="remove_photo" value="1" id="remove-photo" class="w-3.5 h-3.5 text-red-500 rounded">
                            <span class="text-xs text-red-500 font-medium">Remove photo</span>
                        </label>
                    </div>
                @else
                    <div>
                        <div id="photo-preview" class="hidden mb-2">
                            <img id="photo-img" src="" alt="Preview" class="w-28 h-28 rounded-full object-cover border-2 border-gray-200">
                        </div>
                        <input type="file" id="photo-file" accept="image/jpeg,image/png,image/webp" class="text-sm text-gray-600">
                    </div>
                @endif
            </div>
            <input type="hidden" name="photo_data" id="photo_data">
            <p class="text-xs text-gray-400">Recommended: square, min 400×400px. JPG, PNG or WebP.</p>
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Biography</label>
            <textarea name="bio" rows="8" placeholder="Write the team member's biography here. Separate paragraphs with a blank line." class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy">{{ old('bio', $teamMember->bio) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Business Website URL</label>
            <input type="url" name="website" value="{{ old('website', $teamMember->website) }}" placeholder="https://www.example.ie" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
            <p class="text-xs text-gray-400 mt-1">If set, a "Visit Website" button will appear on their public profile.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', $teamMember->display_order) }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }} class="w-4 h-4 text-navy rounded">
                    <span class="text-sm font-medium text-gray-700">Visible on website</span>
                </label>
            </div>
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit" class="bg-navy text-white px-7 py-2.5 rounded-lg text-sm font-semibold hover:bg-navy-light">Save Changes</button>
            <a href="{{ route('team.show', $teamMember->slug) }}" target="_blank" class="border border-gray-300 text-gray-600 px-5 py-2.5 rounded-lg text-sm hover:bg-gray-50">View Profile</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('photo-file')?.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photo_data').value = e.target.result;
        const img = document.getElementById('photo-img');
        if (img) img.src = e.target.result;
        const preview = document.getElementById('photo-preview');
        if (preview) preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
