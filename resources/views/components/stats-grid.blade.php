<!-- Statistics Grid Component -->
<div class="stats-grid">
    @if(isset($stats) && is_array($stats))
        @foreach($stats as $stat)
            <div class="stat-card">
                @if(isset($stat['icon']))
                    <div class="stat-icon">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                @endif
                <div class="stat-content">
                    <div class="stat-value">{{ $stat['value'] ?? 0 }}</div>
                    <div class="stat-label">{{ $stat['label'] ?? 'N/A' }}</div>
                </div>
            </div>
        @endforeach
    @else
        <!-- Default stats for guru -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalPengajar ?? 0 }}</div>
                <div class="stat-label">Total Pengajar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalSiswa ?? 0 }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalKelas ?? 0 }}</div>
                <div class="stat-label">Total Kelas</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalMapel ?? 0 }}</div>
                <div class="stat-label">Mata Pelajaran</div>
            </div>
        </div>
    @endif
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 1.5rem;
    color: white;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 16px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.4);
}

.stat-card:nth-child(2) {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 8px 32px rgba(240, 147, 251, 0.3);
}

.stat-card:nth-child(3) {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    box-shadow: 0 8px 32px rgba(79, 172, 254, 0.3);
}

.stat-card:nth-child(4) {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    box-shadow: 0 8px 32px rgba(67, 233, 123, 0.3);
}

.stat-icon {
    position: relative;
    z-index: 2;
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.stat-content {
    position: relative;
    z-index: 2;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    font-weight: 500;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
    
    .stat-icon {
        font-size: 2rem;
    }
}
</style>
