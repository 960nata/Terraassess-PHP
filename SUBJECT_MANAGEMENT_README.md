# Manajemen Mata Pelajaran - Sistem Baru

## Overview
Sistem Manajemen Mata Pelajaran yang telah diperbarui dengan desain yang lebih sederhana dan fungsional. Menghilangkan card kategori yang membingungkan dan menggantinya dengan tombol "+ Mata Pelajaran" yang langsung membuka popup untuk menambah mata pelajaran baru.

## 🎯 Perubahan Utama

### ❌ **Yang Dihapus:**
- Card kategori (Umum, Kejuruan, Agama, Olahraga)
- Form create yang kompleks
- Interface yang membingungkan

### ✅ **Yang Ditambahkan:**
- Tombol "+ Mata Pelajaran" yang jelas
- Popup modal untuk menambah mata pelajaran
- List mata pelajaran dengan fitur edit dan delete
- Pencarian dan filter yang mudah digunakan
- Interface yang clean dan modern

## 🛠️ Fitur Utama

### 1. **Tombol + Mata Pelajaran**
- Tombol yang jelas di header
- Langsung membuka popup untuk menambah mata pelajaran
- Desain yang menarik dengan hover effects

### 2. **Popup Tambah Mata Pelajaran**
- **Nama Mata Pelajaran**: Input text untuk nama
- **Kategori**: Dropdown (Akademik, Sains, Bahasa, Sosial, Seni)
- **Kode Mata Pelajaran**: Input opsional untuk kode unik
- **Deskripsi**: Textarea untuk deskripsi detail
- **Status**: Aktif/Tidak Aktif

### 3. **List Mata Pelajaran**
- **Tabel Responsif**: Menampilkan semua mata pelajaran
- **Informasi Lengkap**: Nama, kategori, status, jumlah guru, jumlah kelas
- **Aksi Cepat**: Tombol Edit dan Hapus untuk setiap mata pelajaran
- **Status Badge**: Visual indicator untuk status aktif/tidak aktif

### 4. **Fitur Edit & Delete**
- **Edit**: Modal popup untuk mengedit mata pelajaran
- **Delete**: Konfirmasi sebelum menghapus
- **Validasi**: Cek apakah mata pelajaran sedang digunakan

### 5. **Pencarian & Filter**
- **Search**: Cari berdasarkan nama mata pelajaran
- **Filter Kategori**: Filter berdasarkan kategori
- **Real-time**: Filter langsung tanpa reload halaman

## 📁 File yang Dibuat/Dimodifikasi

### 1. **View**
- `resources/views/superadmin/subject-management-new.blade.php`
  - Interface baru yang clean dan modern
  - Modal untuk tambah/edit mata pelajaran
  - Tabel responsif dengan aksi

### 2. **Controller**
- `app/Http/Controllers/SuperAdmin/SubjectManagementController.php`
  - `index()` - Tampilkan halaman utama
  - `store()` - Tambah mata pelajaran baru
  - `edit()` - Ambil data untuk edit
  - `update()` - Update mata pelajaran
  - `destroy()` - Hapus mata pelajaran
  - `search()` - Pencarian dan filter

### 3. **Model**
- `app/Models/Mapel.php`
  - Menambahkan field: `kategori`, `code`, `is_active`
  - Update `$fillable` array

### 4. **Migration**
- `database/migrations/2025_10_04_073510_add_fields_to_mapels_table.php`
  - Menambahkan kolom `kategori` (string, default: 'akademik')
  - Menambahkan kolom `code` (string, nullable, unique)
  - Menambahkan kolom `is_active` (boolean, default: true)

### 5. **Routes**
- `GET /superadmin/subject-management-new` - Halaman utama
- `POST /superadmin/subject-management` - Tambah mata pelajaran
- `GET /superadmin/subject-management/{id}/edit` - Data edit
- `PUT /superadmin/subject-management/{id}` - Update mata pelajaran
- `DELETE /superadmin/subject-management/{id}` - Hapus mata pelajaran
- `GET /superadmin/subject-management-search` - Pencarian

## 🎨 UI/UX Design

### **Header Section**
```css
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}
```

### **Add Button**
```css
.add-subject-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: all 0.3s ease;
}
```

### **Table Design**
- Clean white background
- Hover effects pada rows
- Status badges dengan warna yang jelas
- Action buttons yang mudah diakses

### **Modal Design**
- Centered modal dengan backdrop
- Form yang terorganisir dengan baik
- Button actions yang jelas
- Responsive design

## 🔧 Cara Penggunaan

### 1. **Menambah Mata Pelajaran**
1. Klik tombol "+ Mata Pelajaran" di header
2. Isi form yang muncul:
   - Nama mata pelajaran (wajib)
   - Pilih kategori
   - Masukkan kode (opsional)
   - Tulis deskripsi (opsional)
   - Pilih status
3. Klik "Simpan"

### 2. **Mengedit Mata Pelajaran**
1. Klik tombol "Edit" pada mata pelajaran yang ingin diedit
2. Modal edit akan terbuka dengan data yang sudah terisi
3. Ubah data yang diperlukan
4. Klik "Update"

