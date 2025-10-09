@php
use App\Services\GranularRbacService;
@endphp

{{-- Create Button --}}
@if(GranularRbacService::canCreate($component))
    <button type="button" class="btn btn-primary" onclick="{{ $createAction ?? 'showCreateModal()' }}">
        <i class="fas fa-plus"></i> {{ $createText ?? 'Tambah' }}
    </button>
@endif

{{-- Edit Button --}}
@if(GranularRbacService::canEdit($component))
    @php
        $editOnclick = $editAction ?? ('editItem(' . ($itemId ?? 'null') . ')');
    @endphp
    <button type="button" class="btn btn-warning btn-sm" onclick="{{ $editOnclick }}">
        <i class="fas fa-edit"></i> {{ $editText ?? 'Edit' }}
    </button>
@endif

{{-- Delete Button --}}
@if(GranularRbacService::canDelete($component))
    @php
        $deleteOnclick = $deleteAction ?? ('deleteItem(' . ($itemId ?? 'null') . ')');
    @endphp
    <button type="button" class="btn btn-danger btn-sm" onclick="{{ $deleteOnclick }}">
        <i class="fas fa-trash"></i> {{ $deleteText ?? 'Hapus' }}
    </button>
@endif

{{-- View All Button (for Superadmin/Admin) --}}
@if(GranularRbacService::canManageAll($component))
    <button type="button" class="btn btn-info btn-sm" onclick="{{ $viewAllAction ?? 'viewAllItems()' }}">
        <i class="fas fa-eye"></i> {{ $viewAllText ?? 'Lihat Semua' }}
    </button>
@endif

{{-- Export Button --}}
@if(GranularRbacService::hasPermission($component, 'export'))
    <button type="button" class="btn btn-success btn-sm" onclick="{{ $exportAction ?? 'exportData()' }}">
        <i class="fas fa-download"></i> {{ $exportText ?? 'Ekspor' }}
    </button>
@endif

{{-- Send Notification Button --}}
@if(GranularRbacService::hasPermission($component, 'send'))
    <button type="button" class="btn btn-primary btn-sm" onclick="{{ $sendAction ?? 'sendNotification()' }}">
        <i class="fas fa-paper-plane"></i> {{ $sendText ?? 'Kirim' }}
    </button>
@endif