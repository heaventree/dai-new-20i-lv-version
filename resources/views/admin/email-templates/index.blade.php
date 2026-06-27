@extends('layouts.admin')
@section('title', 'Email Templates')
@section('page-title', 'Email Templates')

@section('content')

{{-- Subtitle --}}
<p style="color:#6b7280;margin-bottom:20px;font-size:0.9375rem">
    Customise the subject and content of notification emails for each form type
</p>

{{-- Flash --}}
@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:0.875rem;font-weight:600">
    {{ session('success') }}
</div>
@endif

{{-- Info banner --}}
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px 18px;margin-bottom:24px;font-size:0.875rem;color:#1e40af;line-height:1.55">
    <strong>How it works:</strong> Each template defines the subject and body for notifications sent when a form is submitted.
    The body is wrapped in the standard DAI branded email wrapper automatically.
    Leaving a template as "default" uses the built-in template.
</div>

{{-- Tab bar --}}
<div style="display:flex;gap:4px;border-bottom:2px solid #e5e7eb;margin-bottom:28px">
    @foreach($tabOrder as $slug)
    @php $meta = $tabMeta[$slug]; $isActive = $activeTab === $slug; @endphp
    <a href="{{ route('admin.email-templates.index', ['tab' => $slug]) }}"
       style="padding:9px 18px;font-size:0.875rem;font-weight:600;border-radius:6px 6px 0 0;border:1.5px solid transparent;border-bottom:none;text-decoration:none;transition:background 0.15s,color 0.15s;
              {{ $isActive
                  ? 'background:#fff;border-color:#e5e7eb;color:#111827;margin-bottom:-2px;border-bottom:2px solid #fff'
                  : 'color:#6b7280' }}"
       onmouseover="{{ $isActive ? '' : "this.style.color='#374151';this.style.background='#f9fafb'" }}"
       onmouseout="{{ $isActive ? '' : "this.style.color='#6b7280';this.style.background=''" }}">
        {{ $meta['label'] }}
    </a>
    @endforeach
</div>

{{-- Active tab panel --}}
@php
    $slug     = $activeTab;
    $meta     = $tabMeta[$slug];
    $template = $templates->get($slug);
@endphp

@if(!$template)
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:32px;text-align:center;color:#6b7280">
    Template not found in database. Run <code>php artisan db:seed --force</code> to create it.
</div>
@else
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:28px 32px">

    {{-- Template heading row --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:24px">
        <div>
            <h2 style="font-family:'Manrope',sans-serif;font-size:1.125rem;font-weight:700;color:#111827;margin:0 0 4px">
                {{ $meta['heading'] }}
            </h2>
            <p style="font-size:0.875rem;color:#6b7280;margin:0">{{ $meta['description'] }}</p>
        </div>
        {{-- Status badge --}}
        <span style="flex-shrink:0;margin-top:3px;display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;letter-spacing:0.02em;
            {{ $template->is_default
                ? 'background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0'
                : 'background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0' }}">
            @if($template->is_default)
            Using Default
            @else
            <svg style="width:10px;height:10px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Customised
            @endif
        </span>
    </div>

    {{-- Edit form --}}
    <form method="POST" action="{{ route('admin.email-templates.update', $template) }}">
        @csrf

        {{-- Subject --}}
        <div style="margin-bottom:22px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:8px">
                Subject Line
            </label>
            <input type="text" name="subject"
                   value="{{ old('subject', $template->subject) }}"
                   required
                   style="width:100%;box-sizing:border-box;border:1.5px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:0.9375rem;color:#111827;outline:none;transition:border-color 0.15s"
                   onfocus="this.style.borderColor='#132d5e'" onblur="this.style.borderColor='#d1d5db'">
            <p style="margin:7px 0 0;font-size:0.8125rem;color:#9ca3af">
                You can use variables like
                @foreach($meta['variables'] as $v)
                <code style="background:#f3f4f6;padding:1px 5px;border-radius:4px;font-size:0.75rem;color:#374151">{{ $v }}</code>{{ !$loop->last ? ' ' : '' }}
                @endforeach
                in the subject.
            </p>
        </div>

        {{-- Body --}}
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:8px">
                Email Body (HTML)
            </label>
            <textarea name="body" rows="18" required
                      style="width:100%;box-sizing:border-box;border:1.5px solid #d1d5db;border-radius:8px;padding:12px 14px;font-size:0.8125rem;font-family:'Fira Code','Cascadia Code','Menlo',monospace;color:#1e293b;line-height:1.6;resize:vertical;outline:none;transition:border-color 0.15s"
                      onfocus="this.style.borderColor='#132d5e'" onblur="this.style.borderColor='#d1d5db'">{{ old('body', $template->body) }}</textarea>
            <p style="margin:7px 0 0;font-size:0.8125rem;color:#9ca3af">
                HTML is supported. The body is automatically wrapped in the DAI branded email layout when sent.
            </p>
        </div>

        {{-- Active toggle --}}
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:24px">
            <div style="position:relative;width:42px;height:24px;flex-shrink:0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                       style="position:absolute;opacity:0;width:0;height:0"
                       id="toggle-{{ $slug }}"
                       onchange="this.parentElement.parentElement.querySelector('.toggle-track').style.background=this.checked?'#132d5e':'#d1d5db';this.parentElement.parentElement.querySelector('.toggle-thumb').style.left=this.checked?'20px':'2px'">
                <div class="toggle-track" style="position:absolute;inset:0;border-radius:12px;transition:background 0.2s;background:{{ $template->is_active ? '#132d5e' : '#d1d5db' }}"></div>
                <div class="toggle-thumb" style="position:absolute;top:2px;left:{{ $template->is_active ? '20px' : '2px' }};width:20px;height:20px;border-radius:50%;background:#fff;transition:left 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.2)"></div>
            </div>
            <label for="toggle-{{ $slug }}" style="font-size:0.875rem;font-weight:600;color:#374151;cursor:pointer">
                Active — send this email when the form is submitted
            </label>
        </label>

        <div style="display:flex;align-items:center;gap:12px">
            <button type="submit"
                    style="padding:10px 24px;background:#132d5e;color:#fff;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;transition:background 0.15s"
                    onmouseover="this.style.background='#1a3f7a'" onmouseout="this.style.background='#132d5e'">
                Save Changes
            </button>
            @if(!$template->is_default)
            <form method="POST" action="{{ route('admin.email-templates.update', $template) }}" style="display:inline">
                @csrf
                <input type="hidden" name="subject" value="{{ $template->subject }}">
                <input type="hidden" name="body" value="{{ $template->body }}">
                <input type="hidden" name="reset_to_default" value="1">
                <button type="submit"
                        style="padding:10px 18px;background:#fff;color:#6b7280;border:1.5px solid #d1d5db;border-radius:8px;font-size:0.875rem;font-weight:600;cursor:pointer;transition:background 0.15s"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                    Reset to Default
                </button>
            </form>
            @endif
        </div>
    </form>
</div>
@endif

@endsection
