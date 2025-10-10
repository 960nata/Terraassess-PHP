# 🔧 IoT Monitoring Troubleshooting Guide

## 📋 Overview

Panduan lengkap untuk mengatasi masalah umum dalam sistem IoT monitoring TerraAssessment, termasuk koneksi USB, data sensor, dan penyimpanan database.

## 🚨 Masalah Umum & Solusi

### **1. Arduino Tidak Terdeteksi di Browser**

#### **Gejala:**
- Tombol "Hubungkan" tidak berfungsi
- Browser tidak menampilkan perangkat USB
- Error: "Web Serial API tidak didukung"

#### **Solusi:**

**A. Periksa Browser Support:**
- ✅ **Chrome 89+**: Full support
- ✅ **Edge 89+**: Full support  
- ❌ **Safari**: Tidak didukung
- ❌ **Firefox**: Tidak didukung
- ❌ **Mobile browsers**: Tidak didukung

**B. Periksa Koneksi USB:**
```bash
# Windows - Device Manager
1. Buka Device Manager
2. Cari "Ports (COM & LPT)"
3. Pastikan Arduino muncul sebagai "Arduino Uno (COM3)" atau sejenisnya
4. Jika ada tanda seru kuning, update driver

# Mac - System Information
1. Buka System Information
2. Hardware → USB
3. Cari Arduino device
4. Pastikan status "Connected"

# Linux - dmesg
sudo dmesg | grep tty
# Cari output seperti: "ttyUSB0" atau "ttyACM0"
```

**C. Test Arduino di Serial Monitor:**
1. Buka Arduino IDE
2. Tools → Serial Monitor
3. Set baud rate ke 9600
4. Pastikan data JSON muncul setiap detik

### **2. Data Sensor Tidak Terbaca**

#### **Gejala:**
- Arduino terhubung tapi dashboard menampilkan "--"
- Data tidak berubah di dashboard
- Console browser menampilkan error parsing

#### **Solusi:**

**A. Periksa Format Data Arduino:**
```cpp
// Pastikan Arduino mengirim format JSON yang benar:
{"temperature":25.5,"humidity":60.2,"soil_moisture":45.8,"ph_level":6.5,"nutrient_level":75.0}

// BUKAN format ini:
Temperature: 25.5, Humidity: 60.2  // ❌ Salah
25.5,60.2,45.8,6.5,75.0           // ❌ Salah
```

**B. Periksa Baud Rate:**
```cpp
// Arduino sketch
Serial.begin(9600);  // Pastikan 9600

// JavaScript (sudah benar)
this.baudRate = 9600;
```

**C. Test Parsing Data:**
1. Buka Browser Console (F12)
2. Lihat output: `Sensor data received: {temperature: 25.5, ...}`
3. Jika tidak ada, periksa koneksi USB
4. Jika ada tapi tidak update UI, periksa JavaScript

### **3. Data Tidak Tersimpan ke Database**

#### **Gejala:**
- Data muncul di dashboard tapi tidak tersimpan
- Error di console: "Failed to save sensor data"
- Halaman "Data Sensor" kosong

#### **Solusi:**

**A. Periksa Recording Status:**
1. Pastikan tombol "Mulai Perekaman" sudah diklik
2. Pastikan kelas sudah dipilih
3. Lihat console: `Recording started: {kelasId: 1, ...}`

**B. Periksa API Endpoint:**
```bash
# Test API endpoint
curl -X POST http://localhost:8000/api/iot/sensor-data \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: [token]" \
  -d '{"device_id":"test","temperature":25.5,"humidity":60.2,"soil_moisture":45.8,"kelas_id":1}'
```

**C. Periksa Database Connection:**
```bash
# Laravel
php artisan migrate:status
php artisan tinker
>>> App\Models\IotSensorData::count()
```

**D. Periksa Authentication:**
- Pastikan user sudah login
- Periksa CSRF token di meta tag
- Pastikan middleware auth aktif

### **4. Sensor DHT22 Tidak Terbaca**

#### **Gejala:**
- Temperature dan Humidity selalu 0
- Error di Serial Monitor: "Failed to read from DHT sensor"

#### **Solusi:**

**A. Periksa Koneksi Kabel:**
```
DHT22 → Arduino
VCC   → 5V (atau 3.3V)
GND   → GND  
DATA  → Pin 2 (atau pin lain)
      └── Pull-up resistor 10kΩ ke VCC
```

**B. Periksa Library:**
```cpp
// Pastikan library terinstall
#include <DHT.h>
#define DHT_PIN 2
#define DHT_TYPE DHT22
DHT dht(DHT_PIN, DHT_TYPE);

// Install via Arduino IDE:
// Tools → Manage Libraries → Search "DHT sensor library"
```

