<div class="task-type-card individual" onclick="createIndividualTask()">
    <div class="task-card-header">
        <div class="task-card-icon">
            <i class="fas fa-user"></i>
        </div>
        <div class="task-card-stats">
            <span class="task-count">{{ $tugasMandiri ?? 0 }}</span>
            <span class="task-label">Tugas</span>
        </div>
    </div>
    <div class="task-card-content">
        <h3 class="task-type-title">Mandiri</h3>
        <p class="task-type-description">Buat tugas individual untuk pembelajaran mandiri</p>
    </div>
    <div class="task-card-footer">
        <span class="task-card-action">Buat Tugas <i class="fas fa-arrow-right arrow-icon"></i></span>
    </div>
    </div>

<div class="task-type-card group" onclick="createGroupTask()">
    <div class="task-card-header">
        <div class="task-card-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="task-card-stats">
            <span class="task-count">{{ $tugasKelompok ?? 0 }}</span>
            <span class="task-label">Tugas</span>
        </div>
    </div>
    <div class="task-card-content">
        <h3 class="task-type-title">Kelompok</h3>
        <p class="task-type-description">Buat tugas kelompok untuk kolaborasi</p>
    </div>
    <div class="task-card-footer">
        <span class="task-card-action">Buat Tugas <i class="fas fa-arrow-right arrow-icon"></i></span>
    </div>
</div>

<script>
// Define creation functions only if not already defined by parent view
if (typeof createIndividualTask !== 'function') {
    function createIndividualTask() {
        @if(isset($user) && $user->roles_id == 1) // Super Admin
            window.location.href = "{{ route('superadmin.tasks.create.individual') }}";
        @elseif(isset($user) && $user->roles_id == 2) // Admin
            window.location.href = "{{ route('admin.tugas.create', 3) }}";
        @else // Teacher
            window.location.href = "{{ route('teacher.tasks.create.individual') }}";
        @endif
    }
}

if (typeof createGroupTask !== 'function') {
    function createGroupTask() {
        @if(isset($user) && $user->roles_id == 1) // Super Admin
            window.location.href = "{{ route('superadmin.tasks.create.group') }}";
        @elseif(isset($user) && $user->roles_id == 2) // Admin
            window.location.href = "{{ route('admin.tugas.create', 4) }}";
        @else // Teacher
            window.location.href = "{{ route('teacher.tasks.create.group') }}";
        @endif
    }
}
</script>