### 3. **Menghapus Mata Pelajaran**
1. Klik tombol "Hapus" pada mata pelajaran yang ingin dihapus
2. Konfirmasi penghapusan
3. Mata pelajaran akan dihapus jika tidak sedang digunakan

### 4. **Mencari Mata Pelajaran**
1. Gunakan search box untuk mencari berdasarkan nama
2. Gunakan dropdown filter untuk filter berdasarkan kategori
3. Hasil akan update secara real-time

## 📊 Database Schema

### **Tabel `mapels`**
```sql
- id (primary key)
- name (string) - Nama mata pelajaran
- deskripsi (text) - Deskripsi mata pelajaran
- gambar (string) - Path gambar (existing)
- kategori (string) - Kategori mata pelajaran
- code (string, unique) - Kode unik mata pelajaran
- is_active (boolean) - Status aktif/tidak aktif
- created_at (timestamp)
- updated_at (timestamp)
```

## 🔒 Validasi & Keamanan

### **Input Validation**
- Nama mata pelajaran: required, unique, max 255 karakter
- Kategori: required, enum (akademik, sains, bahasa, sosial, seni)
- Kode: optional, unique, max 50 karakter
- Deskripsi: optional, max 1000 karakter
- Status: boolean

### **Business Logic**
- Cek apakah mata pelajaran sedang digunakan sebelum hapus
- Validasi unik untuk nama dan kode
- Soft delete untuk data integrity

## 🚀 JavaScript Functions

### **Modal Management**
- `openAddSubjectModal()` - Buka modal tambah
- `closeAddSubjectModal()` - Tutup modal tambah
- `openEditSubjectModal(id)` - Buka modal edit
- `closeEditSubjectModal()` - Tutup modal edit
- `openDeleteSubjectModal(id, name)` - Buka konfirmasi hapus

### **Data Management**
- `loadSubjectData(id)` - Load data untuk edit
- `confirmDeleteSubject()` - Konfirmasi hapus
- `searchSubjects()` - Pencarian real-time
- `filterSubjects()` - Filter berdasarkan kategori

### **AJAX Calls**
- Load data edit via AJAX
- Update data via AJAX
- Delete data via AJAX
- Search dan filter via AJAX

## 📱 Responsive Design

### **Breakpoints**
- Desktop: 1200px+
- Tablet: 768px - 1199px
- Mobile: < 768px

### **Mobile Optimizations**
- Stacked layout pada mobile
- Touch-friendly buttons
- Optimized modal size
- Responsive table dengan horizontal scroll

## 🎯 Kategori Mata Pelajaran

### **Akademik**
- Matematika, Bahasa Indonesia, Bahasa Inggris
- Sejarah, Geografi, Ekonomi
- Sosiologi, Antropologi

### **Sains**
- Fisika, Kimia, Biologi
- IPA Terpadu, Matematika Sains

### **Bahasa**
- Bahasa Indonesia, Bahasa Inggris
- Bahasa Asing lainnya

### **Sosial**
- Sejarah, Geografi, Ekonomi
- Sosiologi, Antropologi, PPKn

### **Seni**
- Seni Rupa, Seni Musik, Seni Tari
- Seni Teater, Seni Kriya

## 🔄 Migration & Update

### **Database Migration**
```bash
php artisan migrate --path=database/migrations/2025_10_04_073510_add_fields_to_mapels_table.php
```

### **Rollback (jika diperlukan)**
```bash
php artisan migrate:rollback --path=database/migrations/2025_10_04_073510_add_fields_to_mapels_table.php
```

## 🐛 Troubleshooting

### **Common Issues**
1. **Modal tidak terbuka**: Check JavaScript console untuk errors
2. **Data tidak tersimpan**: Check validation rules dan database connection
3. **Delete gagal**: Pastikan mata pelajaran tidak sedang digunakan
4. **Search tidak bekerja**: Check JavaScript function calls

### **Debug Steps**
1. Check browser console untuk JavaScript errors
2. Check Laravel logs untuk server errors
3. Verify database connection
4. Check route definitions

## 📈 Future Enhancements

### **Planned Features**
- [ ] Bulk import mata pelajaran dari Excel
- [ ] Export data mata pelajaran
- [ ] Drag & drop untuk reorder mata pelajaran
- [ ] Advanced filtering options
- [ ] Mata pelajaran templates
- [ ] Audit trail untuk perubahan

### **Advanced Features**
- [ ] Mata pelajaran hierarchy (parent-child)
- [ ] Custom fields untuk mata pelajaran
- [ ] Integration dengan sistem penilaian
- [ ] Analytics untuk penggunaan mata pelajaran

## 📞 Support

Untuk bantuan teknis atau pertanyaan tentang sistem manajemen mata pelajaran, silakan hubungi tim development atau buat issue di repository project.

---

**Sistem Manajemen Mata Pelajaran v2.0** - Desain yang lebih sederhana, fungsional, dan user-friendly untuk mengelola mata pelajaran dengan efisien.
