@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Materi')

@section('styles')
<style>
/* Dark Theme Base */
body {
    background: #0f172a !important;
    color: #ffffff !important;
}

/* Override any white backgrounds */
* {
    box-sizing: border-box;
}

/* Ensure all text is visible on dark background */
h1, h2, h3, h4, h5, h6, p, span, div, a, label, select, option {
    color: inherit;
}

/* Dark theme for form elements */
select, input, textarea {
    background: #1e293b !important;
    color: #ffffff !important;
    border-color: #475569 !important;
}

select option {
    background: #1e293b !important;
    color: #ffffff !important;
}

/* Modern Student Material UI */
.material-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    background: #0f172a;
    min-height: 100vh;
    width: 100%;
    position: relative;
    z-index: 1;
}

/* Full width dark background */
.material-container::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #0f172a;
    z-index: -1;
}

/* Ensure full width dark background */
html, body {
    background: #0f172a !important;
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100vh;
}

/* Override any parent container backgrounds */
.container, .main-content, .content-wrapper {
    background: #0f172a !important;
}

/* Force dark theme on all elements */
*, *::before, *::after {
    background-color: transparent;
}

/* Override any white backgrounds that might appear */
.card, .panel, .box, .container-fluid, .row, .col, .col-md, .col-lg, .col-xl {
    background: #0f172a !important;
    color: #ffffff !important;
}

/* Ensure navigation and header are dark */
.navbar, .header, .sidebar, .menu {
    background: #1e293b !important;
    color: #ffffff !important;
}

/* Override any framework defaults */
.bg-white, .bg-light, .text-dark {
    background: #1e293b !important;
    color: #ffffff !important;
}

.page-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    color: white;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="25" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="25" cy="75" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    position: relative;
    z-index: 2;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-description {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
    position: relative;
    z-index: 2;
}

.materials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.material-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 1.5rem;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    position: relative;
    overflow: hidden;
}

.material-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    z-index: 1;
}

.material-card > * {
    position: relative;
    z-index: 2;
}

.material-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.material-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.material-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    line-height: 1.3;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.material-subject {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(59, 130, 246, 0.9);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
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
    gap: 0.75rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    font-size: 0.85rem;
    color: #ffffff;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.meta-icon {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    padding: 0.25rem;
}

.meta-text {
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 500;
}

.material-description {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.5;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.material-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.material-btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    flex: 1;
    justify-content: center;
}

.material-btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.material-btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 16px;
    border: 2px solid #475569;
    position: relative;
    overflow: hidden;
}

.empty-state::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(102, 126, 234, 0.2)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
    opacity: 0.5;
}

.empty-icon {
    font-size: 4rem;
    color: #667eea;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 2;
    text-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.75rem;
    position: relative;
    z-index: 2;
}

.empty-description {
    color: #cbd5e1;
    font-size: 1rem;
    margin: 0;
    position: relative;
    z-index: 2;
}

/* Filter Controls */
.filter-controls {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 12px;
    border: 1px solid #475569;
    flex-wrap: wrap;
    align-items: end;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 180px;
}

.filter-label {
    color: #cbd5e1;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-select {
    padding: 0.75rem 1rem;
    background: #1e293b;
    border: 2px solid #475569;
    border-radius: 8px;
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    transform: translateY(-1px);
    background: #2a2a3e;
}

.filter-btn {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 8px;
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
}

/* Tablet - 2x2 Grid */
@media (max-width: 1200px) {
    .materials-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
}

