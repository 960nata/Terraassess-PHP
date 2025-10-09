# Student Layout Consistency Guide

## 🎯 **Tujuan**
Membuat header dan sidebar yang konsisten di semua halaman siswa untuk memberikan pengalaman pengguna yang seragam.

## 📁 **File Komponen yang Dibuat**

### 1. **Layout Utama**
- `resources/views/layouts/student-layout.blade.php` - Layout utama untuk semua halaman siswa

### 2. **Komponen Header**
- `resources/views/components/student-header.blade.php` - Header dengan logo, notifikasi, dan profil user

### 3. **Komponen Sidebar**
- `resources/views/components/student-sidebar.blade.php` - Sidebar dengan menu navigasi

### 4. **Styling Konsisten**
- `resources/views/components/student-layout-styles.blade.php` - CSS untuk header, sidebar, dan layout

### 5. **JavaScript Konsisten**
- `resources/views/components/student-layout-scripts.blade.php` - JavaScript untuk interaksi header dan sidebar

## 🔧 **Cara Menggunakan**

### **Untuk Halaman Baru:**
```php
@extends('layouts.student-layout')

@section('title', 'Terra Assessment - Nama Halaman')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-icon-name"></i>
            Nama Halaman
        </h1>
        <p class="page-description">Deskripsi halaman</p>
    </div>

    <!-- Konten halaman di sini -->
@endsection

@section('additional-styles')
<style>
    /* CSS tambahan untuk halaman ini */
</style>
@endsection
```

### **Untuk Halaman yang Sudah Ada:**
1. Ganti `@extends('layout.template.mainTemplate')` dengan `@extends('layouts.student-layout')`
2. Hapus semua HTML header dan sidebar yang sudah ada
3. Pindahkan konten ke dalam `@section('content')`
4. Pindahkan CSS ke `@section('additional-styles')`
5. Pindahkan JavaScript ke `@section('additional-scripts')`

## ✅ **Halaman yang Sudah Diupdate**

1. **✅ Ujian** - `resources/views/student/ujian.blade.php`
2. **✅ Tugas** - `resources/views/student/tugas.blade.php`
3. **✅ Materi** - `resources/views/student/materi.blade.php`
4. **✅ Dashboard** - `resources/views/student/dashboard-new.blade.php`

## 🎨 **Fitur Layout Konsisten**

### **Header Features:**
- Logo Terra Assessment dengan icon
- Menu toggle untuk mobile
- Notifikasi dropdown dengan badge
- Profile dropdown dengan avatar
- Responsive design

### **Sidebar Features:**
- Menu utama (Dashboard, Tugas, Materi, Ujian)
- Menu kelas & pembelajaran
- Menu pengaturan
- Active state highlighting
- Mobile overlay

### **Main Content Features:**
- Page header dengan title dan description
- Consistent background gradient
- Responsive padding
- Glass morphism effects

## 📱 **Responsive Design**

- **Desktop**: Full sidebar dan header
- **Tablet**: Collapsible sidebar
- **Mobile**: Overlay sidebar dengan hamburger menu

## 🔄 **Active State Management**

Sidebar secara otomatis menandai menu yang aktif berdasarkan route:
```php
class="menu-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
```

## 🎯 **Keuntungan**

1. **Konsistensi UI/UX** - Semua halaman terlihat sama
2. **Maintainability** - Mudah update header/sidebar di satu tempat
3. **Performance** - CSS dan JS di-cache
4. **Responsive** - Otomatis responsive di semua device
5. **Accessibility** - Struktur HTML yang semantik

## 🚀 **Next Steps**

1. Update halaman siswa lainnya yang belum menggunakan layout konsisten
2. Test di berbagai device dan browser
3. Optimize performance jika diperlukan
4. Tambahkan fitur notifikasi real-time jika diperlukan

## 📝 **Catatan Penting**

- Pastikan semua route sudah didefinisikan di `routes/web.php`
- Gunakan `{{ route('route.name') }}` untuk link navigation
- Test responsive design di berbagai ukuran layar
- Pastikan semua icon Font Awesome tersedia
