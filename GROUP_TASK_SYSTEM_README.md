# Sistem Tugas Kelompok

Sistem tugas kelompok memungkinkan guru untuk membuat tugas yang harus dikerjakan secara berkelompok oleh siswa, dengan sistem penilaian peer-to-peer yang dilakukan oleh ketua kelompok.

## Fitur Utama

### 1. **Manajemen Tugas Kelompok**
- Guru dapat membuat tugas kelompok dengan detail lengkap
- Menentukan jumlah minimum dan maksimum anggota kelompok
- Mengatur periode pengerjaan tugas
- Memberikan instruksi pengerjaan yang jelas

### 2. **Sistem Keanggotaan Kelompok**
- Siswa dapat bergabung dengan kelompok yang tersedia
- Sistem otomatis mencegah kelebihan anggota
- Siswa dapat keluar dari kelompok (kecuali ketua)
- Tampilan daftar anggota kelompok yang real-time

### 3. **Pemilihan Ketua Kelompok**
- Ketua kelompok dapat memilih ketua baru
- Transfer kepemimpinan yang aman
- Hanya ketua yang dapat melakukan penilaian

### 4. **Sistem Penilaian Peer-to-Peer**
- **Kurang Baik**: 1 poin - Kontribusi sangat minim, tidak aktif dalam kelompok
- **Cukup Baik**: 2 poin - Kontribusi terbatas, kadang aktif dalam kelompok  
- **Baik**: 3 poin - Kontribusi baik, aktif dalam kelompok
- **Sangat Baik**: 4 poin - Kontribusi sangat baik, sangat aktif dan membantu

### 5. **Laporan dan Analisis**
- Ranking anggota berdasarkan total poin
- Grafik perbandingan performa anggota
- Statistik kelompok (total anggota, poin tertinggi, rata-rata, dll)
- Detail penilaian dari setiap evaluator

## Struktur Database

### Tabel `group_tasks`
- `id` - Primary key
- `title` - Judul tugas
- `description` - Deskripsi tugas
- `instructions` - Instruksi pengerjaan
- `teacher_id` - ID guru pembuat
- `class_id` - ID kelas
- `subject_id` - ID mata pelajaran
- `start_date` - Tanggal mulai
- `end_date` - Tanggal selesai
- `max_members` - Maksimal anggota
- `min_members` - Minimal anggota
- `is_active` - Status aktif

### Tabel `group_members`
- `id` - Primary key
- `group_task_id` - ID tugas kelompok
- `student_id` - ID siswa
- `is_leader` - Status ketua kelompok
- `joined_at` - Waktu bergabung

### Tabel `group_evaluations`
- `id` - Primary key
- `group_task_id` - ID tugas kelompok
- `evaluator_id` - ID penilai (ketua kelompok)
- `evaluated_id` - ID yang dinilai
- `rating` - Rating (kurang_baik, cukup_baik, baik, sangat_baik)
- `points` - Poin berdasarkan rating
- `comment` - Komentar penilaian

## Cara Penggunaan

### Untuk Guru:
1. **Buat Tugas Kelompok**
   - Akses menu "Tugas Kelompok"
   - Klik "Buat Tugas Kelompok"
   - Isi detail tugas, instruksi, dan pengaturan kelompok
   - Tentukan periode pengerjaan

2. **Monitor Kelompok**
   - Lihat daftar tugas yang telah dibuat
   - Monitor keanggotaan kelompok
   - Pantau progress penilaian

### Untuk Siswa:
1. **Bergabung dengan Kelompok**
   - Lihat daftar tugas kelompok yang tersedia
   - Klik "Bergabung" pada tugas yang diinginkan
   - Pastikan tidak melebihi batas maksimal anggota

2. **Sebagai Ketua Kelompok**
   - Lakukan penilaian terhadap anggota kelompok
   - Pilih ketua baru jika diperlukan
   - Lihat hasil penilaian dan ranking

3. **Sebagai Anggota Biasa**
   - Lihat anggota kelompok lainnya
   - Tunggu penilaian dari ketua kelompok
   - Lihat hasil penilaian setelah selesai

## Routes

```php
// Group Task Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('group-tasks', GroupTaskController::class);
    Route::post('group-tasks/{groupTask}/join', [GroupTaskController::class, 'join'])->name('group-tasks.join');
    Route::delete('group-tasks/{groupTask}/leave', [GroupTaskController::class, 'leave'])->name('group-tasks.leave');
    Route::patch('group-tasks/{groupTask}/select-leader', [GroupTaskController::class, 'selectLeader'])->name('group-tasks.select-leader');
    Route::get('group-tasks/{groupTask}/evaluation', [GroupTaskController::class, 'evaluationForm'])->name('group-tasks.evaluation');
    Route::post('group-tasks/{groupTask}/evaluation', [GroupTaskController::class, 'submitEvaluation'])->name('group-tasks.submit-evaluation');
    Route::get('group-tasks/{groupTask}/results', [GroupTaskController::class, 'results'])->name('group-tasks.results');
});
```

## File yang Dibuat

### Models:
- `app/Models/GroupTask.php`
- `app/Models/GroupMember.php`
- `app/Models/GroupEvaluation.php`

### Controllers:
- `app/Http/Controllers/GroupTaskController.php`

### Migrations:
- `database/migrations/2024_01_15_000001_create_group_tasks_table.php`
- `database/migrations/2024_01_15_000002_create_group_members_table.php`
- `database/migrations/2024_01_15_000003_create_group_evaluations_table.php`

### Views:
- `resources/views/group-tasks/index.blade.php` - Daftar tugas kelompok
- `resources/views/group-tasks/create.blade.php` - Form buat tugas kelompok
- `resources/views/group-tasks/show.blade.php` - Detail tugas kelompok
- `resources/views/group-tasks/evaluation.blade.php` - Form penilaian
- `resources/views/group-tasks/results.blade.php` - Hasil penilaian

## Keamanan

- Hanya guru yang dapat membuat tugas kelompok
- Hanya siswa dalam kelas yang sama yang dapat bergabung
- Hanya ketua kelompok yang dapat melakukan penilaian
- Validasi input untuk mencegah data tidak valid
- Middleware authentication untuk semua aksi

## Keunggulan Sistem

1. **Kolaboratif**: Mendorong kerja sama antar siswa
2. **Transparan**: Sistem penilaian yang jelas dan adil
3. **Fleksibel**: Dapat disesuaikan dengan berbagai jenis tugas
4. **Real-time**: Update status kelompok secara langsung
5. **Analitik**: Laporan dan grafik yang informatif

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Blade Templates, Bootstrap, Chart.js
- **Database**: MySQL
- **JavaScript**: Vanilla JS untuk interaksi UI