@media (max-width: 768px) {
    .material-container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 2rem 1rem;
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .filter-controls {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }
    
    .filter-group {
        min-width: auto;
        width: 100%;
    }
    
    .materials-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .material-card {
        padding: 1.25rem;
    }
    
    .material-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .material-title {
        font-size: 1rem;
    }
    
    .empty-state {
        padding: 3rem 1.5rem;
    }
    
    .empty-icon {
        font-size: 3rem;
    }
    
    .empty-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 480px) {
    .material-container {
        padding: 0.75rem;
    }
    
    .page-header {
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.75rem;
    }
    
    .page-description {
        font-size: 0.95rem;
    }
    
    .materials-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .material-card {
        padding: 1rem;
    }
    
    .material-title {
        font-size: 1rem;
    }
    
    .material-description {
        font-size: 0.8rem;
    }
    
    .meta-item {
        font-size: 0.8rem;
        padding: 0.4rem;
    }
    
    .filter-controls {
        padding: 1rem;
    }
    
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .empty-icon {
        font-size: 2.5rem;
    }
    
    .empty-title {
        font-size: 1.1rem;
    }
}
</style>
@endsection

@section('content')
<div class="material-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="ph-book"></i>
            Materi Pembelajaran
        </h1>
        <p class="page-description">Akses materi pembelajaran dan sumber daya edukatif</p>
    </div>

    <!-- Filter and Sort Controls -->
    <div class="filter-controls">
        <div class="filter-group">
            <label for="subjectFilter" class="filter-label">Filter Mata Pelajaran:</label>
            <select id="subjectFilter" class="filter-select" onchange="filterMaterials()">
                <option value="">Semua Mata Pelajaran</option>
                @if($materi && $materi->count() > 0)
                    @foreach($materi->pluck('kelasMapel.mapel.name')->unique() as $subject)
                        <option value="{{ $subject }}">{{ $subject }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        <div class="filter-group">
            <label for="sortBy" class="filter-label">Urutkan:</label>
            <select id="sortBy" class="filter-select" onchange="sortMaterials()">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="subject">Berdasarkan Mata Pelajaran</option>
                <option value="title">Berdasarkan Judul</option>
            </select>
        </div>
        
        <div class="filter-group">
            <button class="filter-btn" onclick="resetFilters()">
                <i class="ph-arrow-clockwise"></i>
                Reset Filter
            </button>
        </div>
    </div>

    @if($materi && $materi->count() > 0)
        <div class="materials-grid">
            @foreach($materi as $materiItem)
                <div class="material-card" onclick="window.location.href='{{ route('student.materi.detail', $materiItem->id) }}'">
                    <div class="material-header">
                        <h2 class="material-title">{{ $materiItem->name }}</h2>
                        <span class="material-subject">{{ $materiItem->kelasMapel->mapel->name }}</span>
                    </div>

                    <div class="material-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="ph-user"></i>
                            </div>
                            <span class="meta-text">{{ $materiItem->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="ph-calendar"></i>
                            </div>
                            <span class="meta-text">{{ $materiItem->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <p class="material-description">
                        {{ Str::limit(strip_tags($materiItem->content), 150) }}
                    </p>

                    <div class="material-actions" style="display: none;">
                        <!-- Actions hidden since card is clickable -->
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="ph-book empty-icon"></i>
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
        return {
            element: card,
            title: card.querySelector('.material-title').textContent,
            subject: card.querySelector('.material-subject').textContent,
            date: card.querySelector('.meta-text:last-child').textContent
        };
    });
    
    filteredMaterials = [...allMaterials];
});

function filterMaterials() {
    const subjectFilter = document.getElementById('subjectFilter').value;
    
    filteredMaterials = allMaterials.filter(material => {
        if (!subjectFilter) return true;
        return material.subject === subjectFilter;
    });
    
    updateMaterialDisplay();
}

function sortMaterials() {
    const sortBy = document.getElementById('sortBy').value;
    
    filteredMaterials.sort((a, b) => {
        switch(sortBy) {
            case 'subject':
                return a.subject.localeCompare(b.subject);
            case 'title':
                return a.title.localeCompare(b.title);
            case 'newest':
            case 'oldest':
                // This would need more complex date parsing in a real implementation
                return 0;
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
    
    // Update grid layout
    materialsGrid.style.display = 'grid';
    materialsGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
    materialsGrid.style.gap = '1.5rem';
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