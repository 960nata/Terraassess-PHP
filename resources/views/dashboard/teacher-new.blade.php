@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')
@section('page-description', 'Kelola pembelajaran dan kelas Anda')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <x-unified-welcome-section 
        :userName="Auth::user()->name"
        roleName="Guru"
        roleIcon="fas fa-chalkboard-teacher"
        roleColor="green"
        description="Kelola pembelajaran dan kelas Anda"
    />

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-unified-dashboard-card
            title="Manajemen Tugas"
            description="Kelola tugas yang telah Anda buat"
            icon="fas fa-tasks"
            iconColor="blue"
            :href="route('teacher.task-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Ujian"
            description="Buat dan kelola ujian untuk kelas Anda"
            icon="fas fa-clipboard-check"
            iconColor="green"
            :href="route('teacher.exam-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen Materi"
            description="Kelola materi pembelajaran untuk kelas"
            icon="fas fa-file-alt"
            iconColor="purple"
            :href="route('teacher.material-management')"
        />

        <x-unified-dashboard-card
            title="Manajemen IoT"
            description="Monitor data IoT dan perangkat sensor"
            icon="fas fa-microchip"
            iconColor="orange"
            :href="route('teacher.iot-management')"
        />

        <x-unified-dashboard-card
            title="Laporan"
            description="Lihat laporan performa siswa dan kelas"
            icon="fas fa-chart-line"
            iconColor="indigo"
            :href="route('teacher.reports')"
        />

        <x-unified-dashboard-card
            title="Pengaturan"
            description="Konfigurasi profil dan preferensi"
            icon="fas fa-cog"
            iconColor="gray"
            :href="route('teacher.settings')"
        />
    </div>
</div>
@endsection