**C. Test DHT22 Manual:**
```cpp
void setup() {
  Serial.begin(9600);
  dht.begin();
}

void loop() {
  float temp = dht.readTemperature();
  float hum = dht.readHumidity();
  
  if (isnan(temp) || isnan(hum)) {
    Serial.println("DHT22 Error!");
  } else {
    Serial.print("Temp: ");
    Serial.print(temp);
    Serial.print("°C, Humidity: ");
    Serial.print(hum);
    Serial.println("%");
  }
  delay(2000);
}
```

### **5. Soil Moisture Sensor Tidak Akurat**

#### **Gejala:**
- Nilai kelembaban tanah tidak realistis
- Selalu 0% atau 100%
- Tidak berubah saat sensor dicelupkan ke air

#### **Solusi:**

**A. Kalibrasi Sensor:**
```cpp
// Test dengan udara kering (nilai tinggi)
int dryValue = analogRead(SOIL_MOISTURE_PIN);
Serial.print("Dry value: ");
Serial.println(dryValue);

// Test dengan air (nilai rendah)  
int wetValue = analogRead(SOIL_MOISTURE_PIN);
Serial.print("Wet value: ");
Serial.println(wetValue);

// Update kode dengan nilai kalibrasi
const float SOIL_MOISTURE_DRY = dryValue;    // Nilai saat kering
const float SOIL_MOISTURE_WET = wetValue;    // Nilai saat basah
```

**B. Periksa Koneksi:**
```
Soil Moisture Sensor → Arduino
VCC  → 5V (atau 3.3V)
GND  → GND
AOUT → A0 (atau pin analog lain)
```

**C. Test Sensor Manual:**
```cpp
void loop() {
  int rawValue = analogRead(A0);
  float percentage = map(rawValue, SOIL_MOISTURE_DRY, SOIL_MOISTURE_WET, 0, 100);
  percentage = constrain(percentage, 0, 100);
  
  Serial.print("Raw: ");
  Serial.print(rawValue);
  Serial.print(", Percentage: ");
  Serial.print(percentage);
  Serial.println("%");
  
  delay(1000);
}
```

### **6. pH Sensor Tidak Akurat**

#### **Gejala:**
- pH selalu 7.0 atau nilai tidak realistis
- Tidak berubah dengan larutan pH berbeda

#### **Solusi:**

**A. Kalibrasi dengan Larutan Standar:**
```cpp
// Test dengan larutan pH 4.0
int ph4Value = analogRead(PH_SENSOR_PIN);
Serial.print("pH 4.0 value: ");
Serial.println(ph4Value);

// Test dengan larutan pH 7.0
int ph7Value = analogRead(PH_SENSOR_PIN);
Serial.print("pH 7.0 value: ");
Serial.println(ph7Value);

// Test dengan larutan pH 10.0
int ph10Value = analogRead(PH_SENSOR_PIN);
Serial.print("pH 10.0 value: ");
Serial.println(ph10Value);

// Update kode
const float PH_ACID = ph4Value;      // Nilai pH asam
const float PH_NEUTRAL = ph7Value;   // Nilai pH netral
const float PH_ALKALINE = ph10Value; // Nilai pH basa
```

**B. Periksa Koneksi:**
```
pH Sensor → Arduino
VCC  → 5V (atau 3.3V)
GND  → GND
AOUT → A1 (atau pin analog lain)
```

### **7. Koneksi Terputus Tiba-tiba**

#### **Gejala:**
- Dashboard menampilkan "Terputus"
- Data berhenti mengalir
- Error: "USB connection lost"

#### **Solusi:**

**A. Periksa Kabel USB:**
- Ganti kabel USB dengan yang berkualitas baik
- Pastikan koneksi tidak longgar
- Hindari kabel yang terlalu panjang (>2m)

**B. Periksa Power Supply:**
- Arduino Uno: 5V via USB atau power adapter
- ESP32: 3.3V, pastikan power supply cukup
- Hindari power supply yang tidak stabil

**C. Periksa Interference:**
- Jauhkan dari perangkat elektronik lain
- Gunakan kabel USB yang ter-shield
- Pastikan tidak ada kabel listrik AC di dekatnya

### **8. Browser Permission Issues**

#### **Gejala:**
- Browser meminta permission berulang kali
- Error: "Permission denied"
- Tidak bisa akses USB device

#### **Solusi:**

**A. Clear Browser Permissions:**
```
Chrome:
1. Settings → Privacy and Security → Site Settings
2. Cari localhost:8000
3. Clear permissions untuk Serial/USB

Edge:
1. Settings → Cookies and Site Permissions
2. Cari localhost:8000
3. Clear permissions
```

