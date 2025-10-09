# Student Dashboard - Terra Assessment

## Overview
Dashboard siswa yang lengkap dengan fitur-fitur pembelajaran, tugas, ujian, materi, dan penelitian IoT. Dibangun dengan Laravel dan menggunakan UI yang modern dengan efek glassmorphism.

## Features

### 1. Dashboard Utama
- **Statistik Lengkap**: Total materi, tugas selesai, ujian selesai, data IoT
- **Analisis Nilai Per Mata Pelajaran**: Carousel dengan 4 item per slide (desktop) dan 1 item (mobile)
- **Quick Actions**: Akses cepat ke semua fitur
- **Recent Activities**: Tugas, ujian, dan materi terbaru
- **Real-time Clock**: Jam dan tanggal real-time

### 2. Manajemen Tugas
- **Daftar Tugas**: Filter berdasarkan status (semua, belum dikerjakan, sudah dikumpulkan, sudah dinilai, terlambat)
- **Kerjakan Tugas**: Interface untuk mengerjakan tugas dengan auto-save draft
- **Upload File**: Support upload file jawaban (PDF, DOC, DOCX, JPG, PNG)
- **View Hasil**: Lihat hasil dan feedback dari guru
- **Status Tracking**: Real-time status tugas

### 3. Manajemen Ujian
- **Daftar Ujian**: Filter berdasarkan status dan deadline
- **Kerjakan Ujian**: Interface ujian dengan timer otomatis
- **Auto-save Progress**: Progress tersimpan otomatis setiap 30 detik
- **View Hasil**: Review jawaban dan skor
- **Timer**: Countdown timer untuk ujian berdurasi

### 4. Materi Pembelajaran
- **Daftar Materi**: Filter berdasarkan mata pelajaran dan tipe
- **Search**: Pencarian materi
- **File Support**: Support berbagai format file (PDF, DOC, PPT, Video, Image)
- **Preview**: Preview file langsung di browser

### 5. Penelitian IoT
- **Data Collection**: Kumpulkan data sensor IoT (suhu, kelembaban, kadar humus)
- **Bluetooth Integration**: Scan dan koneksi perangkat IoT via Web Bluetooth
- **Manual Input**: Input data manual jika perangkat tidak tersedia
- **Real-time Display**: Tampilan data real-time
- **Export Data**: Export data ke CSV
- **Analisis Kualitas**: Analisis kualitas tanah otomatis

### 6. Profile Management
- **Informasi Profile**: Edit nama, email, telepon, alamat, tentang
- **Ganti Password**: Update password dengan validasi
- **Upload Foto**: Upload dan crop foto profile
- **About**: Informasi tentang aplikasi

## Technical Features

### Backend
- **Laravel 10**: Framework PHP modern
- **Database**: MySQL dengan relasi yang kompleks
- **API**: RESTful API untuk IoT data
- **Validation**: Validasi data yang ketat
- **File Upload**: Support upload file dengan validasi

### Frontend
- **Bootstrap 5**: Framework CSS modern
- **Glassmorphism**: UI dengan efek kaca modern
- **Responsive**: Mobile-first design
- **JavaScript**: Vanilla JS dengan AJAX
- **Web Bluetooth**: Integrasi perangkat IoT
- **Real-time**: Update data real-time

### Database Schema
- **Users**: Data pengguna dengan role-based access
- **Kelas**: Data kelas
- **Mapel**: Data mata pelajaran
- **KelasMapel**: Relasi kelas dan mata pelajaran
- **Materi**: Data materi pembelajaran
- **Tugas**: Data tugas
- **Ujian**: Data ujian
- **Soal**: Data soal ujian
- **UserTugas**: Relasi user dan tugas
- **UserUjian**: Relasi user dan ujian
- **IotReadings**: Data sensor IoT

## Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd terra-assessment
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
php artisan migrate
php artisan db:seed --class=StudentDataSeeder
```

### 5. Storage Setup
```bash
php artisan storage:link
```

### 6. Run Application
```bash
php artisan serve
npm run dev
```

## Usage

### 1. Login sebagai Siswa
- Email: `siswa@example.com`
- Password: `password`

### 2. Dashboard
- Akses dashboard utama dengan statistik lengkap
- Lihat analisis nilai per mata pelajaran
- Quick actions untuk akses cepat

### 3. Tugas
- Lihat daftar tugas dengan filter
- Kerjakan tugas dengan auto-save
- Upload file jawaban
- Lihat hasil dan feedback

### 4. Ujian
- Lihat daftar ujian
- Kerjakan ujian dengan timer
- Auto-save progress
- Lihat hasil dan review

### 5. Materi
- Lihat daftar materi
- Filter berdasarkan mata pelajaran
- Download file materi
- Preview file

### 6. IoT Research
- Scan perangkat IoT via Bluetooth
- Input data manual
- Lihat data real-time
- Export data ke CSV

### 7. Profile
- Edit informasi profile
- Ganti password
- Upload foto profile
- Lihat informasi aplikasi

## API Endpoints

### IoT Data
- `POST /api/iot/readings` - Store IoT reading
- `GET /api/iot/readings/class/{classId}` - Get class readings
- `GET /api/iot/readings/student/{studentId}` - Get student readings
- `GET /api/iot/readings/export` - Export readings to CSV
- `GET /api/iot/readings/realtime` - Get real-time data

## File Structure

```
resources/views/student/
├── dashboard.blade.php          # Dashboard utama
├── tugas.blade.php             # Daftar tugas
├── kerjakan-tugas.blade.php    # Kerjakan tugas
├── ujian.blade.php             # Daftar ujian
├── kerjakan-ujian.blade.php    # Kerjakan ujian
├── materi.blade.php            # Daftar materi
├── iot.blade.php               # Penelitian IoT
└── profile.blade.php           # Profile siswa

app/Http/Controllers/
├── StudentController.php       # Controller utama siswa
└── IotTugasController.php      # Controller IoT

app/Models/
├── UserTugas.php              # Model user tugas
├── UserUjian.php              # Model user ujian
├── IotReading.php             # Model IoT reading
├── Tugas.php                  # Model tugas
├── Ujian.php                  # Model ujian
├── Soal.php                   # Model soal
├── Materi.php                 # Model materi
├── KelasMapel.php             # Model kelas mapel
├── Mapel.php                  # Model mapel
└── Kelas.php                  # Model kelas
```

## Customization

### 1. UI Theme
Edit file `public/asset/css/student-dashboard.css` untuk mengubah tema UI.

### 2. Dashboard Layout
Edit file `resources/views/layout/template/studentTemplate.blade.php` untuk mengubah layout.

### 3. Sidebar Menu
Edit file `resources/views/layout/navbar/student-sidebar.blade.php` untuk mengubah menu sidebar.

### 4. Database Schema
Edit migration files di `database/migrations/` untuk mengubah struktur database.

## Troubleshooting

### 1. Web Bluetooth tidak berfungsi
- Pastikan menggunakan HTTPS
- Pastikan browser mendukung Web Bluetooth
- Gunakan mode manual input

### 2. File upload gagal
- Pastikan folder storage writable
- Check file size limit
- Check file type validation

### 3. Database error
- Pastikan migration berhasil
- Check database connection
- Run seeder untuk data dummy

## Support

Untuk bantuan dan pertanyaan, silakan hubungi tim development atau buat issue di repository.

## License

Proyek ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut.
