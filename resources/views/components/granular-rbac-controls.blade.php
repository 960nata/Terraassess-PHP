@php
use App\Services\GranularRbacService;
@endphp

{{-- Create Button --}}
@if(GranularRbacService::canCreate($component))
    <button type="button" class="btn btn-primary" onclick="{{ $createAction ?? 'showCreateModal()' }}">
        <i class="fas fa-plus"></i> {{ $createText ?? 'Tambah' }}
    </button>
@endif

{{-- Edit Button - REMOVED as requested --}}
{{-- Delete Button - REMOVED as requested --}}
{{-- View All Button - REMOVED as requested --}}

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