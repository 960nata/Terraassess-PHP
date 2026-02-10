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

        <div class="dashboard-grid font-poppins">
            <!-- Row 1 -->
            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('student.tugas') }}" class="flex flex-col h-full">
                    <div class="card-icon blue mb-4">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Tugas Saya</h3>
                    <p class="card-description text-gray-400 text-sm">Lihat dan kerjakan tugas yang diberikan</p>
                </a>
            </x-modern-card>

            <!-- Row 2 -->
            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('student.ujian') }}" class="flex flex-col h-full">
                    <div class="card-icon green mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Ujian Saya</h3>
                    <p class="card-description text-gray-400 text-sm">Ikuti ujian yang telah dijadwalkan</p>
                </a>
            </x-modern-card>

            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('student.materi') }}" class="flex flex-col h-full">
                    <div class="card-icon purple mb-4">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Materi Saya</h3>
                    <p class="card-description text-gray-400 text-sm">Akses materi pembelajaran kelas</p>
                </a>
            </x-modern-card>

            <!-- Row 3 -->
            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('student.iot') }}" class="flex flex-col h-full">
                    <div class="card-icon orange mb-4">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Penelitian IoT</h3>
                    <p class="card-description text-gray-400 text-sm">Lakukan penelitian menggunakan perangkat IoT</p>
                </a>
            </x-modern-card>

            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('student.class-management') }}" class="flex flex-col h-full">
                    <div class="card-icon blue mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Kelas Saya</h3>
                    <p class="card-description text-gray-400 text-sm">Lihat informasi kelas dan teman sekelas</p>
                </a>
            </x-modern-card>

            <!-- Row 4 -->
            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('student.grades') }}" class="flex flex-col h-full">
                    <div class="card-icon green mb-4">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Nilai Saya</h3>
                    <p class="card-description text-gray-400 text-sm">Lihat nilai tugas dan ujian</p>
                </a>
            </x-modern-card>
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