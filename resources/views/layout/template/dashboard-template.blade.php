@extends('layouts.unified-layout')

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-section role-color-{{ $roleColor }}">
        <div class="welcome-content">
            <div class="welcome-avatar {{ $roleColor }}">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="welcome-avatar-img">
                @else
                    <i class="{{ $roleIcon }}"></i>
                @endif
            </div>
            <div class="welcome-text">
                <h1 class="welcome-title">Selamat datang, {{ $user->name ?? 'Pengguna' }}!</h1>
                <p class="welcome-subtitle">{{ $roleTitle }} - {{ $roleDescription }}</p>
                <p class="welcome-message">{{ $welcomeMessage }}</p>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards Grid -->
    <div class="dashboard-grid">
        @yield('dashboard-cards')
    </div>

    <!-- Role Information Modal (Optional) -->
    <div class="role-info-section">
        <div class="role-info-card">
            <div class="role-info-header">
                <div class="role-info-icon {{ $roleColor }}">
                    <i class="{{ $roleIcon }}"></i>
                </div>
                <div class="role-info-title">
                    <h3>{{ $permissionsTitle }}</h3>
                    <p>{{ $roleDescription }}</p>
                </div>
            </div>
            <div class="role-info-content">
                <div class="permissions-section">
                    <h4>{{ $permissionsTitle }}</h4>
                    <ul class="permissions-list">
                        @foreach($permissions as $permission)
                            <li><i class="fas fa-check-circle"></i> {{ $permission }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="responsibilities-section">
                    <h4>{{ $responsibilitiesTitle }}</h4>
                    <ul class="responsibilities-list">
                        @foreach($responsibilities as $responsibility)
                            <li><i class="fas fa-star"></i> {{ $responsibility }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
/* Welcome Section Styles */
.welcome-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(51, 65, 85, 0.8) 100%);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.welcome-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.welcome-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    background: linear-gradient(135deg, var(--role-color-start), var(--role-color-end));
    flex-shrink: 0;
}

.welcome-avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.welcome-text {
    flex: 1;
}

.welcome-title {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    font-size: 1.1rem;
    color: #cbd5e1;
    margin-bottom: 0.75rem;
}

.welcome-message {
    font-size: 1rem;
    color: #94a3b8;
    line-height: 1.6;
}

/* Dashboard Grid Styles */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Desktop: 4 columns */
@media (min-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Tablet: 3 columns */
@media (min-width: 768px) and (max-width: 1023px) {
    .dashboard-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Mobile: 2 columns */
@media (max-width: 767px) {
    .dashboard-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.75rem;
    }
    
    .card {
        padding: 1rem;
        min-height: 120px;
    }

    .card-icon {
        width: 45px;
        height: 45px;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .card-title {
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .card-description {
        display: none; /* Hide descriptions on mobile to keep grid compact */
    }
    
    .welcome-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .welcome-avatar {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }
}

/* Extra Small Mobile Adjustment */
@media (max-width: 375px) {
    .dashboard-grid {
        gap: 0.5rem;
    }
    
    .card {
        padding: 0.75rem;
    }
}


/* Role Information Section */
.role-info-section {
    margin-top: 2rem;
}

.role-info-card {
    background: rgba(30, 41, 59, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.role-info-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.role-info-icon {
    width: 50px;
    height: 50px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: linear-gradient(135deg, var(--role-color-start), var(--role-color-end));
}

.role-info-title h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.role-info-title p {
    color: #cbd5e1;
    font-size: 0.9rem;
}

.role-info-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.permissions-section h4,
.responsibilities-section h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 1rem;
}

.permissions-list,
.responsibilities-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.permissions-list li,
.responsibilities-list li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: #cbd5e1;
    font-size: 0.9rem;
}

.permissions-list li i {
    color: #10b981;
    font-size: 0.875rem;
}

.responsibilities-list li i {
    color: #f59e0b;
    font-size: 0.875rem;
}

/* Mobile responsive for role info */
@media (max-width: 768px) {
    .role-info-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .role-info-header {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
}

/* Card styles (inherited from existing CSS) */
.card {
    background: rgba(30, 41, 59, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    border: 1px solid rgba(51, 65, 85, 0.5);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-height: 160px;
}

.card:hover {
    transform: translateY(-4px);
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(51, 65, 85, 0.8);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
}

.card-icon.blue {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.card-icon.green {
    background: linear-gradient(135deg, #10b981, #059669);
}

.card-icon.purple {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.card-icon.orange {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.card-icon.red {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.card-description {
    font-size: 0.875rem;
    color: #cbd5e1;
    line-height: 1.5;
    margin: 0;
}

/* Role-specific color variables */
.role-color-purple {
    --role-color-start: #8b5cf6;
    --role-color-end: #7c3aed;
}

.role-color-blue {
    --role-color-start: #3b82f6;
    --role-color-end: #1d4ed8;
}

.role-color-green {
    --role-color-start: #10b981;
    --role-color-end: #059669;
}

.role-color-orange {
    --role-color-start: #f59e0b;
    --role-color-end: #d97706;
}
</style>
@endsection
