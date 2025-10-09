# Komponen Header & Sidebar Terpisah - Terra Assessment

## 📋 Overview

Komponen header dan sidebar telah dipisahkan dari layout utama agar dapat digunakan secara fleksibel di halaman manapun. Ini memungkinkan konsistensi UI yang lebih baik dan kemudahan maintenance.

## 🎯 Komponen yang Tersedia

### 1. **Unified Header** (`components.unified-header`)
- Header dengan logo, menu toggle, notifikasi, dan profile dropdown
- Responsive design untuk desktop dan mobile
- Role-based configuration (Super Admin, Admin, Guru, Siswa)

### 2. **Unified Sidebar** (`components.unified-sidebar`)
- Sidebar dengan menu navigasi berdasarkan role
- Mobile-friendly dengan overlay
- Permission-based menu visibility

### 3. **Mobile Overlay** (`components.mobile-overlay`)
- Overlay untuk mobile sidebar
- Click-to-close functionality

### 4. **Header Styles** (`components.unified-header-styles`)
- CSS styles untuk header dan dropdown
- Dark theme dengan glass morphism effect

### 5. **Header Scripts** (`components.unified-header-scripts`)
- JavaScript untuk functionality header dan sidebar
- Notification system, profile dropdown, mobile responsive

## 🚀 Cara Penggunaan

### **Metode 1: Menggunakan Layout yang Sudah Ada**
```php
@extends('layouts.unified-layout-new')

@section('content')
    <!-- Konten halaman Anda -->
@endsection
```

### **Metode 2: Menggunakan Komponen Secara Terpisah**
```php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Custom</title>
    
    <!-- Include CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Include header styles -->
    @include('components.unified-header-styles')
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Include komponen -->
    @include('components.mobile-overlay')
    @include('components.unified-header')
    @include('components.unified-sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Konten halaman Anda -->
    </main>

    <!-- Include JavaScript -->
    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    @include('components.unified-header-scripts')
</body>
</html>
```

### **Metode 3: Layout Custom dengan Komponen**
```php
@extends('layouts.custom-layout-example')

@section('content')
    <!-- Konten halaman Anda -->
@endsection
```

## 📁 Struktur File

```
resources/views/
├── components/
│   ├── unified-header.blade.php          # Komponen header
│   ├── unified-sidebar.blade.php         # Komponen sidebar
│   ├── mobile-overlay.blade.php          # Komponen mobile overlay
│   ├── unified-header-styles.blade.php   # CSS styles untuk header
│   └── unified-header-scripts.blade.php  # JavaScript untuk header
├── layouts/
│   ├── unified-layout-new.blade.php      # Layout utama (sudah menggunakan komponen)
│   └── custom-layout-example.blade.php   # Contoh layout custom
└── examples/
    └── standalone-header-sidebar.blade.php # Contoh penggunaan standalone
```

## ⚙️ Konfigurasi

### **Role Configuration**
Komponen otomatis mendeteksi role user dan menampilkan:
- **Super Admin**: Crown icon, purple color
- **Admin**: Shield icon, blue color  
- **Guru**: Teacher icon, green color
- **Siswa**: Graduate icon, orange color

### **Menu Configuration**
Menu sidebar otomatis menyesuaikan berdasarkan role:
- Menu yang tidak diizinkan akan disembunyikan
- Permission-based visibility
- Dynamic route generation

## 🎨 Customization

### **Mengubah Warna Role**
Edit di `unified-header.blade.php`:
```php
$roleConfig = [
    1 => ['title' => 'Super Admin', 'icon' => 'fas fa-crown', 'initial' => 'SA', 'color' => 'purple'],
    // Ubah 'color' sesuai kebutuhan
];
```

### **Menambah Menu Sidebar**
Edit di `components/role-sidebar.blade.php`:
```php
<a href="{{ route('custom.route') }}" class="menu-item">
    <i class="fas fa-custom-icon"></i>
    <span class="menu-item-text">Menu Custom</span>
</a>
```

### **Custom CSS**
Tambahkan CSS custom di section styles:
```php
@section('styles')
<style>
    .custom-style {
        /* Custom styles Anda */
    }
</style>
@endsection
```

## 📱 Responsive Features

- **Desktop (>1024px)**: Sidebar selalu terlihat
- **Tablet (768px-1024px)**: Sidebar tersembunyi, toggle dengan hamburger menu
- **Mobile (<768px)**: Sidebar overlay dengan backdrop

## 🔧 JavaScript Functions

### **Available Functions**
- `toggleSidebar()` - Toggle sidebar visibility
- `closeSidebar()` - Close sidebar
- `toggleNotificationDropdown()` - Toggle notification dropdown
- `toggleProfile()` - Toggle profile dropdown
- `loadNotifications()` - Load notifications
- `markAsRead(notificationId)` - Mark notification as read
- `markAllAsRead()` - Mark all notifications as read

## 🎯 Keuntungan

1. **Konsistensi**: Semua halaman menggunakan header dan sidebar yang sama
2. **Maintainability**: Perubahan di satu tempat mempengaruhi semua halaman
3. **Flexibility**: Dapat digunakan di halaman manapun
4. **Responsive**: Otomatis menyesuaikan dengan ukuran layar
5. **Role-based**: Menu dan tampilan menyesuaikan dengan role user

## 📝 Contoh Implementasi

Lihat file `resources/views/examples/standalone-header-sidebar.blade.php` untuk contoh lengkap penggunaan komponen secara standalone.

## 🔄 Update History

- **v1.0**: Komponen header dan sidebar dipisahkan dari layout utama
- **v1.1**: Ditambahkan mobile overlay dan responsive features
- **v1.2**: Ditambahkan notification system dan profile dropdown
- **v1.3**: Optimasi JavaScript dan CSS untuk performa yang lebih baik

## 🚨 Notes

- Pastikan untuk include `superadmin-dashboard.css` untuk styling yang lengkap
- JavaScript `superadmin-dashboard.js` diperlukan untuk functionality sidebar
- CSRF token harus tersedia untuk notification system
- Komponen ini kompatibel dengan semua role (Super Admin, Admin, Guru, Siswa)
