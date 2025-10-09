# Terra Assessment - Unified Design System

## 🎯 Overview

Sistem desain unifikasi yang konsisten untuk semua halaman Terra Assessment, mencakup sidebar, header, tema warna, dan komponen UI yang seragam untuk semua role (Super Admin, Admin, Teacher, Student).

## 🚀 Fitur Utama

### ✅ **Konsistensi Desain**
- **Sidebar Unifikasi**: Sidebar yang konsisten dengan navigasi yang sesuai role
- **Header Konsisten**: Header dengan struktur yang sama di semua halaman
- **Tema Warna Seragam**: Palet warna yang konsisten dengan variasi per role
- **Komponen UI Standar**: Button, Card, Stats Card yang dapat digunakan kembali

### ✅ **Responsive Design**
- **Mobile-First**: Optimized untuk semua ukuran layar
- **Sidebar Collapsible**: Sidebar yang bisa di-collapse di desktop
- **Mobile Navigation**: Overlay sidebar untuk mobile
- **Touch-Friendly**: Interface yang mudah digunakan di touch device

### ✅ **Role-Based Theming**
- **Super Admin**: Purple gradient theme
- **Admin**: Blue gradient theme  
- **Teacher**: Green gradient theme
- **Student**: Orange gradient theme

## 📁 Struktur File

```
resources/
├── css/
│   ├── unified-design-system.css     # Core design system
│   └── app.css                       # Main CSS dengan import
├── views/
│   ├── layouts/
│   │   └── unified-layout-consistent.blade.php  # Layout utama
│   ├── components/
│   │   ├── unified-card.blade.php               # Card component
│   │   ├── unified-button.blade.php             # Button component
│   │   └── unified-stats-card.blade.php         # Stats card component
│   └── dashboard/
│       └── unified-dashboard.blade.php          # Dashboard unifikasi
└── app/Http/Controllers/
    └── DashboardController.php                  # Controller dengan helper methods
```

## 🎨 Design Tokens

### Color Palette
```css
/* Primary Colors - Terra Blue */
--primary-50: #eff6ff;
--primary-500: #3b82f6;
--primary-600: #2563eb;
--primary-700: #1d4ed8;

/* Secondary Colors - Neutral Gray */
--secondary-50: #f8fafc;
--secondary-500: #64748b;
--secondary-600: #475569;
--secondary-900: #0f172a;

/* Status Colors */
--success-500: #22c55e;
--warning-500: #f59e0b;
--error-500: #ef4444;
--info-500: #3b82f6;
```

### Typography
```css
/* Font Family */
--font-family-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

/* Font Sizes */
--font-size-sm: 0.875rem;   /* 14px */
--font-size-base: 1rem;     /* 16px */
--font-size-lg: 1.125rem;   /* 18px */
--font-size-xl: 1.25rem;    /* 20px */

/* Font Weights */
--font-weight-medium: 500;
--font-weight-semibold: 600;
--font-weight-bold: 700;
```

### Spacing
```css
--space-2: 0.5rem;    /* 8px */
--space-3: 0.75rem;   /* 12px */
--space-4: 1rem;      /* 16px */
--space-6: 1.5rem;    /* 24px */
--space-8: 2rem;      /* 32px */
```

## 🧩 Komponen UI

### 1. Unified Card
```blade
<x-unified-card 
    title="Card Title" 
    subtitle="Card subtitle" 
    icon="fas fa-icon" 
    color="primary"
    href="/link">
    Card content here
</x-unified-card>
```

### 2. Unified Button
```blade
<x-unified-button 
    variant="primary" 
    size="md" 
    icon="fas fa-plus"
    href="/action">
    Button Text
</x-unified-button>
```

### 3. Unified Stats Card
```blade
<x-unified-stats-card
    title="Total Users"
    value="1,234"
    change="+12%"
    change-type="positive"
    icon="fas fa-users"
    color="primary"
    href="/users" />
```

## 🎯 Layout Structure

### Sidebar Navigation
- **Logo & Role Badge**: Menampilkan logo Terra Assessment dan role user
- **Navigation Menu**: Menu yang disesuaikan dengan role user
- **Active State**: Indikator visual untuk halaman aktif
- **Collapsible**: Bisa di-collapse di desktop untuk menghemat ruang

### Header
- **Page Title**: Judul halaman dan deskripsi
- **Notifications**: Bell icon dengan badge notifikasi
- **User Profile**: Dropdown dengan avatar dan menu profile
- **Mobile Toggle**: Button untuk toggle sidebar di mobile

### Main Content
- **Responsive Grid**: Grid system yang responsive
- **Consistent Spacing**: Spacing yang konsisten antar elemen
- **Card-based Layout**: Konten dalam card untuk konsistensi visual

