@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Student Dashboard')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-graduation-cap"></i>
            Student Dashboard
        </h1>
        <p class="page-description">Selamat datang di platform pembelajaran Terra Assessment</p>
    </div>

    <div class="welcome-banner">
        <div class="welcome-icon">
            <i class="fas fa-star"></i>
        </div>
        <div class="welcome-content">
            <h2 class="welcome-title">Selamat datang, {{ Auth::user()->name }}!</h2>
            <p class="welcome-description">Mari mulai perjalanan pembelajaran Anda dengan mengakses materi, tugas, dan ujian yang tersedia.</p>
        </div>
    </div>

    <div class="dashboard-grid">
        <a href="{{ route('student.tugas') }}" class="card">
            <div class="card-icon blue">
                <i class="fas fa-tasks"></i>
            </div>
            <h3 class="card-title">Tugas & Assignment</h3>
            <p class="card-description">Lihat dan kerjakan tugas yang diberikan oleh pengajar</p>
        </a>

        <a href="{{ route('student.materi') }}" class="card">
            <div class="card-icon green">
                <i class="fas fa-book"></i>
            </div>
            <h3 class="card-title">Materi Pembelajaran</h3>
            <p class="card-description">Akses materi pembelajaran dan sumber daya edukatif</p>
        </a>

        <a href="{{ route('student.ujian') }}" class="card">
            <div class="card-icon purple">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3 class="card-title">Ujian & Test</h3>
            <p class="card-description">Ikuti ujian dan test untuk mengukur pemahaman Anda</p>
        </a>

        <a href="{{ route('student.class-management') }}" class="card">
            <div class="card-icon orange">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="card-title">Kelas Saya</h3>
            <p class="card-description">Lihat kelas yang Anda ikuti dan informasi terkait</p>
        </a>

        <a href="{{ route('iot.research-projects') }}" class="card">
            <div class="card-icon teal">
                <i class="fas fa-flask"></i>
            </div>
            <h3 class="card-title">Research Projects</h3>
            <p class="card-description">Jelajahi proyek penelitian dan eksperimen IoT</p>
        </a>

        <a href="{{ route('student.profile') }}" class="card">
            <div class="card-icon red">
                <i class="fas fa-user"></i>
            </div>
            <h3 class="card-title">Profile Saya</h3>
            <p class="card-description">Kelola informasi profil dan pengaturan akun</p>
        </a>
    </div>
@endsection

@section('additional-styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }
    
    .card {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        min-height: 160px;
        display: flex;
        flex-direction: column;
    }
    
    .card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }
    
    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }
    
    .card-icon.blue { background-color: #3b82f6; }
    .card-icon.green { background-color: #10b981; }
    .card-icon.purple { background-color: #8b5cf6; }
    .card-icon.orange { background-color: #f59e0b; }
    .card-icon.teal { background-color: #14b8a6; }
    .card-icon.red { background-color: #ef4444; }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .card-description {
        color: #94a3b8;
        font-size: 0.875rem;
        line-height: 1.5;
        flex-grow: 1;
    }

    .welcome-banner {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .welcome-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .welcome-content {
        flex: 1;
    }

    .welcome-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        line-height: 1.5;
        margin: 0;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }
        
        .card {
            min-height: 120px;
            padding: 0.75rem;
        }
        
        .card-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .card-title {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .card-description {
            font-size: 0.75rem;
            line-height: 1.2;
        }

        .welcome-banner {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .welcome-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .welcome-title {
            font-size: 1.25rem;
        }

        .welcome-description {
            font-size: 0.9rem;
        }
    }
</style>
@endsection
