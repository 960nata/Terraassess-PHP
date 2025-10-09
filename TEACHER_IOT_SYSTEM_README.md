# Teacher IoT System - Terra Assessment

## 🎯 **Overview**

Sistem IoT untuk guru yang memungkinkan monitoring dan analisis data sensor per kelas. Guru dapat mengakses data IoT dari semua kelas yang diampu, namun data tetap terbatas per kelas untuk keamanan dan organisasi.

## 🔧 **Fitur Utama**

### **1. Dashboard IoT Guru**
- **Lokasi**: `/teacher/iot/dashboard`
- **Akses**: Hanya guru (roles_id == 3)
- **Fitur**:
  - Statistik overview (total device, online device, total data, data hari ini)
  - Daftar kelas yang diampu
  - Data sensor real-time
  - Status device
  - Timeline data terbaru

### **2. Data IoT Per Kelas**
- **Lokasi**: `/teacher/iot/class/{kelasId}`
- **Fitur**:
  - Data sensor spesifik per kelas
  - Grafik real-time
  - Proyek penelitian per kelas
  - Nilai sensor saat ini

### **3. Manajemen Device**
- **Lokasi**: `/teacher/iot/devices`
- **Fitur**:
  - Daftar semua device dari kelas yang diampu
  - Status device (online/offline)
  - Data sensor terbaru per device
  - Detail device

### **4. Data Sensor**
- **Lokasi**: `/teacher/iot/sensor-data`
- **Fitur**:
  - Filter berdasarkan device, kelas, tanggal
  - Tabel data sensor dengan pagination
  - Kualitas tanah otomatis
  - Export data
  - Detail data sensor

### **5. Proyek Penelitian**
- **Lokasi**: `/teacher/iot/research-projects`
- **Fitur**:
  - Buat proyek penelitian per kelas
  - Monitoring progress proyek
  - Analisis data proyek
  - Export laporan

## 🗄️ **Struktur Database**

### **Tabel `iot_sensor_data`**
```sql
- id (primary key)
- device_id (foreign key ke iot_devices)
- kelas_id (foreign key ke kelas)
- user_id (foreign key ke users - guru yang melakukan pengukuran)
- temperature (decimal 5,2)
- humidity (decimal 5,2)
- soil_moisture (decimal 5,2)
- ph_level (decimal 4,2)
- nutrient_level (decimal 5,2)
- location (string, nullable)
- notes (text, nullable)
- raw_data (json, nullable)
- measured_at (timestamp)
- created_at, updated_at
```

### **Tabel `research_projects`**
```sql
- id (primary key)
- title (string)
- description (text)
- kelas_id (foreign key ke kelas)
- pengajar_id (foreign key ke users)
- objectives (text)
- methodology (text)
- expected_outcomes (text)
- status (enum: active, completed, paused)
- start_date, end_date
- created_at, updated_at
```

## 🔐 **Keamanan & Akses**

### **Pembatasan Per Kelas**
- ✅ Data IoT hanya muncul di kelas yang diampu guru
- ✅ Guru tidak bisa akses data kelas lain
- ✅ Filter otomatis berdasarkan `kelas_id` dari `kelas_mapel`

### **Middleware & Role**
- ✅ `auth` - User harus login
- ✅ `role:teacher` - Hanya guru yang bisa akses
- ✅ Verifikasi akses kelas di controller

### **Validasi Data**
- ✅ Validasi input sensor data
- ✅ Verifikasi device exists
- ✅ Verifikasi guru memiliki akses ke kelas

## 📊 **API Endpoints**

### **Dashboard & Data**
```php
GET  /teacher/iot/dashboard              // Dashboard utama
GET  /teacher/iot/class/{kelasId}        // Data per kelas
GET  /teacher/iot/devices                // Daftar device
GET  /teacher/iot/sensor-data            // Data sensor dengan filter
GET  /teacher/iot/research-projects      // Proyek penelitian
```

### **API Real-time**
```php
GET  /teacher/iot/realtime               // Data real-time
GET  /teacher/iot/device-status          // Status device
POST /teacher/iot/sensor-data            // Simpan data sensor
POST /teacher/iot/create-project         // Buat proyek penelitian
```

## 🎨 **UI/UX Features**

### **Galaxy Theme**
- ✅ Konsisten dengan Super Admin dashboard
- ✅ Dark theme dengan efek glassmorphism
- ✅ Animasi hover dan transisi smooth
- ✅ Responsive design

### **Real-time Updates**
- ✅ Auto refresh setiap 30 detik
- ✅ Status device real-time
- ✅ Grafik data sensor live
- ✅ Notifikasi status device

### **Interactive Elements**
- ✅ Modal untuk detail data
- ✅ Filter data yang powerful
- ✅ Export functionality
- ✅ Chart.js untuk visualisasi

