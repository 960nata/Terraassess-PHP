@php
    $roleId = $roleId ?? Auth()->user()->roles_id;
@endphp

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-menu">
        @include('components.role-sidebar', ['roleId' => $roleId])
    </div>
</nav>
