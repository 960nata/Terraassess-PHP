# 🌡️ Sistem IoT Monitoring Tanah - TerraAssessment

Sistem monitoring IoT untuk mengukur suhu tanah, kelembaban, dan kualitas tanah dengan koneksi Bluetooth cross-platform.

## 🚀 Fitur Utama

### 📱 **Cross-Platform Bluetooth**
- ✅ **Chrome/Edge**: Web Bluetooth API
- ✅ **Safari**: Web Bluetooth API (iOS 13+)
- ✅ **Firefox**: Web Bluetooth API (Experimental)
- ✅ **Mobile Browsers**: Android Chrome, iOS Safari
- ✅ **Desktop**: Windows, Mac, Linux

### 🔬 **Monitoring Real-time**
- **Suhu Tanah**: Pengukuran suhu dalam Celsius
- **Kelembaban Udara**: Pengukuran kelembaban dalam persen
- **Kelembaban Tanah**: Pengukuran kelembaban tanah dalam persen
- **pH Tanah**: Pengukuran tingkat keasaman tanah
- **Level Nutrisi**: Pengukuran kandungan nutrisi tanah

### 📊 **Dashboard Penelitian**
- Proyek penelitian per kelas
- Analisis data sensor
- Grafik real-time
- Export data penelitian

## 🛠️ Instalasi & Setup

### 1. Database Migration
```bash
php artisan migrate
```

### 2. Jalankan Server
```bash
php artisan serve
```

### 3. Akses IoT Dashboard
```
http://localhost:8000/iot/dashboard
```

## 📱 Cara Menggunakan Koneksi IoT

### **🔌 USB Connection (Baru!)**
1. Buka dashboard IoT
2. Pilih metode "USB" di panel koneksi
3. Klik tombol "Hubungkan"
4. Pilih perangkat USB dari daftar
5. Koneksi langsung via kabel USB (jarak 1m, stabil)

**Keuntungan USB:**
- ✅ Koneksi stabil dan reliable
- ✅ Tidak ada interference
- ✅ Power supply langsung dari komputer
- ✅ Latency rendah

**Keterbatasan USB:**
- ⚠️ Jarak terbatas (1m)
- ⚠️ Perlu kabel USB
- ⚠️ Tidak mobile-friendly
- ⚠️ Browser support terbatas

### **📶 Bluetooth Connection**
1. Buka dashboard IoT
2. Pilih metode "Bluetooth" di panel koneksi
3. Klik tombol "Hubungkan"
4. Pilih perangkat IoT dari daftar
5. Klik "Pair" untuk menghubungkan

**Keuntungan Bluetooth:**
- ✅ Wireless (jarak hingga 10m)
- ✅ Mobile-friendly
- ✅ Cross-platform support
- ✅ Mudah digunakan

**Keterbatasan Bluetooth:**
- ⚠️ Bisa terputus karena interference
- ⚠️ Konsumsi baterai perangkat IoT
- ⚠️ Latency sedikit lebih tinggi

## 🔧 Konfigurasi Perangkat IoT

### **Arduino/ESP32 Setup**
```cpp
// UUID untuk service dan characteristic
#define SERVICE_UUID "12345678-1234-1234-1234-123456789abc"
#define CHARACTERISTIC_UUID "87654321-4321-4321-4321-cba987654321"

// Format data JSON
{
  "temperature": 25.5,
  "humidity": 60.2,
  "soil_moisture": 45.8,
  "ph_level": 6.5,
  "nutrient_level": 75.0
}
```

### **Sensor yang Didukung**
- **DHT22**: Suhu dan kelembaban udara
- **Soil Moisture Sensor**: Kelembaban tanah
- **pH Sensor**: Tingkat keasaman tanah
- **NPK Sensor**: Kandungan nutrisi tanah

## 📊 Struktur Database

### **Tabel: iot_devices**
```sql
- id: Primary key
- device_name: Nama perangkat
- device_id: ID unik perangkat
- bluetooth_address: Alamat Bluetooth
- device_type: Jenis perangkat
- status: Status perangkat (online/offline)
- last_seen: Waktu terakhir terhubung
```

### **Tabel: iot_sensor_data**
```sql
- id: Primary key
- device_id: Foreign key ke iot_devices
- kelas_id: Foreign key ke kelas
- user_id: Foreign key ke users (guru)
- temperature: Suhu tanah
- humidity: Kelembaban udara
- soil_moisture: Kelembaban tanah
- ph_level: pH tanah
- nutrient_level: Level nutrisi
- location: Lokasi pengukuran
- notes: Catatan tambahan
- measured_at: Waktu pengukuran
```

### **Tabel: research_projects**
```sql
- id: Primary key
- project_name: Nama proyek
- description: Deskripsi proyek
- kelas_id: Foreign key ke kelas
- teacher_id: Foreign key ke users
- status: Status proyek (active/completed/paused)
- start_date: Tanggal mulai
- end_date: Tanggal selesai
- research_parameters: Parameter penelitian (JSON)
- conclusion: Kesimpulan penelitian
```

## 🎯 API Endpoints

### **Web Routes**
```
GET  /iot/dashboard              - Dashboard IoT
GET  /iot/devices               - Kelola perangkat
GET  /iot/sensor-data           - Data sensor
GET  /iot/research-projects     - Proyek penelitian
```

