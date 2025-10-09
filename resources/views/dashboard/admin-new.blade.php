@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-description', 'Kelola sistem Terra Assessment')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <x-unified-welcome-section 
        :userName="Auth::user()->name"
        roleName="Admin"
        roleIcon="fas fa-user-shield"
        roleColor="blue"
        description="Kelola sistem Terra Assessment"
    />

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-unified-dashboard-card
            title="Push Notifikasi"
            description="Kirim notifikasi ke pengguna dan kelas"
            icon="fas fa-bell"
            iconColor="blue"
            :href="route('admin.push-notification')"
        />

        <x-unified-dashboard-card
            title="Manajemen IoT"
            description="Monitor perangkat IoT dan data sensor"
            icon="fas fa-microchip"
            iconColor="green"
            :href="route('admin.iot-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Tugas"
            description="Kelola tugas per kelas dan mata pelajaran"
            icon="fas fa-tasks"
            iconColor="purple"
            :href="route('admin.task-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Ujian"
            description="Buat dan kelola ujian untuk kelas"
            icon="fas fa-clipboard-check"
            iconColor="orange"
            :href="route('admin.exam-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Pengguna"
            description="Kelola pengguna sistem (Guru, Siswa)"
            icon="fas fa-users"
            iconColor="indigo"
            :href="route('admin.user-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Kelas"
            description="Buat dan kelola kelas di sistem"
            icon="fas fa-chalkboard"
            iconColor="teal"
            :href="route('admin.class-management')"
        />

        <x-unified-dashboard-card
            title="Mata Pelajaran"
            description="Tambah dan kelola mata pelajaran"
            icon="fas fa-book"
            iconColor="pink"
            :href="route('admin.subject-management')"
        />

        <x-unified-dashboard-card
            title="Materi"
            description="Kelola materi pembelajaran"
            icon="fas fa-file-alt"
            iconColor="cyan"
            :href="route('admin.material-management')"
        />

        <x-unified-dashboard-card
            title="Laporan"
            description="Lihat laporan dan analitik"
            icon="fas fa-chart-line"
            iconColor="red"
            :href="route('admin.reports')"
        />
    </div>
</div>
@endsection