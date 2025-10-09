@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - Student Dashboard')
@section('page-title', 'Student Dashboard')
@section('page-description', 'Akses pembelajaran dan penelitian IoT')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <x-unified-welcome-section 
        :userName="Auth::user()->name"
        roleName="Siswa"
        roleIcon="fas fa-graduation-cap"
        roleColor="blue"
        description="Akses pembelajaran dan penelitian IoT"
    />

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-unified-dashboard-card
            title="Tugas Saya"
            description="Lihat dan kerjakan tugas yang diberikan"
            icon="fas fa-tasks"
            iconColor="blue"
            :href="route('student.tugas')"
        />

        <x-unified-dashboard-card
            title="Ujian Saya"
            description="Ikuti ujian yang telah dijadwalkan"
            icon="fas fa-clipboard-check"
            iconColor="green"
            :href="route('student.ujian')"
        />

        <x-unified-dashboard-card
            title="Materi Saya"
            description="Akses materi pembelajaran kelas"
            icon="fas fa-file-alt"
            iconColor="purple"
            :href="route('student.materi')"
        />

        <x-unified-dashboard-card
            title="Penelitian IoT"
            description="Lakukan penelitian menggunakan perangkat IoT"
            icon="fas fa-microscope"
            iconColor="orange"
            :href="route('student.iot')"
        />

        <x-unified-dashboard-card
            title="Nilai Saya"
            description="Lihat nilai tugas dan ujian"
            icon="fas fa-chart-line"
            iconColor="indigo"
            :href="route('student.reports')"
        />

        <x-unified-dashboard-card
            title="Pengaturan"
            description="Konfigurasi profil dan preferensi"
            icon="fas fa-cog"
            iconColor="gray"
            :href="route('student.settings')"
        />
    </div>
</div>
@endsection