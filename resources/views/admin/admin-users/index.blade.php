@extends('layouts.admin')
@section('title', 'Admin Users')
@section('page-title', 'Admin Users')

@section('page-actions')
<button onclick="document.getElementById('modal-add').showModal()" class="header-btn">
    <svg style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add Admin User
</button>
@endsection

@section('content')
<div style="max-width:860px">

<p style="color:#6b7280;font-size:0.9375rem;margin:0 0 20px">Manage who has access to this admin panel</p>

<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden">
    <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:8px">
        <svg style="width:16px;height:16px;color:#132d5e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <span style="font-size:0.9375rem;font-weight:600;color:#111827">Admin Accounts</span>
    </div>

    @forelse($users as $user)
    @php $isMe = session('admin_id') == $user->id; @endphp
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f9fafb">
        <div style="display:flex;align-items:center;gap:14px">
            <div style="width:40px;height:40px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg style="width:18px;height:18px;color:#132d5e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:0.9375rem;font-weight:600;color:#111827">{{ $user->username }}</span>
                    @if($isMe)
                    <span style="font-size:0.7rem;font-weight:700;padding:2px 7px;border-radius:10px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0">You</span>
                    @endif
                    <span style="font-size:0.7rem;font-weight:700;padding:2px 7px;border-radius:10px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;text-transform:capitalize">{{ $user->role ?? 'admin' }}</span>
                </div>
                <p style="font-size:0.8125rem;color:#6b7280;margin:2px 0 0">
                    {{ $user->email ?? '—' }}
                    <span style="color:#d1d5db;margin:0 4px">·</span>
                    Added {{ $user->created_at?->format('j M Y') ?? '—' }}
                </p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <button onclick="openEdit({{ $user->id }}, '{{ addslashes($user->username) }}', '{{ addslashes($user->email ?? '') }}')"
                    style="display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border:1.5px solid #d1d5db;border-radius:7px;background:#fff;font-size:0.8125rem;font-weight:600;color:#374151;cursor:pointer">
                <svg style="width:13px;height:13px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </button>
            @if(!$isMe)
            <button onclick="openDelete({{ $user->id }}, '{{ addslashes($user->username) }}')"
                    style="display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border:1.5px solid #fca5a5;border-radius:7px;background:#fff;font-size:0.8125rem;font-weight:600;color:#dc2626;cursor:pointer">
                <svg style="width:13px;height:13px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:48px;color:#9ca3af">No admin users found.</div>
    @endforelse
</div>

{{-- ADD MODAL --}}
<dialog id="modal-add" style="border:none;border-radius:14px;padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.18);max-width:440px;width:100%">
    <div style="padding:24px 28px;border-bottom:1px solid #f3f4f6">
        <h2 style="font-family:'Manrope',sans-serif;font-size:1.1rem;font-weight:700;color:#111827;margin:0 0 4px">Add Admin User</h2>
        <p style="font-size:0.875rem;color:#6b7280;margin:0">Create a new administrator account.</p>
    </div>
    <form method="POST" action="{{ route('admin.admin-users.store') }}" style="padding:24px 28px">
        @csrf
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">Username</label>
            <input type="text" name="username" required autocomplete="off"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box"
                   placeholder="e.g. admin">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">
                Email Address <span style="font-weight:400;color:#9ca3af;font-size:0.8125rem">(optional)</span>
            </label>
            <input type="email" name="email" autocomplete="off"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box"
                   placeholder="admin@example.com">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">Password</label>
            <input type="password" name="password" required autocomplete="new-password" minlength="8"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box"
                   placeholder="Min. 8 characters">
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">Confirm Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box"
                   placeholder="Re-enter password">
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end">
            <button type="button" onclick="document.getElementById('modal-add').close()"
                    style="padding:9px 18px;border:1.5px solid #d1d5db;border-radius:8px;background:#fff;font-size:0.875rem;font-weight:600;color:#374151;cursor:pointer">
                Cancel
            </button>
            <button type="submit"
                    style="padding:9px 18px;background:#132d5e;color:#fff;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer">
                Create User
            </button>
        </div>
    </form>
</dialog>

{{-- EDIT MODAL --}}
<dialog id="modal-edit" style="border:none;border-radius:14px;padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.18);max-width:440px;width:100%">
    <div style="padding:24px 28px;border-bottom:1px solid #f3f4f6">
        <h2 style="font-family:'Manrope',sans-serif;font-size:1.1rem;font-weight:700;color:#111827;margin:0 0 4px">Edit Admin User</h2>
        <p style="font-size:0.875rem;color:#6b7280;margin:0">Update username, email, or set a new password.</p>
    </div>
    <form id="edit-form" method="POST" style="padding:24px 28px">
        @csrf
        @method('PUT')
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">Username</label>
            <input type="text" name="username" id="edit-username" required autocomplete="off"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">
                Email Address <span style="font-weight:400;color:#9ca3af;font-size:0.8125rem">(optional)</span>
            </label>
            <input type="email" name="email" id="edit-email" autocomplete="off"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box"
                   placeholder="admin@example.com">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">
                New Password <span style="font-weight:400;color:#9ca3af;font-size:0.8125rem">(leave blank to keep current)</span>
            </label>
            <input type="password" name="password" autocomplete="new-password"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box"
                   placeholder="Leave blank to keep current">
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:6px">Confirm New Password</label>
            <input type="password" name="password_confirmation" autocomplete="new-password"
                   style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:9px 13px;font-size:0.9375rem;box-sizing:border-box"
                   placeholder="Leave blank if not changing">
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end">
            <button type="button" onclick="document.getElementById('modal-edit').close()"
                    style="padding:9px 18px;border:1.5px solid #d1d5db;border-radius:8px;background:#fff;font-size:0.875rem;font-weight:600;color:#374151;cursor:pointer">
                Cancel
            </button>
            <button type="submit"
                    style="padding:9px 18px;background:#132d5e;color:#fff;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer">
                Save Changes
            </button>
        </div>
    </form>
</dialog>

{{-- DELETE MODAL --}}
<dialog id="modal-delete" style="border:none;border-radius:14px;padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.18);max-width:420px;width:100%">
    <div style="padding:24px 28px">
        <h2 style="font-family:'Manrope',sans-serif;font-size:1.1rem;font-weight:700;color:#111827;margin:0 0 8px">Delete admin user?</h2>
        <p style="font-size:0.9375rem;color:#6b7280;margin:0 0 24px">
            This will permanently remove <strong id="delete-name"></strong> from the admin panel. This cannot be undone.
        </p>
        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="document.getElementById('modal-delete').close()"
                        style="padding:9px 18px;border:1.5px solid #d1d5db;border-radius:8px;background:#fff;font-size:0.875rem;font-weight:600;color:#374151;cursor:pointer">
                    Cancel
                </button>
                <button type="submit"
                        style="padding:9px 18px;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer">
                    Delete
                </button>
            </div>
        </form>
    </div>
</dialog>

</div>
@endsection

@push('scripts')
<script>
function openEdit(id, username, email) {
    document.getElementById('edit-username').value = username;
    document.getElementById('edit-email').value    = email;
    document.getElementById('edit-form').action    = '/admin/admin-users/' + id;
    document.getElementById('modal-edit').showModal();
}
function openDelete(id, username) {
    document.getElementById('delete-name').textContent = username;
    document.getElementById('delete-form').action = '/admin/admin-users/' + id;
    document.getElementById('modal-delete').showModal();
}
['modal-add','modal-edit','modal-delete'].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener('click', e => { if (e.target === el) el.close(); });
});
</script>
@endpush
