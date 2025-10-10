# 📖 IoT Monitoring User Manual

## 📋 Overview

Panduan lengkap cara menggunakan sistem IoT monitoring TerraAssessment untuk mengukur kelembaban tanah (humus), suhu, dan pH dengan Arduino via USB.

## 🎯 Langkah-langkah Lengkap

### **STEP 1: Persiapan Hardware**

#### **1.1 Komponen yang Dibutuhkan:**
- Arduino Uno/Nano/ESP32
- Soil Moisture Sensor (kapasitif/resistif)
- DHT22 Sensor (suhu & kelembaban udara)
- pH Sensor (opsional)
- Kabel jumper
- Breadboard
- Resistor 10kΩ

#### **1.2 Koneksi Kabel:**
```
Arduino Uno:
├── Soil Moisture: VCC→5V, GND→GND, AOUT→A0
├── DHT22: VCC→5V, GND→GND, DATA→Pin2 + 10kΩ ke VCC
└── pH Sensor: VCC→5V, GND→GND, AOUT→A1
```

### **STEP 2: Upload Kode Arduino**

#### **2.1 Install Arduino IDE:**
1. Download dari https://arduino.cc
2. Install library DHT sensor:
   - Tools → Manage Libraries
   - Search "DHT sensor library"
   - Install by Adafruit

#### **2.2 Upload Sketch:**
1. Buka Arduino IDE
2. Copy kode dari `ARDUINO_SETUP_GUIDE.md`
3. Pilih board: Tools → Board → Arduino AVR Boards → Arduino Uno
4. Pilih port: Tools → Port → (pilih port USB Arduino)
5. Upload: Sketch → Upload (Ctrl+U)

#### **2.3 Test Arduino:**
1. Buka Serial Monitor: Tools → Serial Monitor
2. Set baud rate ke 9600
3. Pastikan output JSON muncul setiap detik:
```json
{"temperature":25.5,"humidity":60.2,"soil_moisture":45.8,"ph_level":6.5,"nutrient_level":75.0}
```

### **STEP 3: Setup Sistem TerraAssessment**

#### **3.1 Jalankan Server:**
```bash
cd /path/to/terraassess
php artisan serve
```

#### **3.2 Akses Dashboard:**
1. Buka browser Chrome/Edge (versi 89+)
2. Akses: http://localhost:8000
3. Login sebagai guru
4. Klik "IoT Dashboard"

### **STEP 4: Koneksi Arduino ke Browser**

#### **4.1 Pilih Metode Koneksi:**
1. Di dashboard IoT, klik tombol "USB"
2. Pastikan status: "USB Connection (1m range, stable)"

#### **4.2 Hubungkan Arduino:**
1. Klik tombol "Hubungkan"
2. Browser akan meminta permission USB
3. Klik "Allow" atau "Izinkan"
4. Pilih Arduino dari daftar perangkat
5. Status berubah menjadi "Terhubung"

### **STEP 5: Monitoring Real-time**

#### **5.1 Lihat Data Sensor:**
Dashboard akan menampilkan:
- **Suhu Tanah**: Real-time dalam °C
- **Kelembaban Udara**: Real-time dalam %
- **Kelembaban Tanah (Humus)**: Real-time dalam % ⭐
- **pH Tanah**: Real-time (0-14)
- **Level Nutrisi**: Real-time dalam %
- **Kualitas Tanah**: Status otomatis

#### **5.2 Verifikasi Data:**
- Data harus berubah setiap detik
- Nilai harus realistis (tidak selalu 0 atau 100)
- Console browser (F12) menampilkan: "Sensor data received"

### **STEP 6: Perekaman Data**

#### **6.1 Setup Perekaman:**
1. Pilih kelas dari dropdown
2. Masukkan lokasi (contoh: "Kebun Sekolah")
3. Tambahkan catatan (opsional)

#### **6.2 Mulai Perekaman:**
1. Klik "Mulai Perekaman"
2. Tombol berubah menjadi "Merekam..."
3. Data otomatis tersimpan ke database
4. Console menampilkan: "Recording started"

#### **6.3 Hentikan Perekaman:**
1. Klik "Hentikan Perekaman"
2. Tombol kembali ke "Mulai Perekaman"
3. Data berhenti tersimpan

### **STEP 7: Verifikasi Data Tersimpan**

