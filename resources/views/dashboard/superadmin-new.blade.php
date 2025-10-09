@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')
@section('page-description', 'Kontrol penuh atas sistem Terra Assessment')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <x-unified-welcome-section 
        :userName="Auth::user()->name"
        roleName="Super Admin"
        roleIcon="fas fa-crown"
        roleColor="yellow"
        description="Kontrol penuh atas sistem Terra Assessment"
    />

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-unified-dashboard-card
            title="Push Notifikasi"
            description="Kirim notifikasi ke semua pengguna"
            icon="fas fa-bell"
            iconColor="blue"
            :href="route('superadmin.push-notification')"
        />

        <x-unified-dashboard-card
            title="Manajemen IoT"
            description="Daftarkan dan monitor perangkat IoT"
            icon="fas fa-microchip"
            iconColor="green"
            :href="route('superadmin.iot-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Tugas"
            description="Kelola tugas per kelas"
            icon="fas fa-tasks"
            iconColor="purple"
            :href="route('superadmin.task-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Ujian"
            description="Buat dan kelola ujian"
            icon="fas fa-clipboard-check"
            iconColor="orange"
            :href="route('superadmin.exam-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Pengguna"
            description="Kelola semua pengguna sistem"
            icon="fas fa-users"
            iconColor="indigo"
            :href="route('superadmin.user-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Kelas"
            description="Buat dan kelola semua kelas"
            icon="fas fa-chalkboard"
            iconColor="teal"
            :href="route('superadmin.class-management')"
        />

        <x-unified-dashboard-card
            title="Mata Pelajaran"
            description="Tambah dan kelola mata pelajaran"
            icon="fas fa-book"
            iconColor="pink"
            :href="route('superadmin.subject-management')"
        />

        <x-unified-dashboard-card
            title="Materi"
            description="Kelola materi pembelajaran"
            icon="fas fa-file-alt"
            iconColor="cyan"
            :href="route('superadmin.material-management')"
        />

        <x-unified-dashboard-card
            title="Laporan"
            description="Lihat laporan dan analitik sistem"
            icon="fas fa-chart-line"
            iconColor="red"
            :href="route('superadmin.reports')"
        />
    </div>
</div>
@endsection