## 📱 Responsive Breakpoints

```css
/* Mobile */
@media (max-width: 640px) { }

/* Tablet */
@media (max-width: 768px) { }

/* Desktop */
@media (max-width: 1024px) { }

/* Large Desktop */
@media (min-width: 1025px) { }
```

## 🔧 Implementation

### 1. Menggunakan Layout Konsisten
```blade
@extends('layouts.unified-layout-consistent')

@section('title', 'Page Title')
@section('page-title', 'Page Title')
@section('page-description', 'Page description')

@section('content')
    <!-- Page content here -->
@endsection
```

### 2. Menggunakan Komponen UI
```blade
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-unified-stats-card ... />
    <x-unified-stats-card ... />
</div>

<!-- Action Buttons -->
<div class="flex gap-3">
    <x-unified-button variant="primary" ... />
    <x-unified-button variant="secondary" ... />
</div>
```

### 3. Role-Based Styling
Layout otomatis menerapkan class CSS berdasarkan role:
- `.role-superadmin` - Purple theme
- `.role-admin` - Blue theme  
- `.role-teacher` - Green theme
- `.role-student` - Orange theme

## 🎨 Role-Specific Themes

### Super Admin (Purple)
- Primary: Purple gradient
- Active states: Purple variants
- Icons: Crown icon

### Admin (Blue)
- Primary: Blue gradient
- Active states: Blue variants
- Icons: Shield icon

### Teacher (Green)
- Primary: Green gradient
- Active states: Green variants
- Icons: Chalkboard icon

### Student (Orange)
- Primary: Orange gradient
- Active states: Orange variants
- Icons: Graduate icon

## 📊 Dashboard Features

### Statistics Cards
- **Real-time Data**: Data statistik yang real-time
- **Role-based Stats**: Statistik yang disesuaikan dengan role
- **Interactive**: Clickable untuk navigasi ke halaman detail

### Recent Activities
- **Activity Feed**: Feed aktivitas terbaru
- **Role-based Activities**: Aktivitas yang relevan dengan role
- **Time Stamps**: Waktu aktivitas yang jelas

### Quick Actions
- **Role-based Actions**: Action yang sesuai dengan role
- **Direct Navigation**: Link langsung ke fitur utama
- **Visual Icons**: Icon yang jelas untuk setiap action

## 🔄 Migration Guide

### Dari Layout Lama ke Layout Konsisten

1. **Update Layout Extension**:
   ```blade
   <!-- Old -->
   @extends('layouts.terra-layout')
   
   <!-- New -->
   @extends('layouts.unified-layout-consistent')
   ```

2. **Update Komponen**:
   ```blade
   <!-- Old -->
   <div class="card">
   
   <!-- New -->
   <x-unified-card>
   ```

3. **Update CSS Classes**:
   ```blade
   <!-- Old -->
   <button class="btn btn-primary">
   
   <!-- New -->
   <x-unified-button variant="primary">
   ```

## 🚀 Benefits

### ✅ **Konsistensi Visual**
- Semua halaman memiliki tampilan yang seragam
- User experience yang konsisten
- Brand identity yang kuat

### ✅ **Maintainability**
- Komponen yang dapat digunakan kembali
- Design tokens yang terpusat
- Mudah untuk update dan maintenance

### ✅ **Performance**
- CSS yang optimized
- Komponen yang lightweight
- Loading yang cepat

### ✅ **Accessibility**
- WCAG compliant
- Keyboard navigation support
- Screen reader friendly

### ✅ **Responsive**
- Mobile-first approach
- Touch-friendly interface
- Optimized untuk semua device

## 📝 Next Steps

1. **Implementasi Bertahap**: Migrasi halaman satu per satu ke layout konsisten
2. **Testing**: Test di semua device dan browser
3. **User Feedback**: Kumpulkan feedback dari user
4. **Optimization**: Optimasi performance dan accessibility
5. **Documentation**: Update dokumentasi untuk developer

## 🎯 Status Implementasi

- ✅ **Design System**: Core design system selesai
- ✅ **Layout Konsisten**: Layout unifikasi selesai
- ✅ **Komponen UI**: Komponen dasar selesai
- ✅ **Dashboard Unifikasi**: Dashboard konsisten selesai
- ✅ **Role-based Theming**: Theme per role selesai
- 🔄 **Migration**: Sedang dalam proses migrasi halaman
- ⏳ **Testing**: Belum dilakukan testing menyeluruh

---

**Sistem desain unifikasi Terra Assessment telah siap digunakan dan akan memberikan pengalaman user yang konsisten dan modern di semua halaman platform.**
