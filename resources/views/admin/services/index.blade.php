@extends('layouts.admin')
@section('title', 'Services')
@section('page-title', 'Services')

@section('content')
<p style="color:#6b7280;margin-bottom:24px;font-size:0.9375rem">
    Edit content for each condition page. Hover over the card image to upload a custom photo per service.
</p>

@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:0.9375rem;font-weight:600">
    {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
@foreach($services as $service)
@php
    $json      = $service->content_json ?? [];
    $summary   = $json['summary'] ?? $service->meta_description ?? '';
    $summary   = mb_strlen($summary) > 90 ? mb_substr($summary, 0, 90) . '…' : $summary;
    $img       = $service->image_path ?: null;
    $hasCustom = !empty($service->image_path);
@endphp
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden">

    {{-- Image area with hover-to-upload --}}
    <div style="position:relative;height:180px;background:#e5e7eb;overflow:hidden" class="service-img-wrap">
        @if($img)
        <img src="{{ asset(ltrim($img, '/')) }}?v={{ $service->updated_at?->timestamp }}" alt="{{ $service->title }}"
             style="width:100%;height:100%;object-fit:cover;display:block">
        @else
        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#cbd5e1,#94a3b8)">
            <svg style="width:40px;height:40px;color:#64748b;opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        @endif

        {{-- Custom badge --}}
        @if($hasCustom)
        <span style="position:absolute;top:10px;left:10px;background:#16a34a;color:#fff;font-size:0.7rem;font-weight:700;padding:3px 8px;border-radius:20px;display:flex;align-items:center;gap:4px">
            <svg style="width:10px;height:10px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            Custom
        </span>
        @endif

        {{-- Hover upload overlay --}}
        <form method="POST"
              action="{{ route('admin.services.upload-image', $service) }}"
              enctype="multipart/form-data"
              class="img-upload-form"
              style="position:absolute;inset:0;background:rgba(0,0,0,0.55);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;opacity:0;transition:opacity 0.2s;cursor:pointer">
            @csrf
            <svg style="width:28px;height:28px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span style="color:#fff;font-size:0.8125rem;font-weight:600">Upload Photo</span>
            <input type="file" name="image" accept="image/*"
                   style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%"
                   onchange="this.closest('form').submit()">
        </form>
    </div>

    {{-- Card body --}}
    <div style="padding:14px 16px 16px">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:6px">
            <h3 style="font-size:0.9375rem;font-weight:700;color:#111827;font-family:'Manrope',sans-serif;line-height:1.3;margin:0">
                {{ $service->title }}
            </h3>
            {{-- Edit icon --}}
            <a href="{{ route('admin.cms-pages.edit', $service) }}"
               title="Edit full content"
               style="flex-shrink:0;color:#9ca3af;transition:color 0.15s"
               onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#9ca3af'">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
        </div>

        @if($summary)
        <p style="font-size:0.8125rem;color:#6b7280;line-height:1.5;margin:0 0 14px">{{ $summary }}</p>
        @endif

        <div style="display:flex;align-items:center;gap:8px">
            {{-- Edit Content --}}
            <a href="{{ route('admin.cms-pages.edit', $service) }}"
               style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px 14px;border-radius:7px;border:1.5px solid #d1d5db;font-size:0.875rem;font-weight:600;color:#374151;text-decoration:none;transition:background 0.15s,border-color 0.15s"
               onmouseover="this.style.background='#f9fafb';this.style.borderColor='#9ca3af'"
               onmouseout="this.style.background='';this.style.borderColor='#d1d5db'">
                Edit Content
            </a>

            {{-- Upload image button --}}
            <form method="POST"
                  action="{{ route('admin.services.upload-image', $service) }}"
                  enctype="multipart/form-data"
                  style="position:relative">
                @csrf
                <button type="button"
                        title="Upload image"
                        onclick="this.nextElementSibling.click()"
                        style="padding:8px 10px;border-radius:7px;border:1.5px solid #d1d5db;background:#fff;cursor:pointer;display:flex;align-items:center;color:#6b7280;transition:background 0.15s,color 0.15s"
                        onmouseover="this.style.background='#f9fafb';this.style.color='#374151'"
                        onmouseout="this.style.background='#fff';this.style.color='#6b7280'">
                    <svg style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </button>
                <input type="file" name="image" accept="image/*" style="display:none"
                       onchange="this.closest('form').submit()">
            </form>

            {{-- Remove custom image (only when a custom image exists) --}}
            @if($hasCustom)
            <form method="POST" action="{{ route('admin.services.remove-image', $service) }}"
                  onsubmit="return confirm('Remove the custom image for \'{{ addslashes($service->title) }}\'? The default placeholder will be shown instead.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        title="Remove custom image"
                        style="padding:8px 10px;border-radius:7px;border:1.5px solid #fed7aa;background:#fff7ed;cursor:pointer;display:flex;align-items:center;color:#c2410c;transition:background 0.15s"
                        onmouseover="this.style.background='#ffedd5'"
                        onmouseout="this.style.background='#fff7ed'">
                    <svg style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </button>
            </form>
            @endif

            {{-- Delete service --}}
            <button type="button"
                    title="Delete service"
                    onclick="confirmDelete('{{ $service->id }}', '{{ addslashes($service->title) }}')"
                    style="padding:8px 10px;border-radius:7px;border:1.5px solid #fca5a5;background:#fff;cursor:pointer;display:flex;align-items:center;color:#dc2626;transition:background 0.15s"
                    onmouseover="this.style.background='#fef2f2'"
                    onmouseout="this.style.background='#fff'">
                <svg style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>

            {{-- Hidden delete form (submitted by JS) --}}
            <form id="delete-form-{{ $service->id }}" method="POST"
                  action="{{ route('admin.services.destroy', $service) }}" style="display:none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endforeach
</div>

{{-- Delete confirmation dialog --}}
<dialog id="delete-dialog" style="border:none;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,0.2);padding:0;width:420px;max-width:90vw">
    <div style="padding:28px 28px 24px">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
            <div style="width:44px;height:44px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg style="width:22px;height:22px;color:#dc2626" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 style="font-family:'Manrope',sans-serif;font-size:1.0625rem;font-weight:700;color:#111827;margin:0 0 3px">Delete Service</h3>
                <p style="font-size:0.875rem;color:#6b7280;margin:0">This will permanently remove the service and its content.</p>
            </div>
        </div>
        <p id="delete-dialog-msg" style="font-size:0.9375rem;color:#374151;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:12px 14px;margin:0 0 22px"></p>
        <div style="display:flex;gap:10px;justify-content:flex-end">
            <button type="button" onclick="document.getElementById('delete-dialog').close()"
                    style="padding:10px 20px;border-radius:8px;border:1.5px solid #d1d5db;background:#fff;font-size:0.9375rem;font-weight:600;color:#374151;cursor:pointer">
                Cancel
            </button>
            <button type="button" id="delete-confirm-btn"
                    style="padding:10px 20px;border-radius:8px;border:none;background:#dc2626;font-size:0.9375rem;font-weight:700;color:#fff;cursor:pointer">
                Delete Service
            </button>
        </div>
    </div>
</dialog>

@endsection

@push('scripts')
<script>
    // Show upload overlay on image hover
    document.querySelectorAll('.service-img-wrap').forEach(function(wrap) {
        const overlay = wrap.querySelector('.img-upload-form');
        if (!overlay) return;
        wrap.addEventListener('mouseenter', function() { overlay.style.opacity = '1'; });
        wrap.addEventListener('mouseleave', function() { overlay.style.opacity = '0'; });
    });

    // Delete confirmation
    function confirmDelete(id, title) {
        const dialog = document.getElementById('delete-dialog');
        const msg    = document.getElementById('delete-dialog-msg');
        const btn    = document.getElementById('delete-confirm-btn');
        msg.textContent = 'Are you sure you want to delete "' + title + '"? This cannot be undone.';
        btn.onclick = function() {
            document.getElementById('delete-form-' + id).submit();
        };
        dialog.showModal();
    }

    // Close dialog on backdrop click
    document.getElementById('delete-dialog').addEventListener('click', function(e) {
        if (e.target === this) this.close();
    });
</script>
@endpush
