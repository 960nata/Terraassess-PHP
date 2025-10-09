# Sistem Role dan Permission - Terra Assessment

## Overview
Sistem role dan permission telah diperbaiki sesuai dengan kebutuhan yang diminta. Setiap role memiliki akses dan batasan yang jelas.

## Role dan Permission

### 1. Super Admin (roles_id = 1)
**Akses Penuh:**
- ✅ Dashboard Super Admin
- ✅ Manajemen semua user (Admin, Guru, Siswa)
- ✅ **HANYA Super Admin yang bisa membuat Admin baru**
- ✅ Manajemen Kelas, Mata Pelajaran, Materi
- ✅ Manajemen Tugas, Ujian, IoT
- ✅ Analytics dan Reports
- ✅ Push Notifications
- ✅ IoT Management
- ✅ Task Management
- ✅ Exam Management
- ✅ User Management (semua role)
- ✅ Class Management
- ✅ Subject Management
- ✅ Material Management

### 2. Admin (roles_id = 2)
**Akses Sama seperti Super Admin TAPI dengan batasan:**
- ✅ Dashboard Admin
- ✅ Manajemen Guru dan Siswa (TIDAK bisa buat Admin)
- ❌ **TIDAK bisa membuat user dengan role Admin**
- ✅ Manajemen Kelas, Mata Pelajaran, Materi
- ✅ Manajemen Tugas, Ujian, IoT
- ✅ Analytics dan Reports
- ✅ Push Notifications
- ✅ IoT Management
- ✅ Task Management
- ✅ Exam Management
- ✅ User Management (hanya Guru dan Siswa)
- ✅ Class Management
- ✅ Subject Management
- ✅ Material Management

### 3. Guru/Pengajar (roles_id = 3)
**Akses untuk mengajar dan mengelola konten:**
- ✅ Dashboard Guru
- ✅ Manajemen Materi
- ✅ Manajemen Tugas (semua jenis)
- ✅ Manajemen Ujian (semua jenis)
- ✅ **Manajemen Tugas IoT untuk Siswa**
- ✅ IoT Management
- ✅ Task Management
- ✅ Exam Management
- ✅ Material Management
- ✅ View Reports (terbatas)
- ❌ Tidak bisa manage user lain
- ❌ Tidak bisa manage kelas/mapel

### 4. Siswa (roles_id = 4)
**Akses untuk belajar dan mengerjakan tugas:**
- ✅ Dashboard Siswa
- ✅ **Lihat List Materi**
- ✅ **Lihat List Tugas**
- ✅ **Lihat List Ujian**
- ✅ **Mengerjakan Tugas IoT**
- ✅ Profile Management
- ✅ View Notifications
- ❌ Tidak bisa create/edit konten
- ❌ Tidak bisa manage user lain

## Implementasi Teknis

### 1. Middleware Role
File: `app/Http/Middleware/Role.php`
```php
$roleMap = [
    1 => ['superadmin'],
    2 => ['admin'],
    3 => ['teacher', 'pengajar'],
    4 => ['student', 'siswa'],
];
```

### 2. Controller Restrictions
File: `app/Http/Controllers/AdminController.php`

**Admin User Management:**
- `storeUser()` - Hanya bisa buat Guru (3) dan Siswa (4)
- `updateUser()` - Hanya bisa ubah ke Guru (3) dan Siswa (4)
- Validasi: `'roles_id' => 'required|integer|in:3,4'`

**Super Admin User Management:**
- `createAdminUser()` - Bisa buat semua role (1,2,3,4)
- Validasi: `'roles_id' => 'required|integer|in:1,2,3,4'`

### 3. View Restrictions
File: `resources/views/admin/user-management.blade.php`

**Form Role Selection untuk Admin:**
```html
<select name="roles_id" class="form-input">
    <option value="3">Guru/Pengajar</option>
    <option value="4">Siswa</option>
</select>
<p class="text-sm text-gray-400 mt-1">
    Admin tidak dapat membuat user dengan role admin. 
    Hanya Super Admin yang dapat membuat admin baru.
</p>
```

## Routes dan Access Control

### Super Admin Routes
```php
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // Semua route superadmin
});
```

### Admin Routes
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Semua route admin (sama seperti superadmin)
});
```

### Teacher Routes
```php
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // Route untuk manage materi, tugas, ujian, IoT
});
```

### Student Routes
```php
Route::middleware(['auth', 'role:student'])->group(function () {
    // Route untuk lihat dan mengerjakan tugas
});
```

## Fitur Khusus

### 1. IoT Task Management untuk Guru
- Guru bisa membuat tugas IoT untuk siswa
- Guru bisa monitor progress siswa
- Guru bisa memberikan feedback

### 2. Student IoT Interface
- Siswa bisa melihat tugas IoT yang diberikan
- Siswa bisa mengerjakan tugas IoT
- Siswa bisa submit hasil kerja

### 3. Role-based UI
- Setiap role memiliki dashboard yang sesuai
- Menu dan fitur disesuaikan dengan permission
- Form validation sesuai dengan role

## Security Features

1. **Role Validation di Controller**
2. **Middleware Protection**
3. **View-level Restrictions**
4. **Database-level Constraints**
5. **Error Messages yang Informatif**

## Testing

Untuk test sistem role:

1. **Login sebagai Super Admin** - Pastikan bisa buat Admin
2. **Login sebagai Admin** - Pastikan TIDAK bisa buat Admin
3. **Login sebagai Guru** - Pastikan bisa manage materi/tugas/ujian/IoT
4. **Login sebagai Siswa** - Pastikan hanya bisa lihat dan kerjakan

## Kesimpulan

Sistem role sekarang sudah sesuai dengan kebutuhan:
- ✅ Super Admin: Full access + bisa buat Admin
- ✅ Admin: Full access TAPI tidak bisa buat Admin
- ✅ Guru: Manage konten + IoT tasks
- ✅ Siswa: Lihat dan kerjakan tugas

Semua permission sudah diimplementasi dengan proper validation dan error handling.
