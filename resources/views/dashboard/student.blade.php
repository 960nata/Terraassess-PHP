@extends('layouts.unified-layout')

@section('title', 'Student Dashboard')

@section('content')
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-graduation-cap"></i>
                Student Dashboard
            </h1>
            <p class="page-description">Kelola sistem Terra Assessment dengan akses student</p>
        </div>

        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fas fa-exclamation"></i>
            </div>
            <div class="welcome-content">
                <h2 class="welcome-title">Selamat datang, Student!</h2>
                <p class="welcome-description">Sebagai Student, Anda memiliki akses untuk mengelola sistem Terra Assessment.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Row 1 -->

            <a href="{{ route('student.tugas') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="card-title">Tugas Saya</h3>
                <p class="card-description">Lihat dan kerjakan tugas yang diberikan</p>
            </a>

            <!-- Row 2 -->
            <a href="{{ route('student.ujian') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="card-title">Ujian Saya</h3>
                <p class="card-description">Ikuti ujian yang telah dijadwalkan</p>
            </a>

            <a href="{{ route('student.materi') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="card-title">Materi Saya</h3>
                <p class="card-description">Akses materi pembelajaran kelas</p>
            </a>

            <!-- Row 3 -->
            <a href="{{ route('student.iot') }}" class="card">
                <div class="card-icon orange">
                    <i class="fas fa-microscope"></i>
                </div>
                <h3 class="card-title">Penelitian IoT</h3>
                <p class="card-description">Lakukan penelitian menggunakan perangkat IoT</p>
            </a>

            <a href="{{ route('student.class-management') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="card-title">Kelas Saya</h3>
                <p class="card-description">Lihat informasi kelas dan teman sekelas</p>
            </a>

            <!-- Row 4 -->
            <a href="{{ route('student.grades') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="card-title">Nilai Saya</h3>
                <p class="card-description">Lihat nilai tugas dan ujian</p>
            </a>


        </div>

        <div class="system-info">
            <div class="info-section">
                <h3 class="info-title">Hak Akses Siswa</h3>
                <ul class="info-list">
                    <li>Mengakses materi pembelajaran</li>
                    <li>Mengerjakan tugas dan ujian</li>
                    <li>Melakukan penelitian IoT</li>
                    <li>Melihat nilai dan progress</li>
                </ul>
            </div>

            <div class="info-section">
                <h3 class="info-title">Tanggung Jawab</h3>
                <ul class="info-list">
                    <li>Memastikan keamanan sistem</li>
                    <li>Mengelola data pengguna</li>
                    <li>Konfigurasi aplikasi</li>
                    <li>Backup dan maintenance</li>
                </ul>
            </div>
        </div>
@endsection

@push('styles')
<style>
        /* Dashboard Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.5rem;
            }
        }

        /* Card Styling for 4x3 Layout */
        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
        
        /* Framer Motion-style Animation */
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    /* Stagger Animation for Cards */
    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
    .card:nth-child(4) { animation-delay: 0.4s; }
    .card:nth-child(5) { animation-delay: 0.5s; }
    .card:nth-child(6) { animation-delay: 0.6s; }
    .card:nth-child(7) { animation-delay: 0.7s; }
    .card:nth-child(8) { animation-delay: 0.8s; }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Page Header Animation */
    .page-header {
        opacity: 0;
        transform: translateY(-20px);
        animation: fadeInDown 0.5s ease-out 0.2s forwards;
    }
    
    @keyframes fadeInDown {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Welcome Banner Animation */
    .welcome-banner {
        opacity: 0;
        transform: scale(0.95);
        animation: fadeInScale 0.6s ease-out 0.4s forwards;
    }
    
    @keyframes fadeInScale {
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* System Info Animation */
    .system-info {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease-out 0.6s forwards;
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .card-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .card-icon.green {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .card-icon.purple {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .card-icon.orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .card-icon.red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .card-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Mobile Card Adjustments */
        @media (max-width: 768px) {
            .card {
                min-height: 140px;
                padding: 1rem;
            }

            .card-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .card-title {
                font-size: 1rem;
            }

            .card-description {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                min-height: 130px;
                padding: 0.75rem;
            }

            .card-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }

            .card-title {
                font-size: 0.85rem;
                line-height: 1.3;
            }

            .card-description {
                font-size: 0.7rem;
                line-height: 1.4;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects with smooth transitions
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.3)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.2)';
        });
    });
    
    // Add click animation
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = e.offsetX + 'px';
            ripple.style.top = e.offsetY + 'px';
            ripple.style.width = ripple.style.height = '20px';
            ripple.style.pointerEvents = 'none';
            
            this.style.position = 'relative';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add ripple animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
    </script>
@endpush