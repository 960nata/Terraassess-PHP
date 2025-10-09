# Sistem Notifikasi Push - TerraAssessment

## Overview
Sistem notifikasi push telah berhasil diimplementasikan untuk admin dan super admin agar dapat mengirim notifikasi langsung ke pengguna platform.

## Fitur yang Tersedia

### 1. Admin Panel
- **Manajemen Notifikasi**: Admin dapat melihat semua notifikasi yang telah dikirim
- **Buat Notifikasi Baru**: Form lengkap untuk membuat notifikasi dengan berbagai opsi target
- **Target Pengiriman**:
  - Broadcast ke semua user (kecuali admin)
  - Berdasarkan role (Pengajar/Siswa)
  - User tertentu (pilih manual)
- **Tipe Notifikasi**: Info, Warning, Success, Error
- **Hapus Notifikasi**: Dapat menghapus notifikasi individual atau semua sekaligus

### 2. User Interface
- **Notifikasi Bell**: Icon bell di header dengan badge jumlah notifikasi unread
- **Dropdown Notifikasi**: Menampilkan 5 notifikasi terbaru dengan preview
- **Halaman Notifikasi**: Halaman khusus untuk melihat semua notifikasi user
- **Mark as Read**: User dapat menandai notifikasi sebagai sudah dibaca
- **Real-time Update**: Notifikasi terupdate otomatis setiap 30 detik

### 3. API Endpoints
- `GET /api/notifications/unread-count` - Mendapatkan jumlah notifikasi unread
- `GET /api/notifications/latest` - Mendapatkan 5 notifikasi terbaru
- `POST /notifications/{id}/mark-read` - Menandai notifikasi sebagai dibaca
- `POST /notifications/mark-all-read` - Menandai semua notifikasi sebagai dibaca

## Struktur Database

### Tabel: notifications
```sql
- id (bigint, primary key)
- user_id (bigint, nullable) - null untuk broadcast
- title (varchar)
- body (text)
- excerpt (text, nullable)
- type (varchar) - info, warning, success, error
- is_read (boolean, default false)
- read_at (timestamp, nullable)
- data (json, nullable) - data tambahan
- created_at (timestamp)
- updated_at (timestamp)
```

## Cara Menggunakan

### Untuk Admin:
1. Login sebagai admin
2. Akses menu "Notifikasi" di dashboard admin
3. Klik "Buat Notifikasi" untuk membuat notifikasi baru
4. Pilih target pengiriman dan isi detail notifikasi
5. Klik "Kirim Notifikasi"

### Untuk User:
1. Lihat icon bell di header (akan ada badge merah jika ada notifikasi unread)
2. Klik icon bell untuk melihat notifikasi terbaru
3. Klik "Lihat Semua" untuk melihat semua notifikasi
4. Klik notifikasi untuk menandai sebagai sudah dibaca

## File yang Dibuat/Dimodifikasi

### Controller
- `app/Http/Controllers/NotificationController.php` - Controller utama untuk notifikasi

### Views
- `resources/views/menu/admin/notifications/index.blade.php` - Halaman manajemen notifikasi admin
- `resources/views/menu/admin/notifications/create.blade.php` - Form buat notifikasi
- `resources/views/notifications/user.blade.php` - Halaman notifikasi user

### Database
- `database/migrations/2025_09_20_223909_create_notifications_table.php` - Migrasi tabel
- `database/seeders/NotificationSeeder.php` - Seeder data contoh

### Routes
- Ditambahkan di `routes/web.php` dengan prefix admin dan user

### Template
- `resources/views/layout/template/mainTemplate.blade.php` - Ditambahkan notifikasi bell dan JavaScript

## Keamanan
- Hanya admin (roles_id = 1) yang dapat mengakses fitur manajemen notifikasi
- User hanya dapat melihat notifikasi yang ditujukan untuk mereka atau broadcast
- CSRF protection untuk semua form dan API calls
- Validasi input yang ketat

## Real-time Features
- Auto refresh notifikasi setiap 30 detik
- Badge notifikasi terupdate otomatis
- Dropdown notifikasi menampilkan data real-time
- Mark as read tanpa perlu refresh halaman

## Testing
Untuk testing fitur notifikasi:
1. Login sebagai admin dan buat beberapa notifikasi
2. Login sebagai user dan lihat notifikasi di bell icon
3. Test berbagai tipe notifikasi dan target pengiriman
4. Verifikasi real-time update berfungsi dengan baik
