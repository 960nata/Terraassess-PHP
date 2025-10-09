# User Management System - Terra Assessment

## Overview
Sistem manajemen pengguna yang memungkinkan Super Admin untuk mengelola user dengan role Admin, Guru, dan Siswa. SuperAdmin hanya dapat dilihat dan tidak dapat dibuat atau diubah melalui interface ini.

## Fitur Utama

### 1. Role yang Dapat Dibuat
- ✅ **Admin** - Dapat mengelola sistem
- ✅ **Guru** - Dapat mengelola materi, tugas, dan ujian
- ✅ **Siswa** - Dapat mengakses materi dan mengerjakan tugas
- ❌ **SuperAdmin** - Hanya dapat dilihat, tidak dapat dibuat atau diubah

### 2. Pembatasan SuperAdmin
- SuperAdmin tidak dapat dibuat melalui form
- SuperAdmin tidak dapat diedit melalui interface
- SuperAdmin tidak dapat dihapus
- SuperAdmin tetap ditampilkan dalam daftar pengguna (read-only)
- Card SuperAdmin ditampilkan dengan status disabled

### 3. Fitur Manajemen Pengguna
- **Tambah Pengguna Baru**: Form untuk membuat user dengan role Admin, Guru, atau Siswa
- **Filter Pengguna**: Filter berdasarkan role, status, kelas, dan pencarian nama/email
- **Lihat Detail**: Melihat informasi lengkap pengguna
- **Edit Pengguna**: Mengubah informasi pengguna (kecuali SuperAdmin)
- **Reset Password**: Reset password pengguna (kecuali SuperAdmin)
- **Hapus Pengguna**: Menghapus pengguna (kecuali SuperAdmin)
- **Approve Pengguna**: Menyetujui pengguna yang statusnya pending

## Struktur File

### Controller
- **File**: `app/Http/Controllers/DashboardController.php`
- **Methods**:
  - `viewSuperAdminUserManagement()` - Menampilkan halaman manajemen pengguna
  - `createSuperAdminUser()` - Membuat user baru (Admin, Guru, Siswa)
  - `filterSuperAdminUsers()` - Filter pengguna berdasarkan kriteria

### View
- **File**: `resources/views/superadmin/user-management.blade.php`
- **Sections**:
  - Statistics Cards - Menampilkan statistik pengguna
  - Role Cards - Quick action untuk membuat user berdasarkan role
  - Create User Form - Form untuk membuat user baru
  - Filter Section - Filter pengguna
  - User Table - Daftar pengguna dengan aksi

### Routes
- `GET /superadmin/user-management` - Halaman manajemen pengguna
- `POST /superadmin/user-management/create` - Membuat user baru
- `GET /superadmin/user-management/filter` - Filter pengguna

## Validasi

### Create User
```php
'user_name' => 'required|string|max:255',
'user_email' => 'required|email|unique:users,email',
'user_role' => 'required|string|in:admin,teacher,student', // SuperAdmin tidak diperbolehkan
'class_id' => 'nullable|string',
'user_password' => 'required|string|min:8',
'confirm_password' => 'required|string|min:8|same:user_password',
```

### Validasi Tambahan
- Jika `user_role` adalah `superadmin`, akan muncul error message:
  ```
  "Tidak dapat membuat SuperAdmin melalui interface ini. SuperAdmin hanya dapat dilihat."
  ```

## JavaScript Functions

### Role Selection
```javascript
createStudent() // Set role to student
createTeacher() // Set role to teacher
createAdmin() // Set role to admin
// createSuperAdmin() tidak tersedia
```

### User Actions
```javascript
viewUser(userId)      // Lihat detail pengguna
editUser(userId)      // Edit pengguna (kecuali SuperAdmin)
resetPassword(userId) // Reset password (kecuali SuperAdmin)
approveUser(userId)   // Approve pengguna pending
deleteUser(userId)    // Hapus pengguna (kecuali SuperAdmin)
```

## Role Mapping

| Role ID | Role Name | Can Create | Can Edit | Can Delete |
|---------|-----------|------------|----------|------------|
| 1 | Super Admin | ❌ | ❌ | ❌ |
| 2 | Admin | ✅ | ✅ | ✅ |
| 3 | Guru | ✅ | ✅ | ✅ |
| 4 | Siswa | ✅ | ✅ | ✅ |

## Statistik Pengguna

Dashboard menampilkan:
- Total Pengguna
- Total Siswa
- Total Guru
- Pengguna Aktif

## Filter Options

1. **Role Pengguna**: Semua Role / Siswa / Guru / Admin / Super Admin
2. **Status**: Semua Status / Aktif / Tidak Aktif / Menunggu
3. **Kelas**: Semua Kelas / [Daftar Kelas]
4. **Cari Pengguna**: Pencarian berdasarkan nama atau email

## UI/UX Features

### Role Cards
- Setiap role memiliki card dengan warna yang berbeda
- SuperAdmin card ditampilkan dengan opacity 0.6 dan cursor not-allowed
- Tooltip menjelaskan bahwa SuperAdmin hanya dapat dilihat

### User Table
- Menampilkan informasi: Nama, Email, Role, Kelas, Status, Terakhir Login, Aksi
- SuperAdmin ditampilkan dengan badge khusus
- Aksi untuk SuperAdmin diganti dengan pesan "Tidak dapat diubah"

## Security

1. **Authorization**: Hanya SuperAdmin yang dapat mengakses halaman ini
2. **Validation**: Validasi input untuk mencegah pembuatan SuperAdmin
3. **Role Protection**: SuperAdmin tidak dapat diedit atau dihapus melalui interface
4. **CSRF Protection**: Form dilindungi dengan CSRF token

## Implementasi

### Cara Membuat User Baru
1. Login sebagai SuperAdmin
2. Akses menu "Manajemen Pengguna"
3. Klik salah satu role card (Siswa/Guru/Admin) atau isi form secara manual
4. Isi data pengguna
5. Klik "Tambah Pengguna"

### Cara Filter Pengguna
1. Gunakan filter section di bagian atas table
2. Pilih kriteria filter (role, status, kelas, pencarian)
3. Klik "Terapkan Filter"
4. Klik "Reset Filter" untuk menampilkan semua pengguna

## Error Handling

- Validation errors ditampilkan di form
- Error messages ditampilkan dengan notifikasi
- Success messages ditampilkan setelah aksi berhasil

## Future Improvements

1. Export data pengguna ke Excel/PDF
2. Bulk actions (approve, delete multiple users)
3. Email notification untuk user baru
4. Advanced analytics dan reporting
5. User activity logs
6. Profile picture upload
7. Bulk import users dari CSV

## Catatan Penting

⚠️ **PENTING**: SuperAdmin hanya dapat dibuat melalui seeder atau direct database access. Tidak ada interface untuk membuat SuperAdmin melalui aplikasi web.

## Contact

Untuk pertanyaan atau bantuan, hubungi tim development Terra Assessment.

---

**Last Updated**: October 2, 2025
**Version**: 1.0.0