### **API Routes**
```
POST /api/iot/sensor-data       - Simpan data sensor
GET  /api/iot/real-time-data    - Data real-time
GET  /api/iot/device-status     - Status perangkat
POST /api/iot/research-project  - Buat proyek penelitian
```

## 🔌 Koneksi Bluetooth

### **JavaScript API**
```javascript
// Inisialisasi
const iotManager = new IoTDataManager();

// Koneksi
await iotManager.connect();

// Mulai perekaman
iotManager.startRecording(kelasId, location, notes);

// Hentikan perekaman
iotManager.stopRecording();
```

### **Event Handlers**
```javascript
// Data diterima
iotManager.bluetooth.onDataReceived = (data) => {
    console.log('Sensor data:', data);
};

// Status koneksi berubah
iotManager.bluetooth.onConnectionChange = (connected, device) => {
    console.log('Connected:', connected);
};

// Error handling
iotManager.bluetooth.onError = (message, error) => {
    console.error('Bluetooth error:', message);
};
```

## 📱 Browser Compatibility

| Browser | Desktop | Mobile | Web Bluetooth | Web Serial (USB) |
|---------|---------|--------|---------------|------------------|
| Chrome  | ✅ 76+  | ✅ 76+ | ✅ Full       | ✅ 89+         |
| Edge    | ✅ 79+  | ✅ 79+ | ✅ Full       | ✅ 89+         |
| Safari  | ✅ 13+  | ✅ 13+ | ✅ Full       | ❌ Not Supported |
| Firefox | ✅ 89+  | ❌     | ⚠️ Experimental | ❌ Not Supported |

### **USB Connection Requirements:**
- **Chrome/Edge**: Version 89+ dengan Web Serial API
- **Desktop Only**: USB connection tidak didukung di mobile
- **HTTPS Required**: Web Serial API hanya bekerja di HTTPS
- **User Permission**: Browser akan meminta izin akses USB

## 🛡️ Keamanan

### **Bluetooth Security**
- Pairing otomatis dengan perangkat terdaftar
- Validasi data sensor sebelum disimpan
- Enkripsi data dalam transmisi

### **Data Security**
- Autentikasi user untuk akses data
- Validasi input sensor
- Sanitasi data sebelum disimpan

## 🚨 Troubleshooting

### **USB Connection Issues**
1. **Perangkat tidak terdeteksi:**
   - Pastikan kabel USB terhubung dengan baik
   - Periksa driver perangkat di Device Manager
   - Restart browser dan coba lagi
   - Pastikan menggunakan Chrome/Edge versi terbaru

2. **Permission denied:**
   - Klik "Allow" saat browser meminta izin USB
   - Pastikan menggunakan HTTPS (bukan HTTP)
   - Clear browser permissions dan coba lagi

3. **Data tidak terbaca:**
   - Periksa baud rate (default: 9600)
   - Pastikan format data JSON sesuai
   - Periksa koneksi kabel USB

### **Bluetooth Tidak Terdeteksi**
1. Pastikan perangkat IoT dalam mode pairing
2. Restart browser
3. Clear cache dan cookies
4. Pastikan Bluetooth aktif di perangkat

### **Koneksi Terputus**
1. **USB**: Periksa kabel USB dan koneksi
2. **Bluetooth**: Periksa jarak perangkat (max 10 meter)
3. Pastikan baterai perangkat cukup
4. Restart koneksi dan coba lagi

### **Data Tidak Tersimpan**
1. Periksa koneksi internet
2. Pastikan user sudah login
3. Periksa log error di browser console
4. Verifikasi format data sensor

## 📈 Monitoring & Analytics

### **Real-time Dashboard**
- Status koneksi perangkat
- Data sensor terbaru
- Grafik real-time
- Alert kualitas tanah

### **Research Dashboard**
- Proyek penelitian per kelas
- Analisis data historis
- Export data penelitian
- Grafik perbandingan

## 🔄 Update & Maintenance

### **Update Perangkat**
1. Upload firmware baru ke Arduino/ESP32
2. Update UUID jika diperlukan
3. Test koneksi Bluetooth
4. Verifikasi data sensor

### **Update Software**
1. Backup database
2. Update code
3. Run migration
4. Test semua fitur

## 📞 Support

### **Log Error**
- Browser Console (F12)
- Laravel Log (`storage/logs/laravel.log`)
- Network Tab untuk API calls

### **Debug Mode**
```javascript
// Enable debug logging
localStorage.setItem('iot-debug', 'true');
```

## 🎓 Educational Use

### **Untuk Guru**
- Buat proyek penelitian per kelas
- Pantau data real-time
- Analisis hasil penelitian
- Export data untuk laporan

### **Untuk Siswa**
- Lakukan pengukuran dengan IoT
- Lihat data historis
- Analisis kualitas tanah
- Belajar tentang sensor dan data

## 📋 Checklist Implementasi

- [x] Database schema
- [x] Model dan Controller
- [x] Frontend dashboard
- [x] Bluetooth connection
- [x] Real-time monitoring
- [x] Research projects
- [x] Cross-platform support
- [x] Error handling
- [x] Documentation

## 🚀 Next Features

- [ ] Mobile app (React Native)
- [ ] Push notifications
- [ ] Data export (Excel/PDF)
- [ ] Advanced analytics
- [ ] Machine learning predictions
- [ ] Multi-device support
- [ ] Cloud sync
- [ ] Offline mode

---

**Dibuat dengan ❤️ untuk TerraAssessment - Sistem Manajemen Pembelajaran**
