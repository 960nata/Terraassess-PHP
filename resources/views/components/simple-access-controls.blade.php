@php
use App\Services\SimpleAccessControl;
@endphp

{{-- Simple Access Control for UI Elements --}}

{{-- Create Button --}}
@if(SimpleAccessControl::canPerformAction('create'))
    <button class="btn-primary" onclick="{{ $createAction ?? 'void(0)' }}">
        <i class="fas fa-plus"></i>
        {{ $createText ?? 'Tambah' }}
    </button>
@endif

{{-- Edit Button --}}
@if(SimpleAccessControl::canPerformAction('edit'))
    <button class="btn-edit" onclick="{{ $editAction ?? 'void(0)' }}">
        <i class="fas fa-edit"></i>
        {{ $editText ?? 'Edit' }}
    </button>
@endif

{{-- Delete Button --}}
@if(SimpleAccessControl::canPerformAction('delete'))
    <button class="btn-delete" onclick="{{ $deleteAction ?? 'void(0)' }}">
        <i class="fas fa-trash"></i>
        {{ $deleteText ?? 'Hapus' }}
    </button>
@endif

{{-- Admin Only Actions --}}
@if(SimpleAccessControl::canPerformAction('manage_users'))
    <button class="btn-admin" onclick="{{ $adminAction ?? 'void(0)' }}">
        <i class="fas fa-user-shield"></i>
        {{ $adminText ?? 'Admin Action' }}
    </button>
@endif

{{-- Create Admin Button (Superadmin Only) --}}
@if(SimpleAccessControl::canCreateAdmin())
    <button class="btn-superadmin" onclick="{{ $createAdminAction ?? 'void(0)' }}">
        <i class="fas fa-user-plus"></i>
        {{ $createAdminText ?? 'Buat Admin' }}
    </button>
@endif

{{-- Subject Access Check --}}
@if(isset($subjectId) && !SimpleAccessControl::canAccessSubject($subjectId))
    <div class="access-denied">
        <i class="fas fa-lock"></i>
        <span>Anda tidak memiliki akses ke mata pelajaran ini</span>
    </div>
@endif
