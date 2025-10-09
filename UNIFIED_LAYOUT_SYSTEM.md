# Unified Layout System

## Overview
Sistem layout unified memungkinkan semua role (Super Admin, Admin, Guru, Siswa) menggunakan header dan sidebar yang sama, dengan menu yang disesuaikan berdasarkan akses masing-masing role.

## Struktur File

### Layout Utama
- `resources/views/layouts/unified-layout.blade.php` - Layout utama untuk semua role
- `resources/views/layouts/superadmin-layout.blade.php` - Layout super admin (tetap ada untuk kompatibilitas)

### Komponen yang Dihapus
Semua layout dan sidebar terpisah telah dihapus untuk konsistensi:
- ❌ `resources/views/layouts/admin-layout.blade.php`
- ❌ `resources/views/layouts/guru-layout-new.blade.php`
- ❌ `resources/views/layouts/student-layout.blade.php`
- ❌ `resources/views/layouts/unified-layout.blade.php` (yang lama)
- ❌ `resources/views/layout/template/dashboard-template.blade.php`
- ❌ `resources/views/layout/template/galaxyTemplate.blade.php`
- ❌ `resources/views/layout/template/mainTemplate.blade.php`
- ❌ `resources/views/layout/template/modernTemplate.blade.php`
- ❌ `resources/views/layout/template/spaceTemplate.blade.php`
- ❌ `resources/views/layout/template/studentTemplate.blade.php`
- ❌ `resources/views/layout/navbar/consistent-sidebar.blade.php`
- ❌ `resources/views/layout/navbar/modern-sidebar.blade.php`
- ❌ `resources/views/layout/navbar/modern-topbar.blade.php`
- ❌ `resources/views/layout/navbar/sidebar.blade.php`
- ❌ `resources/views/layout/navbar/student-sidebar.blade.php`

## Fitur Unified Layout

### 1. Header Dinamis
- **Logo**: Menampilkan ikon sesuai role
- **Warna**: Setiap role memiliki warna khas
  - Super Admin: Purple (`#8b5cf6`)
  - Admin: Blue (`#3b82f6`)
  - Guru: Green (`#10b981`)
  - Siswa: Orange (`#f59e0b`)
- **Profile Dropdown**: Menampilkan nama dan role user
- **Notification**: Sistem notifikasi terintegrasi

### 2. Sidebar Berdasarkan Role
Menu sidebar disesuaikan dengan akses masing-masing role:

#### Super Admin (Role ID: 1)
- ✅ Menu Utama: Dashboard, Push Notifikasi, Manajemen IoT
- ✅ Manajemen: Tugas, Ujian, Pengguna, Kelas, Mata Pelajaran, Materi
- ✅ IoT & Penelitian: Tugas IoT, Penelitian IoT
- ✅ Analitik: Laporan
- ✅ Pengaturan: Pengaturan, Bantuan

#### Admin (Role ID: 2)
- ✅ Menu Utama: Dashboard, Push Notifikasi, Manajemen IoT
- ✅ Manajemen: Tugas, Ujian, Pengguna, Kelas, Mata Pelajaran, Materi
- ✅ IoT & Penelitian: Tugas IoT, Penelitian IoT
- ✅ Analitik: Laporan
- ✅ Pengaturan: Pengaturan, Bantuan

#### Guru (Role ID: 3)
- ✅ Menu Utama: Dashboard, Tugas Saya, Ujian Saya, Materi Saya
- ❌ Manajemen: (Tidak ada akses)
- ✅ IoT & Penelitian: IoT Dashboard, Devices, Sensor Data
- ✅ Analitik: Laporan
- ✅ Pengaturan: Pengaturan, Bantuan

#### Siswa (Role ID: 4)
- ✅ Menu Utama: Dashboard, Tugas Saya, Ujian Saya, Materi Saya
- ❌ Manajemen: (Tidak ada akses)
- ❌ Analitik: (Tidak ada akses)
- ✅ IoT & Penelitian: Penelitian IoT, Data IoT
- ✅ Pengaturan: Pengaturan, Bantuan

### 3. Responsive Design
- **Desktop**: Sidebar tetap terlihat, bisa di-collapse
- **Mobile**: Sidebar tersembunyi, muncul saat menu toggle ditekan
- **Overlay**: Mobile overlay untuk menutup sidebar saat klik di luar

## Cara Penggunaan

### 1. Menggunakan Layout Unified
```php
@extends('layouts.unified-layout')

@section('title', 'Judul Halaman')
@section('content')
    <!-- Konten halaman -->
@endsection
```

### 2. Role Detection
Layout secara otomatis mendeteksi role user dari `Auth()->user()->roles_id`:
- 1 = Super Admin
- 2 = Admin  
- 3 = Guru
- 4 = Siswa

### 3. Customization
Untuk menyesuaikan menu atau styling, edit file:
- `resources/views/layouts/unified-layout.blade.php`
- `public/css/superadmin-dashboard.css`

## Keuntungan

1. **Konsistensi UI**: Semua role menggunakan interface yang sama
2. **Maintenance**: Lebih mudah maintain karena hanya satu layout
3. **User Experience**: User familiar dengan interface di semua role
4. **Responsive**: Satu sistem responsive untuk semua device
5. **Scalable**: Mudah menambah role baru atau menu baru

## Migration Guide

### Untuk File View yang Sudah Ada:
1. Ganti `@extends('layout.template.dashboard-template')` dengan `@extends('layouts.unified-layout')`
2. Ganti `@extends('layouts.superadmin-layout')` dengan `@extends('layouts.unified-layout')`
3. Ganti `@extends('layouts.guru-layout-new')` dengan `@extends('layouts.unified-layout')`
4. Ganti `@extends('layouts.student-layout')` dengan `@extends('layouts.unified-layout')`

### Untuk Controller:
Tidak perlu perubahan di controller, layout otomatis mendeteksi role user.

## Testing

Untuk test sistem unified layout:
1. Login sebagai Super Admin - pastikan semua menu terlihat
2. Login sebagai Admin - pastikan menu management terlihat
3. Login sebagai Guru - pastikan menu management tersembunyi
4. Login sebagai Siswa - pastikan menu management dan analitik tersembunyi
5. Test responsive di mobile dan desktop

## Troubleshooting

### Menu Tidak Muncul
- Pastikan route sudah didefinisikan di `routes/web.php`
- Pastikan role ID user sudah benar di database

### Styling Tidak Sesuai
- Clear cache CSS: `php artisan cache:clear`
- Pastikan file `public/css/superadmin-dashboard.css` sudah ter-update

### Layout Error
- Pastikan file `resources/views/layouts/unified-layout.blade.php` ada
- Pastikan semua route yang direferensikan ada
