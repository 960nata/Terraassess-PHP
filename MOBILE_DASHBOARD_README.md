# Mobile Dashboard Implementation

## Overview
Implementasi dashboard mobile dengan layout 2 grid (kiri dan kanan) untuk tampilan yang optimal di perangkat mobile. Dashboard ini menampilkan 12 card fitur utama yang terorganisir dalam 2 kolom vertikal.

## Features Implemented

### 1. Mobile Dashboard - 2 Grid Layout (Kiri & Kanan)
- **Mobile (≤768px)**: 2 Grid Layout (Kiri & Kanan) dengan 6 card per grid
- **Tablet (641px-1024px)**: 2 Grid Layout (Kiri & Kanan) dengan spacing yang lebih besar
- **Desktop (≥1025px)**: Hidden (menampilkan layout desktop)

### 2. Grid Organization

#### Grid Kiri - "Manajemen Sistem"
1. **Manajemen Pengguna** - Kelola semua pengguna sistem (Admin, Guru, Siswa)
2. **Manajemen Kelas** - Buat dan kelola semua kelas di sistem
3. **Mata Pelajaran** - Tambah dan kelola mata pelajaran
4. **Materi** - Kelola materi pembelajaran dan konten
5. **Notifikasi** - Kirim notifikasi ke semua pengguna, kelas, atau pengguna spesifik
6. **Pengaturan** - Kelola pengaturan sistem dan konfigurasi

#### Grid Kanan - "Aktivitas Pembelajaran"
1. **Manajemen Tugas** - Kelola tugas per kelas dengan kategorisasi dan tingkat kesulitan
2. **Manajemen Ujian** - Buat, edit, dan kelola ujian dengan fitur lengkap
3. **Manajemen IoT** - Daftarkan perangkat IoT, test konektivitas, dan monitor data sensor
4. **Tugas IoT** - Buat dan kelola tugas penelitian IoT
5. **Penelitian IoT** - Lihat hasil penelitian IoT siswa
6. **Laporan & Analisis** - Lihat laporan dan analisis data pembelajaran

## Files Modified

### 1. Dashboard View
- **File**: `resources/views/menu/admin/dashboard/dashboard.blade.php`
- **Changes**: Menambahkan section mobile dashboard grid dengan 12 card fitur

### 2. CSS Styling
- **File**: `resources/css/responsive-utilities.css`
- **Changes**: Menambahkan styling untuk mobile dashboard grid dan responsive behavior

## CSS Classes Added

```css
.mobile-dashboard-container     /* Container utama untuk 2 grid layout */
.mobile-dashboard-grid-left     /* Grid kiri - Manajemen Sistem */
.mobile-dashboard-grid-right    /* Grid kanan - Aktivitas Pembelajaran */
.mobile-grid-title             /* Judul untuk setiap grid */
.mobile-dashboard-card         /* Card individual */
.mobile-card-icon              /* Icon container */
.mobile-card-content           /* Content wrapper */
.mobile-card-title             /* Judul card */
.mobile-card-desc              /* Deskripsi card */
.mobile-card-action            /* Action button container */
.mobile-card-link              /* Link button */
```

## Responsive Behavior

### Mobile (≤768px)
- Grid 2x6 (2 kolom, 6 baris)
- Card padding: 1rem
- Font size: 0.875rem (title), 0.75rem (desc)
- Gap: 1rem

### Tablet (641px-1024px)
- Grid 3x4 (3 kolom, 4 baris)
- Card padding: 1.25rem
- Font size: 1rem (title), 0.875rem (desc)
- Gap: 1.25rem

### Desktop (≥1025px)
- Mobile dashboard hidden
- Menampilkan layout desktop yang sudah ada

## Visual Design

### Card Styling
- **Background**: Glass morphism effect dengan `rgba(255, 255, 255, 0.05)`
- **Border**: `rgba(255, 255, 255, 0.1)` dengan border-radius 12px
- **Hover Effect**: Transform translateY(-2px) dengan shadow
- **Gradient Overlay**: Purple to blue gradient pada hover

### Icons
- Menggunakan Phosphor Icons
- Size: 2xl (24px)
- Color: Purple (#8b5cf6)
- Background: Semi-transparent white

### Typography
- **Font Family**: Inter (primary), Poppins (headings)
- **Title**: 0.875rem, font-weight 600, white color
- **Description**: 0.75rem, rgba(255, 255, 255, 0.7), 2-line clamp

## Demo File
- **File**: `mobile-dashboard-demo.html`
- **Purpose**: Standalone demo untuk testing responsive behavior
- **Features**: Interactive cards, screen size detection, space theme

## Usage

### Laravel Blade
```php
{{-- Mobile Dashboard Grid --}}
<div class="mobile-dashboard-grid">
    <div class="mobile-dashboard-card">
        <div class="mobile-card-icon">
            <i class="ph-bell text-2xl"></i>
        </div>
        <div class="mobile-card-content">
            <h3 class="mobile-card-title">Notifikasi</h3>
            <p class="mobile-card-desc">Kirim notifikasi ke semua pengguna</p>
        </div>
        <div class="mobile-card-action">
            <a href="{{ route('notifications.user') }}" class="mobile-card-link">
                <i class="ph-arrow-right text-lg"></i>
            </a>
        </div>
    </div>
    <!-- More cards... -->
</div>
```

### CSS Import
```css
@import './responsive-utilities.css';
```

## Browser Support
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Notes
- Menggunakan CSS Grid untuk layout
- Backdrop-filter untuk glass effect
- CSS custom properties untuk theming
- Mobile-first responsive design
- Touch-friendly interactions (44px minimum touch target)