**B. Gunakan HTTPS:**
```bash
# Laravel dengan HTTPS
php artisan serve --host=0.0.0.0 --port=8000
# Atau setup SSL certificate
```

**C. Restart Browser:**
- Tutup semua tab browser
- Restart browser
- Coba lagi

### **9. Database Migration Issues**

#### **Gejala:**
- Error: "Table 'iot_sensor_data' doesn't exist"
- Migration gagal
- Data tidak bisa disimpan

#### **Solusi:**

**A. Run Migration:**
```bash
# Laravel
php artisan migrate
php artisan migrate:status
```

**B. Reset Database:**
```bash
# Hati-hati: ini akan menghapus semua data!
php artisan migrate:fresh
php artisan db:seed
```

**C. Periksa Database Connection:**
```bash
# .env file
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Atau MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=terraassess
DB_USERNAME=root
DB_PASSWORD=
```

### **10. Performance Issues**

#### **Gejala:**
- Dashboard lambat loading
- Data tidak real-time
- Browser freeze

#### **Solusi:**

**A. Optimize Data Reading:**
```cpp
// Arduino - kurangi frekuensi reading
const unsigned long READING_INTERVAL = 2000; // 2 detik instead of 1 detik
```

**B. Optimize JavaScript:**
```javascript
// Throttle data updates
let lastUpdate = 0;
const UPDATE_INTERVAL = 1000; // 1 detik

function updateSensorDisplay(data) {
    const now = Date.now();
    if (now - lastUpdate < UPDATE_INTERVAL) {
        return; // Skip update
    }
    lastUpdate = now;
    // ... update display
}
```

**C. Optimize Database:**
```sql
-- Add indexes untuk performa
CREATE INDEX idx_iot_sensor_data_measured_at ON iot_sensor_data(measured_at);
CREATE INDEX idx_iot_sensor_data_device_id ON iot_sensor_data(device_id);
```

## 🔍 Debug Tools

### **1. Browser Console Debug:**
```javascript
// Enable debug mode
localStorage.setItem('iot-debug', 'true');

// Check connection status
console.log(window.iotManager.getStatus());

// Check available methods
console.log(window.iotManager.getAvailableMethods());

// Test USB connection
window.iotManager.usbManager.testConnection().then(console.log);
```

### **2. Arduino Serial Debug:**
```cpp
// Add debug output
void debugOutput() {
    Serial.print("Debug - ");
    Serial.print("Temp: ");
    Serial.print(temperature);
    Serial.print("°C, Humidity: ");
    Serial.print(humidity);
    Serial.print("%, Soil: ");
    Serial.print(soilMoisture);
    Serial.print("%, pH: ");
    Serial.print(phLevel);
    Serial.print(", Nutrients: ");
    Serial.print(nutrientLevel);
    Serial.println("%");
}
```

### **3. Laravel Log Debug:**
```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Check specific log
grep "IoT" storage/logs/laravel.log
```

## 📞 Support & Escalation

### **Level 1 - Basic Troubleshooting:**
1. Restart Arduino
2. Restart browser
3. Check kabel USB
4. Clear browser cache

### **Level 2 - Advanced Troubleshooting:**
1. Check Arduino Serial Monitor
2. Check browser console
3. Check Laravel logs
4. Test dengan sensor lain

### **Level 3 - Expert Support:**
1. Check hardware dengan multimeter
2. Test dengan Arduino IDE
3. Check database schema
4. Review code implementation

## 🎯 Quick Fix Checklist

- [ ] Arduino terhubung via USB
- [ ] Browser support Web Serial API (Chrome/Edge)
- [ ] Arduino mengirim data JSON format
- [ ] Baud rate 9600 di Arduino dan JavaScript
- [ ] User sudah login di sistem
- [ ] Recording sudah dimulai
- [ ] Kelas sudah dipilih
- [ ] Database migration sudah dijalankan
- [ ] API endpoint bisa diakses
- [ ] CSRF token valid

## 📚 Additional Resources

- [Arduino Setup Guide](./ARDUINO_SETUP_GUIDE.md)
- [IoT System README](./IOT_SYSTEM_README.md)
- [USB IoT Implementation](./USB_IOT_IMPLEMENTATION.md)
- [Web Serial API Documentation](https://developer.mozilla.org/en-US/docs/Web/API/Web_Serial_API)
- [DHT22 Library Documentation](https://github.com/adafruit/DHT-sensor-library)

---

**Dibuat dengan ❤️ untuk TerraAssessment - Sistem Manajemen Pembelajaran**
