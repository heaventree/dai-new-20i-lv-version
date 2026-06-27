@extends('layouts.admin')
@section('title', 'Edit: ' . $page->title)
@section('page-title', 'Edit Page: ' . $page->title)

@section('page-actions')
<button type="submit" form="main-form" class="header-btn">Save Changes</button>
@endsection

@section('content')
<div class="mb-4"><a href="{{ route('admin.cms-pages.index') }}" class="text-navy hover:underline text-sm">← Back to Pages</a></div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-50 border border-green-300 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow p-8 max-w-4xl">
    <form id="main-form" method="POST" action="{{ route('admin.cms-pages.update', $page) }}" class="space-y-6">
        @csrf

        {{-- Title + Slug --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Page Title *</label>
                <input type="text" name="title" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none"
                       value="{{ old('title', $page->title) }}">
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Slug</label>
                <input type="text" disabled
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-500 font-mono"
                       value="{{ $page->slug }}">
            </div>
        </div>

        {{-- Meta Description --}}
        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Meta Description</label>
            <input type="text" name="meta_description" maxlength="160"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none"
                   value="{{ old('meta_description', $page->meta_description) }}">
            <p class="text-xs text-gray-400 mt-1">Max 160 characters for SEO.</p>
        </div>

        {{-- Published --}}
        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1"
                       class="w-4 h-4"
                       {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                <span class="text-sm font-semibold text-gray-700">Published (visible on site)</span>
            </label>
        </div>

        {{-- Page Content --}}
        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">Page Content (HTML)</label>
            <textarea name="content" rows="6"
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm font-mono focus:border-navy focus:ring-1 focus:ring-navy outline-none">{{ old('content', $page->content) }}</textarea>
            <p class="text-xs text-gray-400 mt-1">HTML is supported. Use &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt; etc.</p>
        </div>

        {{-- ─────────────────────────────────────────────────────── --}}
        {{-- FAQ VISUAL EDITOR (slug = faq only)                    --}}
        {{-- ─────────────────────────────────────────────────────── --}}
        @if($page->slug === 'faq')
        @php
            $faqData   = old('content_json') ? json_decode(old('content_json'), true) : ($page->content_json ?? []);
            $faqCats   = $faqData['categories'] ?? ['General Questions','Assessment Process','Medical Compliance','Reporting & Licensing'];
            $faqItems  = $faqData['items'] ?? [];
        @endphp

        <input type="hidden" name="content_json" id="faq-json-output">

        {{-- Video URL --}}
        <div style="border:1px solid #e5e7eb;border-radius:0.35rem;overflow:hidden">
            <div style="background:#f8f9fa;padding:14px 20px;border-bottom:1px solid #e5e7eb">
                <span style="font-weight:700;font-size:1rem;color:#0d1f3c">FAQ Video</span>
            </div>
            <div style="padding:16px 20px">
                <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">YouTube or Vimeo URL</label>
                <input type="url" id="faq-video-url"
                       value="{{ $faqData['video_url'] ?? '' }}"
                       placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/..."
                       style="width:100%;border:1px solid #d1d5db;border-radius:0.35rem;padding:8px 12px;font-size:1rem;color:#0d1f3c;outline:none">
                <p style="font-size:0.8125rem;color:#9ca3af;margin-top:6px">Paste a YouTube or Vimeo URL. Used as fallback if no video file is uploaded below.</p>
                @if(!empty($faqData['video_url']))
                <div style="margin-top:12px;border-radius:0.35rem;overflow:hidden;max-width:480px">
                    @php
                        $previewUrl = $faqData['video_url'];
                        $embedUrl = '';
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $previewUrl, $m)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                        } elseif (preg_match('/vimeo\.com\/(\d+)/', $previewUrl, $m)) {
                            $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                        }
                    @endphp
                    @if($embedUrl)
                    <iframe src="{{ $embedUrl }}" width="480" height="270" frameborder="0" allowfullscreen
                            style="display:block;border-radius:0.35rem"></iframe>
                    @endif
                </div>
                @endif
                {{-- Divider --}}
                <div style="border-top:1px solid #e5e7eb;margin:16px 0"></div>

                {{-- Video file upload --}}
                <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">Or Upload Video File</label>
                @php
                    $videoFile     = $faqData['video_file'] ?? null;
                    $videoFileName = $faqData['video_file_name'] ?? null;
                    $videoFileSize = $faqData['video_file_size'] ?? 0;
                @endphp
                @if($videoFile)
                <div style="display:flex;align-items:center;gap:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.35rem;padding:10px 14px;margin-bottom:10px">
                    <svg style="width:20px;height:20px;color:#16a34a;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <div style="flex:1;min-width:0">
                        <p style="font-size:0.9375rem;font-weight:600;color:#15803d;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $videoFileName }}</p>
                        <p style="font-size:0.8125rem;color:#6b7280;margin:2px 0 0">{{ number_format($videoFileSize / 1048576, 1) }} MB</p>
                    </div>
                    <form method="POST" action="{{ route('admin.cms-pages.remove-video', $page) }}" style="margin:0"
                          onsubmit="return confirm('Remove uploaded video file?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:1px solid #fca5a5;border-radius:0.35rem;color:#dc2626;padding:5px 12px;font-size:0.8125rem;font-weight:600;cursor:pointer">Remove</button>
                    </form>
                </div>
                @endif
                <form method="POST" action="{{ route('admin.cms-pages.upload-video', $page) }}" enctype="multipart/form-data"
                      style="display:flex;align-items:center;gap:10px">
                    @csrf
                    <input type="file" name="video" accept=".mp4,.webm"
                           style="font-size:0.9375rem;color:#374151">
                    <button type="submit"
                            style="background:#0b3168;color:#fff;border:none;border-radius:0.35rem;padding:8px 16px;font-size:0.875rem;font-weight:600;cursor:pointer;white-space:nowrap">Upload Video</button>
                </form>
                <p style="font-size:0.8125rem;color:#9ca3af;margin-top:6px">MP4 or WebM, max 50 MB. Uploaded video takes priority over the YouTube/Vimeo URL above.</p>
                @error('video')<p style="color:#dc2626;font-size:0.8125rem;margin-top:4px">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Categories --}}
        <div style="border:1px solid #e5e7eb;border-radius:0.35rem;overflow:hidden">
            <div style="background:#f8f9fa;padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:1rem;color:#0d1f3c">Categories</span>
                <button type="button" onclick="addCategory()" class="header-btn" style="padding:6px 14px;font-size:0.875rem">+ Add Category</button>
            </div>
            <div id="categories-list" style="padding:16px 20px;display:flex;flex-wrap:wrap;gap:10px">
                @foreach($faqCats as $cat)
                <div class="cat-pill" style="display:inline-flex;align-items:center;gap:6px;background:#f0f4ff;border:1px solid #c7d4f0;border-radius:0.35rem;padding:5px 10px">
                    <input type="text" class="cat-name-input"
                           value="{{ $cat }}"
                           style="border:none;background:transparent;font-size:0.9375rem;color:#0b3168;font-weight:600;width:{{ max(8, mb_strlen($cat)) }}ch;outline:none"
                           oninput="this.style.width=Math.max(8,this.value.length)+'ch'">
                    <button type="button" onclick="removeCat(this)" style="background:none;border:none;cursor:pointer;color:#94a3b8;line-height:1;padding:0;font-size:1rem">×</button>
                </div>
                @endforeach
            </div>
        </div>

        {{-- FAQ Items --}}
        <div style="border:1px solid #e5e7eb;border-radius:0.35rem;overflow:hidden">
            <div style="background:#f8f9fa;padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:1rem;color:#0d1f3c">FAQ Items</span>
                <span id="faq-count-badge" style="font-size:0.875rem;color:#6b7280">{{ count($faqItems) }} item{{ count($faqItems) !== 1 ? 's' : '' }}</span>
            </div>

            <div id="faq-items-list" style="padding:0">
                @forelse($faqItems as $idx => $item)
                <div class="faq-row" style="border-bottom:1px solid #f3f4f6">
                    <div class="faq-row-header" onclick="toggleFaqRow(this)"
                         style="display:flex;align-items:center;gap:12px;padding:13px 20px;cursor:pointer;user-select:none">
                        <svg class="faq-chevron" style="width:16px;height:16px;color:#94a3b8;flex-shrink:0;transition:transform 0.15s"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="faq-row-preview" style="flex:1;font-size:1rem;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            <span style="color:#0b3168;font-weight:600;font-size:0.875rem;margin-right:8px">{{ $item['category'] ?? '—' }}</span>{{ $item['q'] ?? '(no question)' }}
                        </span>
                        <button type="button" onclick="event.stopPropagation();deleteFaqRow(this)"
                                style="background:none;border:none;cursor:pointer;color:#f87171;font-size:1.25rem;line-height:1;padding:2px 4px;flex-shrink:0">×</button>
                    </div>
                    <div class="faq-row-body" style="display:none;padding:0 20px 18px 48px;background:#fafbfc">
                        <div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;margin-bottom:12px">
                            <div>
                                <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">Category</label>
                                <select class="faq-cat-select" onchange="updateRowPreview(this)"
                                        style="width:100%;border:1px solid #d1d5db;border-radius:0.35rem;padding:8px 10px;font-size:1rem;background:#fff;color:#0d1f3c;outline:none">
                                    @foreach($faqCats as $cat)
                                    <option value="{{ $cat }}" {{ ($item['category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">Question *</label>
                                <input type="text" class="faq-q-input"
                                       value="{{ $item['q'] ?? '' }}"
                                       placeholder="Enter the question…"
                                       oninput="updateRowPreview(this)"
                                       style="width:100%;border:1px solid #d1d5db;border-radius:0.35rem;padding:8px 12px;font-size:1rem;color:#0d1f3c;outline:none">
                            </div>
                        </div>
                        <div>
                            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">Answer *</label>
                            <textarea class="faq-a-input" rows="4" placeholder="Enter the answer…"
                                      style="width:100%;border:1px solid #d1d5db;border-radius:0.35rem;padding:8px 12px;font-size:1rem;color:#0d1f3c;outline:none;resize:vertical">{{ $item['a'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                @empty
                <div id="faq-empty-state" style="padding:32px 20px;text-align:center;color:#9ca3af;font-size:1rem">
                    No FAQ items yet. Click "Add Another FAQ" to get started.
                </div>
                @endforelse
            </div>

            <div style="padding:16px 20px;border-top:1px solid #f3f4f6;background:#fafbfc">
                <button type="button" onclick="addFaqItem()"
                        style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:#fff;color:#0b3168;border:1.5px solid #0b3168;border-radius:0.35rem;font-size:1rem;font-weight:700;cursor:pointer">
                    <svg style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Another FAQ
                </button>
            </div>
        </div>

        @else
        {{-- Raw JSON editor for non-FAQ pages --}}
        @if($page->content_json)
        <div>
            <label class="block font-semibold text-gray-700 mb-1.5 text-sm">
                Structured Content (JSON)
                <span class="ml-2 text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded">{{ Str::startsWith($page->slug, 'service-') ? 'Service' : 'Custom' }}</span>
            </label>
            <textarea name="content_json" rows="20" id="content-json-editor"
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 text-xs font-mono focus:border-navy focus:ring-1 focus:ring-navy outline-none"
                      placeholder='{"key": "value"}'>{{ old('content_json', json_encode($page->content_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Valid JSON only. This controls structured content displayed on the public site.</p>
            @error('content_json')<p class="text-red-600 text-xs mt-1">Invalid JSON: {{ $message }}</p>@enderror
        </div>
        @endif
        @endif

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-navy text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-navy-light transition-colors">Save Changes</button>
            <a href="{{ route('admin.cms-pages.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
const form       = document.getElementById('main-form');
const jsonEditor = document.getElementById('content-json-editor');
if (form && jsonEditor) {
    form.addEventListener('submit', (e) => {
        const val = jsonEditor.value.trim();
        if (val) {
            try { JSON.parse(val); } catch (err) {
                e.preventDefault();
                jsonEditor.classList.add('border-red-500');
                alert('Structured Content contains invalid JSON: ' + err.message);
            }
        }
    });
    jsonEditor.addEventListener('input', () => jsonEditor.classList.remove('border-red-500'));
}

const faqOutput = document.getElementById('faq-json-output');
if (faqOutput) {
    form.addEventListener('submit', serializeFaq);

    var existingVideoFile = @json($faqData['video_file'] ?? null);
    var existingVideoFileName = @json($faqData['video_file_name'] ?? null);
    var existingVideoFileSize = @json($faqData['video_file_size'] ?? null);

    function serializeFaq() {
        var data = {
            categories: getCategories(),
            items:      getFaqItems()
        };
        var videoUrl = document.getElementById('faq-video-url');
        if (videoUrl && videoUrl.value.trim()) {
            data.video_url = videoUrl.value.trim();
        }
        if (existingVideoFile) {
            data.video_file = existingVideoFile;
            data.video_file_name = existingVideoFileName;
            data.video_file_size = existingVideoFileSize;
        }
        faqOutput.value = JSON.stringify(data);
    }

    function getCategories() {
        return Array.from(document.querySelectorAll('.cat-name-input'))
                    .map(i => i.value.trim()).filter(Boolean);
    }

    function getFaqItems() {
        return Array.from(document.querySelectorAll('#faq-items-list .faq-row')).map(row => ({
            category: row.querySelector('.faq-cat-select')?.value  || '',
            q:        row.querySelector('.faq-q-input')?.value.trim()  || '',
            a:        row.querySelector('.faq-a-input')?.value.trim()  || '',
        }));
    }

    function updateCount() {
        const n = document.querySelectorAll('#faq-items-list .faq-row').length;
        const badge = document.getElementById('faq-count-badge');
        if (badge) badge.textContent = n + ' item' + (n !== 1 ? 's' : '');
        const empty = document.getElementById('faq-empty-state');
        if (empty) empty.style.display = n === 0 ? 'block' : 'none';
    }

    window.toggleFaqRow = function(header) {
        const body = header.closest('.faq-row').querySelector('.faq-row-body');
        const chev = header.closest('.faq-row').querySelector('.faq-chevron');
        const open = body.style.display === 'none';
        body.style.display = open ? 'block' : 'none';
        if (chev) chev.style.transform = open ? 'rotate(90deg)' : '';
    };

    window.updateRowPreview = function(input) {
        const row = input.closest('.faq-row');
        const cat = row.querySelector('.faq-cat-select')?.value || '—';
        const q   = row.querySelector('.faq-q-input')?.value   || '(no question)';
        row.querySelector('.faq-row-preview').innerHTML =
            '<span style="color:#0b3168;font-weight:600;font-size:0.875rem;margin-right:8px">' + esc(cat) + '</span>' + esc(q);
    };

    window.addFaqItem = function() {
        const cats = getCategories();
        const list = document.getElementById('faq-items-list');
        const empty = document.getElementById('faq-empty-state');
        if (empty) empty.style.display = 'none';
        const div = document.createElement('div');
        div.className = 'faq-row';
        div.style.borderBottom = '1px solid #f3f4f6';
        div.innerHTML = buildRowHTML(cats[0] || '', '', '', cats);
        list.appendChild(div);
        const body = div.querySelector('.faq-row-body');
        const chev = div.querySelector('.faq-chevron');
        if (body) body.style.display = 'block';
        if (chev) chev.style.transform = 'rotate(90deg)';
        updateCount();
        div.querySelector('.faq-q-input')?.focus();
    };

    window.deleteFaqRow = function(btn) {
        if (!confirm('Remove this FAQ item?')) return;
        btn.closest('.faq-row').remove();
        updateCount();
    };

    window.addCategory = function() {
        const name = prompt('New category name:');
        if (!name || !name.trim()) return;
        const n = name.trim();
        const pill = document.createElement('div');
        pill.className = 'cat-pill';
        pill.style.cssText = 'display:inline-flex;align-items:center;gap:6px;background:#f0f4ff;border:1px solid #c7d4f0;border-radius:0.35rem;padding:5px 10px';
        pill.innerHTML = `<input type="text" class="cat-name-input" value="${ea(n)}"
            style="border:none;background:transparent;font-size:0.9375rem;color:#0b3168;font-weight:600;width:${Math.max(8,n.length)}ch;outline:none"
            oninput="this.style.width=Math.max(8,this.value.length)+'ch'">
            <button type="button" onclick="removeCat(this)" style="background:none;border:none;cursor:pointer;color:#94a3b8;line-height:1;padding:0;font-size:1rem">×</button>`;
        document.getElementById('categories-list').appendChild(pill);
        document.querySelectorAll('.faq-cat-select').forEach(sel => {
            const opt = document.createElement('option');
            opt.value = n; opt.textContent = n;
            sel.appendChild(opt);
        });
    };

    window.removeCat = function(btn) { btn.closest('.cat-pill').remove(); };

    function buildRowHTML(cat, q, a, cats) {
        const opts = cats.map(c => `<option value="${ea(c)}"${c===cat?' selected':''}>${esc(c)}</option>`).join('');
        return `
        <div class="faq-row-header" onclick="toggleFaqRow(this)"
             style="display:flex;align-items:center;gap:12px;padding:13px 20px;cursor:pointer;user-select:none">
            <svg class="faq-chevron" style="width:16px;height:16px;color:#94a3b8;flex-shrink:0;transition:transform 0.15s;transform:rotate(90deg)"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="faq-row-preview" style="flex:1;font-size:1rem;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                <span style="color:#0b3168;font-weight:600;font-size:0.875rem;margin-right:8px">${esc(cat)}</span>${esc(q)||'(no question)'}
            </span>
            <button type="button" onclick="event.stopPropagation();deleteFaqRow(this)"
                    style="background:none;border:none;cursor:pointer;color:#f87171;font-size:1.25rem;line-height:1;padding:2px 4px;flex-shrink:0">×</button>
        </div>
        <div class="faq-row-body" style="display:block;padding:0 20px 18px 48px;background:#fafbfc">
            <div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;margin-bottom:12px">
                <div>
                    <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">Category</label>
                    <select class="faq-cat-select" onchange="updateRowPreview(this)"
                            style="width:100%;border:1px solid #d1d5db;border-radius:0.35rem;padding:8px 10px;font-size:1rem;background:#fff;color:#0d1f3c;outline:none">
                        ${opts}
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">Question *</label>
                    <input type="text" class="faq-q-input" value="${ea(q)}" placeholder="Enter the question…"
                           oninput="updateRowPreview(this)"
                           style="width:100%;border:1px solid #d1d5db;border-radius:0.35rem;padding:8px 12px;font-size:1rem;color:#0d1f3c;outline:none">
                </div>
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:5px">Answer *</label>
                <textarea class="faq-a-input" rows="4" placeholder="Enter the answer…"
                          style="width:100%;border:1px solid #d1d5db;border-radius:0.35rem;padding:8px 12px;font-size:1rem;color:#0d1f3c;outline:none;resize:vertical">${esc(a)}</textarea>
            </div>
        </div>`;
    }

    function esc(s)  { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function ea(s)   { return String(s).replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

    updateCount();
}
</script>
@endpush

@endsection
