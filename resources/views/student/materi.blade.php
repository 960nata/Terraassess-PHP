@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - My Materials')
@section('page-title', 'My Materials')
@section('page-description', 'Akses materi pembelajaran dan sumber daya edukatif')

@section('styles')
<style>
/* Student Material Management Styles - Consistent with Superadmin */
.material-filters {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #2a2a3e;
    border: 2px solid #333;
    border-radius: 8px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    background: #333;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: #ffffff;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Material Cards Grid */
.materials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.material-card {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #475569;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.material-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    border-color: #667eea;
}

/* Material Card Components */
.material-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.material-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
    line-height: 1.3;
}

.material-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.material-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #cbd5e1;
    font-size: 0.875rem;
}

.meta-item i {
    color: #667eea;
    width: 16px;
}

.material-content {
    color: #cbd5e1;
    line-height: 1.5;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.material-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.material-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #94a3b8;
    font-size: 0.875rem;
}

.material-btn {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: #ffffff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.material-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: #ffffff;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 16px;
    border: 1px solid #475569;
}

.empty-icon {
    font-size: 4rem;
    color: #667eea;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.75rem;
}

.empty-description {
    color: #cbd5e1;
    font-size: 1rem;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .materials-grid {
        grid-template-columns: 1fr;
    }
    
    .material-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter Controls -->
    <div class="material-filters">
        <div class="filter-row">
            <div class="form-group">
                <label for="subjectFilter">Mata Pelajaran:</label>
                <select id="subjectFilter" class="form-control" onchange="filterMaterials()">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($materi->pluck('kelasMapel.mapel.name')->unique() as $subject)
                        <option value="{{ $subject }}">{{ $subject }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="sortBy">Urutkan:</label>
                <select id="sortBy" class="form-control" onchange="sortMaterials()">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="subject">Berdasarkan Mata Pelajaran</option>
                </select>
            </div>
            
            <button class="btn-primary" onclick="resetFilters()">
                <i class="fas fa-sync-alt"></i>
                Reset Filter
            </button>
        </div>
    </div>

    @if($materi && $materi->count() > 0)
        <div class="materials-grid">
            @foreach($materi as $materiItem)
                <div class="material-card" onclick="window.location.href='{{ route('student.materials.detail', $materiItem->id) }}'">
                    <div class="material-header">
                        <h3 class="material-title">{{ $materiItem->name }}</h3>
                        <span class="material-badge">{{ $materiItem->kelasMapel->mapel->name }}</span>
                    </div>
                    
                    <div class="material-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>{{ $materiItem->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $materiItem->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    
                    <p class="material-content">
                        {{ Str::limit(strip_tags($materiItem->content), 150) }}
                    </p>
                    
                    <div class="material-footer">
                        <div class="material-date">
                            <i class="fas fa-clock"></i>
                            <span>{{ $materiItem->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('student.materials.detail', $materiItem->id) }}" class="material-btn">
                            <i class="fas fa-arrow-right"></i>
                            Baca Materi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-book"></i>
            </div>
            <h3 class="empty-title">Belum Ada Materi</h3>
            <p class="empty-description">Tidak ada materi pembelajaran yang tersedia saat ini. Silakan cek kembali nanti.</p>
        </div>
    @endif
</div>

<script>
// Material filtering and sorting functionality
let allMaterials = [];
let filteredMaterials = [];

// Initialize materials data
document.addEventListener('DOMContentLoaded', function() {
    const materialCards = document.querySelectorAll('.material-card');
    allMaterials = Array.from(materialCards).map(card => {
        const metaItems = card.querySelectorAll('.meta-item span');
        const subject = card.querySelector('.material-badge').textContent;
        
        return {
            element: card,
            title: card.querySelector('.material-title').textContent,
            subject: subject,
            teacher: metaItems[0] ? metaItems[0].textContent : '',
            date: metaItems[1] ? metaItems[1].textContent : ''
        };
    });
    
    filteredMaterials = [...allMaterials];
});

function filterMaterials() {
    const subjectFilter = document.getElementById('subjectFilter').value;
    
    filteredMaterials = allMaterials.filter(material => {
        const subjectMatch = !subjectFilter || material.subject === subjectFilter;
        return subjectMatch;
    });
    
    updateMaterialDisplay();
}

function sortMaterials() {
    const sortBy = document.getElementById('sortBy').value;
    
    filteredMaterials.sort((a, b) => {
        switch(sortBy) {
            case 'newest':
                return b.date.localeCompare(a.date);
            case 'oldest':
                return a.date.localeCompare(b.date);
            case 'subject':
                return a.subject.localeCompare(b.subject);
            default:
                return 0;
        }
    });
    
    updateMaterialDisplay();
}

function updateMaterialDisplay() {
    const materialsGrid = document.querySelector('.materials-grid');
    if (!materialsGrid) return;
    
    // Hide all materials
    allMaterials.forEach(material => {
        material.element.style.display = 'none';
    });
    
    // Show filtered materials
    filteredMaterials.forEach(material => {
        material.element.style.display = 'block';
    });
}

function resetFilters() {
    document.getElementById('subjectFilter').value = '';
    document.getElementById('sortBy').value = 'newest';
    filteredMaterials = [...allMaterials];
    updateMaterialDisplay();
}

// Add smooth animations
function addMaterialAnimations() {
    const materialCards = document.querySelectorAll('.material-card');
    materialCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Initialize animations when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(addMaterialAnimations, 100);
});
</script>
@endsection