#### **7.1 Lihat Data Sensor:**
1. Klik "Data Sensor" di dashboard
2. Pastikan data muncul di tabel
3. Verifikasi timestamp dan nilai sensor

#### **7.2 Export Data:**
1. Gunakan filter untuk data tertentu
2. Export ke Excel/CSV jika diperlukan
3. Data siap untuk analisis

## 🔧 Troubleshooting Cepat

### **Arduino Tidak Terdeteksi:**
- Ganti kabel USB
- Restart browser
- Pastikan menggunakan Chrome/Edge 89+

### **Data Tidak Muncul:**
- Periksa Serial Monitor Arduino
- Pastikan format JSON benar
- Check browser console (F12)

### **Data Tidak Tersimpan:**
- Pastikan sudah login
- Pastikan recording sudah dimulai
- Pastikan kelas sudah dipilih

## 📊 Interpretasi Data

### **Kelembaban Tanah (Humus):**
- **0-20%**: Sangat kering, perlu penyiraman
- **20-40%**: Kering, monitor terus
- **40-60%**: Optimal untuk kebanyakan tanaman
- **60-80%**: Lembab, masih baik
- **80-100%**: Terlalu basah, risiko busuk akar

### **Suhu Tanah:**
- **15-25°C**: Optimal untuk pertumbuhan
- **25-30°C**: Baik untuk tanaman tropis
- **<15°C atau >35°C**: Perlu perhatian

### **pH Tanah:**
- **6.0-7.0**: Optimal untuk kebanyakan tanaman
- **5.5-6.0**: Sedikit asam, masih baik
- **7.0-7.5**: Sedikit basa, masih baik
- **<5.5 atau >7.5**: Perlu perbaikan

### **Kualitas Tanah:**
- **Sangat Baik**: Semua parameter optimal
- **Baik**: Parameter dalam range normal
- **Perlu Perhatian**: Ada parameter yang ekstrem

## 🎓 Tips Penggunaan

### **Untuk Guru:**
1. **Setup Kelompok**: Bagi siswa dalam kelompok 3-4 orang
2. **Rotasi Sensor**: Ganti lokasi pengukuran setiap 15 menit
3. **Dokumentasi**: Minta siswa catat kondisi tanah saat pengukuran
4. **Analisis**: Bandingkan data antar lokasi dan waktu

### **Untuk Siswa:**
1. **Handle Sensor**: Pegang sensor dengan hati-hati
2. **Konsisten**: Ukur di kedalaman yang sama (5-10cm)
3. **Catat Kondisi**: Tulis cuaca, waktu, kondisi tanah
4. **Bandingkan**: Analisis perbedaan data antar lokasi

## 📈 Analisis Data

### **Trend Analysis:**
1. Lihat perubahan data dari waktu ke waktu
2. Identifikasi pola (pagi vs siang vs sore)
3. Bandingkan dengan kondisi cuaca

### **Spatial Analysis:**
1. Bandingkan data antar lokasi
2. Identifikasi area dengan kualitas tanah berbeda
3. Analisis faktor yang mempengaruhi

### **Research Projects:**
1. Buat proyek penelitian per kelas
2. Set parameter penelitian (suhu, kelembaban, pH)
3. Analisis hasil dan buat kesimpulan

## 🚀 Advanced Features

### **Multi-Device Support:**
- Hubungkan beberapa Arduino sekaligus
- Bandingkan data dari sensor berbeda
- Analisis variasi spasial

### **Data Export:**
- Export ke Excel untuk analisis lanjutan
- Generate laporan otomatis
- Visualisasi data dengan grafik

### **Alert System:**
- Set threshold untuk parameter tertentu
- Dapatkan notifikasi jika nilai ekstrem
- Monitoring otomatis 24/7

## 📞 Support

### **Jika Ada Masalah:**
1. Baca [Troubleshooting Guide](./IOT_TROUBLESHOOTING_GUIDE.md)
2. Check [Arduino Setup Guide](./ARDUINO_SETUP_GUIDE.md)
3. Hubungi admin sistem

### **Resources:**
- [IoT System README](./IOT_SYSTEM_README.md)
- [USB IoT Implementation](./USB_IOT_IMPLEMENTATION.md)
- [Web Serial API Documentation](https://developer.mozilla.org/en-US/docs/Web/API/Web_Serial_API)

---

**Dibuat dengan ❤️ untuk TerraAssessment - Sistem Manajemen Pembelajaran**