## 🔄 **Workflow Sistem**

### **1. Guru Login**
```
Guru login → Cek role → Redirect ke IoT Dashboard
```

### **2. Akses Data Kelas**
```
Pilih kelas → Verifikasi akses → Tampilkan data kelas
```

### **3. Monitoring Device**
```
Device mengirim data → Simpan ke database → Update dashboard
```

### **4. Analisis Data**
```
Filter data → Analisis kualitas → Buat laporan
```

## 📱 **Responsive Design**

### **Desktop (1024px+)**
- Grid layout 3-4 kolom
- Sidebar navigation
- Full feature set

### **Tablet (768px-1023px)**
- Grid layout 2 kolom
- Collapsible sidebar
- Touch-friendly buttons

### **Mobile (< 768px)**
- Single column layout
- Bottom navigation
- Swipe gestures

## 🚀 **Cara Penggunaan**

### **1. Akses Dashboard**
```bash
# Login sebagai guru
# Navigate ke /teacher/iot/dashboard
```

### **2. Lihat Data Kelas**
```bash
# Klik nama kelas di dashboard
# Atau navigate ke /teacher/iot/class/{kelasId}
```

### **3. Filter Data Sensor**
```bash
# Navigate ke /teacher/iot/sensor-data
# Gunakan filter dropdown dan date picker
# Klik "Filter" untuk menerapkan
```

### **4. Buat Proyek Penelitian**
```bash
# Navigate ke /teacher/iot/research-projects
# Klik "Buat Proyek"
# Isi form dan submit
```

## 🔧 **Konfigurasi**

### **Environment Variables**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=terra_assessment
DB_USERNAME=root
DB_PASSWORD=
```

### **Cache & Performance**
```php
// Real-time data caching
Cache::remember('iot_realtime_data', 30, function() {
    return IotSensorData::latest('measured_at')->limit(50)->get();
});
```

## 📈 **Monitoring & Analytics**

### **Metrics yang Dilacak**
- Total device per guru
- Data sensor per hari/minggu/bulan
- Kualitas tanah per kelas
- Progress proyek penelitian
- Status device (online/offline)

### **Alerts & Notifications**
- Device offline > 5 menit
- Data sensor abnormal
- Proyek penelitian deadline
- Error dalam sistem

## 🛠️ **Troubleshooting**

### **Common Issues**

#### **1. Data tidak muncul**
```bash
# Cek apakah guru memiliki akses ke kelas
# Verifikasi data ada di database
# Cek filter yang diterapkan
```

#### **2. Device tidak terdeteksi**
```bash
# Cek status device di database
# Verifikasi device_id benar
# Cek koneksi device ke sistem
```

#### **3. Real-time tidak update**
```bash
# Cek JavaScript console untuk error
# Verifikasi API endpoint
# Cek network connectivity
```

## 🔮 **Future Enhancements**

### **Planned Features**
- [ ] Machine learning untuk prediksi kualitas tanah
- [ ] Integration dengan weather API
- [ ] Mobile app untuk monitoring
- [ ] Advanced analytics dashboard
- [ ] Automated reporting
- [ ] IoT device management
- [ ] Data export ke Excel/PDF
- [ ] Email notifications
- [ ] SMS alerts untuk kondisi kritis

### **Performance Improvements**
- [ ] Redis caching untuk real-time data
- [ ] Database indexing optimization
- [ ] CDN untuk static assets
- [ ] Lazy loading untuk data besar
- [ ] WebSocket untuk real-time updates

## 📚 **Dependencies**

### **Backend**
- Laravel 10.x
- MySQL 8.0+
- PHP 8.1+

### **Frontend**
- Tailwind CSS 3.x
- Chart.js 4.x
- Phosphor Icons
- Font Awesome 6.x

### **JavaScript Libraries**
- Chart.js untuk grafik
- Axios untuk API calls
- Moment.js untuk date handling

## 🎯 **Kesimpulan**

Sistem IoT untuk guru di Terra Assessment telah berhasil diimplementasikan dengan:

✅ **Keamanan**: Data terbatas per kelas, akses terbatas per role
✅ **Fungsionalitas**: Monitoring, analisis, dan manajemen data IoT
✅ **UI/UX**: Konsisten dengan Galaxy Theme, responsive design
✅ **Performance**: Real-time updates, caching, optimization
✅ **Scalability**: Struktur database yang baik, API yang terorganisir

Sistem ini memungkinkan guru untuk:
- Monitor data sensor dari semua kelas yang diampu
- Menganalisis kualitas tanah dan kondisi lingkungan
- Membuat dan mengelola proyek penelitian
- Melakukan analisis data yang mendalam
- Menghasilkan laporan yang komprehensif

**Sistem siap digunakan untuk monitoring IoT per kelas dengan akses terbatas untuk guru!** 🚀
