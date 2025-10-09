# Admin Dashboard - Terra Assessment

## Overview
Admin Dashboard adalah sistem manajemen untuk administrator yang memiliki UI yang sama dengan Super Admin Dashboard dengan tema Galaxy yang modern dan responsif.

## Features

### 1. Dashboard Admin
- **File**: `resources/views/dashboard/admin-new.blade.php`
- **Route**: `/admin/dashboard-new`
- **Controller**: `AdminController@dashboard`

**Fitur:**
- Statistik real-time dari database
- Quick actions untuk akses cepat
- Management overview dengan data terkini
- Recent activities timeline
- Responsive design dengan tema Galaxy

### 2. Manajemen Pengajar
- **File**: `resources/views/admin/pengajar-management.blade.php`
- **Route**: `/admin/pengajar-management`
- **Controller**: `AdminController@pengajarManagement`

**Fitur:**
- CRUD lengkap untuk data pengajar
- Search dan filter
- Modal untuk add/edit
- Status management (aktif/tidak aktif)
- Relasi dengan mata pelajaran dan kelas

### 3. Manajemen Siswa
- **File**: `resources/views/admin/siswa-management.blade.php`
- **Route**: `/admin/siswa-management`
- **Controller**: `AdminController@siswaManagement`

**Fitur:**
- CRUD lengkap untuk data siswa
- Statistik siswa per kelas
- Export data functionality
- Search dan filter berdasarkan kelas
- Relasi dengan kelas dan user account

### 4. Manajemen Kelas
- **File**: `resources/views/admin/kelas-management.blade.php`
- **Route**: `/admin/kelas-management`
- **Controller**: `AdminController@kelasManagement`

**Fitur:**
- Grid layout untuk tampilan kelas
- CRUD lengkap untuk data kelas
- Statistik siswa per kelas
- Kapasitas kelas management
- Tingkat kelas (X, XI, XII)

### 5. Manajemen Mata Pelajaran
- **File**: `resources/views/admin/mapel-management.blade.php`
- **Route**: `/admin/mapel-management`
- **Controller**: `AdminController@mapelManagement`

**Fitur:**
- CRUD lengkap untuk mata pelajaran
- Kategori mata pelajaran (sains, sosial, bahasa, matematika)
- Kode mata pelajaran
- Relasi dengan pengajar dan kelas
- Status management

## Database Integration

### Models Used
- `User` - untuk pengajar dan user management
- `DataSiswa` - untuk data siswa
- `Kelas` - untuk data kelas
- `Mapel` - untuk mata pelajaran
- `Materi` - untuk materi pembelajaran
- `Tugas` - untuk tugas
- `Ujian` - untuk ujian

### Relationships
```php
// User (Pengajar)
User::where('roles_id', 3)->with(['mapel', 'kelas'])

// DataSiswa
DataSiswa::with(['kelas', 'user'])

// Kelas
Kelas::with(['siswa', 'mapel'])

// Mapel
Mapel::with(['pengajar', 'kelas'])
```

## Routes

### Admin Routes
```php
// Dashboard
Route::get('/admin/dashboard-new', 'dashboard')->name('admin.dashboard.new');

// Pengajar Management
Route::get('/admin/pengajar-management', 'pengajarManagement')->name('admin.pengajar');
Route::post('/admin/pengajar', 'storePengajar')->name('admin.pengajar.store');
Route::put('/admin/pengajar/{id}', 'updatePengajar')->name('admin.pengajar.update');
Route::delete('/admin/pengajar/{id}', 'deletePengajar')->name('admin.pengajar.delete');

// Siswa Management
Route::get('/admin/siswa-management', 'siswaManagement')->name('admin.siswa');
Route::post('/admin/siswa', 'storeSiswa')->name('admin.siswa.store');
Route::put('/admin/siswa/{id}', 'updateSiswa')->name('admin.siswa.update');
Route::delete('/admin/siswa/{id}', 'deleteSiswa')->name('admin.siswa.delete');

// Kelas Management
Route::get('/admin/kelas-management', 'kelasManagement')->name('admin.kelas');
Route::post('/admin/kelas', 'storeKelas')->name('admin.kelas.store');
Route::put('/admin/kelas/{id}', 'updateKelas')->name('admin.kelas.update');
Route::delete('/admin/kelas/{id}', 'deleteKelas')->name('admin.kelas.delete');

// Mapel Management
Route::get('/admin/mapel-management', 'mapelManagement')->name('admin.mapel');
Route::post('/admin/mapel', 'storeMapel')->name('admin.mapel.store');
Route::put('/admin/mapel/{id}', 'updateMapel')->name('admin.mapel.update');
Route::delete('/admin/mapel/{id}', 'deleteMapel')->name('admin.mapel.delete');
```

## Styling

### CSS File
- **File**: `public/css/admin-dashboard.css`
- **Theme**: Galaxy Theme dengan gradient background
- **Design**: Modern, responsive, dengan glassmorphism effect

### Key CSS Classes
- `.galaxy-card` - Card container dengan glassmorphism
- `.galaxy-button` - Button dengan gradient dan hover effects
- `.data-table` - Table styling dengan hover effects
- `.status-badge` - Badge untuk status (aktif/tidak aktif)
- `.search-input` - Input field dengan focus effects
- `.modal` - Modal dialog styling

## Security

### Middleware
- `auth` - User harus login
- `role:admin` - User harus memiliki role admin (roles_id == 1)

### Validation
- Form validation untuk semua input
- Unique constraints untuk email dan NIS
- Foreign key validation untuk relasi

## JavaScript Features

### Interactive Elements
- Search functionality dengan real-time filtering
- Modal management (open/close)
- Form submission dengan AJAX
- Confirmation dialogs untuk delete actions
- Responsive table interactions

### Search Implementation
```javascript
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
```

## Usage

### Accessing Admin Dashboard
1. Login dengan akun admin (roles_id == 1)
2. Akan diarahkan ke `/admin/dashboard-new`
3. Gunakan sidebar untuk navigasi ke menu manajemen

### Managing Data
1. **Pengajar**: Tambah, edit, hapus data pengajar
2. **Siswa**: Kelola data siswa dan relasi dengan kelas
3. **Kelas**: Buat dan kelola kelas dengan kapasitas
4. **Mata Pelajaran**: Kelola kurikulum dan kategori mapel

### Features Available
- ✅ Real-time data dari database
- ✅ CRUD operations lengkap
- ✅ Search dan filter
- ✅ Responsive design
- ✅ Modern UI/UX
- ✅ Form validation
- ✅ Confirmation dialogs
- ✅ Status management
- ✅ Relationship management

## Dependencies

### Frontend
- Tailwind CSS
- Phosphor Icons
- Font Awesome
- Inter Font Family

### Backend
- Laravel Framework
- Eloquent ORM
- Database relationships
- Form validation
- CSRF protection

## File Structure
```
resources/views/
├── dashboard/
│   └── admin-new.blade.php
└── admin/
    ├── pengajar-management.blade.php
    ├── siswa-management.blade.php
    ├── kelas-management.blade.php
    └── mapel-management.blade.php

app/Http/Controllers/
└── AdminController.php

public/css/
└── admin-dashboard.css

routes/
└── web.php (admin routes)
```

## Notes
- Semua data terhubung ke database backend
- Tidak ada data dummy, semua data real-time
- UI konsisten dengan Super Admin Dashboard
- Responsive untuk mobile dan desktop
- Modern design dengan tema Galaxy
