# 🔌 Arduino IoT Sensor Setup Guide

## 📋 Overview

Panduan lengkap untuk setup Arduino dengan sensor kelembaban tanah (humus), suhu, dan pH untuk sistem monitoring IoT TerraAssessment.

## 🛠️ Hardware yang Dibutuhkan

### **Komponen Utama:**
1. **Arduino Uno/Nano/ESP32** - Mikrokontroler utama
2. **Soil Moisture Sensor** - Mengukur kelembaban tanah (humus)
3. **DHT22 Sensor** - Mengukur suhu dan kelembaban udara
4. **pH Sensor** (Opsional) - Mengukur tingkat keasaman tanah
5. **Kabel jumper** - Untuk koneksi
6. **Breadboard** - Untuk prototyping
7. **Resistor 10kΩ** - Pull-up resistor untuk DHT22

### **Rekomendasi Sensor:**

#### **A. Soil Moisture Sensor (Kelembaban Tanah/Humus)**
**Pilihan 1: Kapasitif (Rekomendasi)**
- ✅ Lebih akurat dan tahan lama
- ✅ Tidak berkarat
- ✅ Output analog 0-1023
- Pin: VCC (3.3V/5V), GND, AOUT
- Harga: ~Rp 20.000-40.000

**Pilihan 2: Resistif**
- ⚠️ Lebih murah tapi cepat berkarat
- ⚠️ Perlu kalibrasi berkala
- Pin: VCC (3.3V/5V), GND, AOUT
- Harga: ~Rp 10.000-20.000

#### **B. DHT22 (Suhu & Kelembaban Udara)**
- Suhu: -40°C hingga 80°C (±0.5°C)
- Kelembaban: 0-100% (±2%)
- Pin: VCC (3.3V/5V), GND, DATA
- Harga: ~Rp 35.000-50.000

#### **C. pH Sensor (Opsional)**
- Range: 0-14 pH
- Output analog 0-1023
- Pin: VCC (3.3V/5V), GND, AOUT
- Harga: ~Rp 100.000-200.000

## 🔌 Diagram Koneksi

### **Arduino Uno/Nano:**
```
Soil Moisture Sensor:
├── VCC → 5V
├── GND → GND
└── AOUT → A0

DHT22:
├── VCC → 5V
├── GND → GND
└── DATA → Pin 2
    └── Pull-up resistor 10kΩ ke VCC

pH Sensor (Opsional):
├── VCC → 5V
├── GND → GND
└── AOUT → A1
```

### **ESP32:**
```
Soil Moisture Sensor:
├── VCC → 3.3V
├── GND → GND
└── AOUT → GPIO 34 (ADC1_CH6)

DHT22:
├── VCC → 3.3V
├── GND → GND
└── DATA → GPIO 4
    └── Pull-up resistor 10kΩ ke 3.3V

pH Sensor (Opsional):
├── VCC → 3.3V
├── GND → GND
└── AOUT → GPIO 35 (ADC1_CH7)
```

## 💻 Kode Arduino Lengkap

### **1. Sketch untuk Arduino Uno/Nano:**

```cpp
/*
 * TerraAssessment IoT Sensor Monitor
 * Arduino Uno/Nano dengan sensor kelembaban tanah, DHT22, dan pH
 * Output: JSON via Serial USB untuk Web Serial API
 */

#include <DHT.h>

// Pin definitions
#define DHT_PIN 2
#define DHT_TYPE DHT22
#define SOIL_MOISTURE_PIN A0
#define PH_SENSOR_PIN A1

// Sensor objects
DHT dht(DHT_PIN, DHT_TYPE);

// Calibration values (sesuaikan dengan sensor Anda)
const float SOIL_MOISTURE_DRY = 0;    // Nilai saat tanah kering
const float SOIL_MOISTURE_WET = 1023; // Nilai saat tanah basah
const float PH_ACID = 0;              // Nilai pH asam
const float PH_ALKALINE = 1023;       // Nilai pH basa

// Timing
unsigned long lastReading = 0;
const unsigned long READING_INTERVAL = 1000; // 1 detik

void setup() {
  // Initialize Serial communication
  Serial.begin(9600);
  
  // Initialize DHT sensor
  dht.begin();
  
  // Initialize analog pins
  pinMode(SOIL_MOISTURE_PIN, INPUT);
  pinMode(PH_SENSOR_PIN, INPUT);
  
  // Wait for serial connection
  while (!Serial) {
    delay(100);
  }
  
  Serial.println("TerraAssessment IoT Sensor Monitor Started");
  Serial.println("Format: JSON via Serial USB");
  delay(2000);
}

void loop() {
  // Check if it's time for a new reading
  if (millis() - lastReading >= READING_INTERVAL) {
    readAndSendSensorData();
    lastReading = millis();
  }
  
  delay(100); // Small delay to prevent overwhelming
}

void readAndSendSensorData() {
  // Read DHT22 (Temperature & Humidity)
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();
  
  // Check if DHT reading failed
  if (isnan(temperature) || isnan(humidity)) {
    temperature = 0.0;
    humidity = 0.0;
  }
  
  // Read Soil Moisture Sensor
  int soilMoistureRaw = analogRead(SOIL_MOISTURE_PIN);
  float soilMoisture = map(soilMoistureRaw, SOIL_MOISTURE_DRY, SOIL_MOISTURE_WET, 0, 100);
  soilMoisture = constrain(soilMoisture, 0, 100);
  
  // Read pH Sensor (if connected)
  int phRaw = analogRead(PH_SENSOR_PIN);
  float phLevel = map(phRaw, PH_ACID, PH_ALKALINE, 0, 14);
  phLevel = constrain(phLevel, 0, 14);
  
  // Calculate nutrient level (simulasi berdasarkan pH dan kelembaban)
  float nutrientLevel = calculateNutrientLevel(phLevel, soilMoisture);
  
  // Create JSON data
  String jsonData = "{";
  jsonData += "\"temperature\":" + String(temperature, 1) + ",";
  jsonData += "\"humidity\":" + String(humidity, 1) + ",";
  jsonData += "\"soil_moisture\":" + String(soilMoisture, 1) + ",";
  jsonData += "\"ph_level\":" + String(phLevel, 1) + ",";
  jsonData += "\"nutrient_level\":" + String(nutrientLevel, 1);
  jsonData += "}";
  
  // Send via Serial
  Serial.println(jsonData);
  
  // Debug info (optional)
  Serial.print("Debug - Temp: ");
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

float calculateNutrientLevel(float ph, float moisture) {
  // Simulasi perhitungan level nutrisi
  // pH optimal untuk kebanyakan tanaman: 6.0-7.0
  // Kelembaban optimal: 40-80%
  
  float phScore = 0;
  if (ph >= 6.0 && ph <= 7.0) {
    phScore = 100; // Optimal
  } else if (ph >= 5.5 && ph <= 7.5) {
    phScore = 80; // Good
  } else if (ph >= 5.0 && ph <= 8.0) {
    phScore = 60; // Fair
  } else {
    phScore = 30; // Poor
  }
  
  float moistureScore = 0;
  if (moisture >= 40 && moisture <= 80) {
    moistureScore = 100; // Optimal
  } else if (moisture >= 30 && moisture <= 90) {
    moistureScore = 80; // Good
  } else if (moisture >= 20 && moisture <= 95) {
    moistureScore = 60; // Fair
  } else {
    moistureScore = 30; // Poor
  }
  
  // Average of both scores
  return (phScore + moistureScore) / 2;
}
```

### **2. Sketch untuk ESP32:**

```cpp
/*
 * TerraAssessment IoT Sensor Monitor - ESP32 Version
 * ESP32 dengan sensor kelembaban tanah, DHT22, dan pH
 * Output: JSON via Serial USB untuk Web Serial API
 */

#include <DHT.h>

// Pin definitions for ESP32
#define DHT_PIN 4
#define DHT_TYPE DHT22
#define SOIL_MOISTURE_PIN 34  // ADC1_CH6
#define PH_SENSOR_PIN 35      // ADC1_CH7

// Sensor objects
DHT dht(DHT_PIN, DHT_TYPE);

// Calibration values
const float SOIL_MOISTURE_DRY = 0;
const float SOIL_MOISTURE_WET = 4095; // ESP32 has 12-bit ADC
const float PH_ACID = 0;
const float PH_ALKALINE = 4095;

// Timing
unsigned long lastReading = 0;
const unsigned long READING_INTERVAL = 1000;

void setup() {
  // Initialize Serial communication
  Serial.begin(9600);
  
  // Initialize DHT sensor
  dht.begin();
  
  // Configure ADC for ESP32
  analogReadResolution(12); // 12-bit resolution (0-4095)
  
  // Wait for serial connection
  while (!Serial) {
    delay(100);
  }
  
  Serial.println("TerraAssessment IoT Sensor Monitor - ESP32 Started");
  Serial.println("Format: JSON via Serial USB");
  delay(2000);
}

void loop() {
  if (millis() - lastReading >= READING_INTERVAL) {
    readAndSendSensorData();
    lastReading = millis();
  }
  
  delay(100);
}

void readAndSendSensorData() {
  // Read DHT22
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();
  
  if (isnan(temperature) || isnan(humidity)) {
    temperature = 0.0;
    humidity = 0.0;
  }
  
  // Read Soil Moisture (ESP32 ADC)
  int soilMoistureRaw = analogRead(SOIL_MOISTURE_PIN);
  float soilMoisture = map(soilMoistureRaw, SOIL_MOISTURE_DRY, SOIL_MOISTURE_WET, 0, 100);
  soilMoisture = constrain(soilMoisture, 0, 100);
  
  // Read pH Sensor
  int phRaw = analogRead(PH_SENSOR_PIN);
  float phLevel = map(phRaw, PH_ACID, PH_ALKALINE, 0, 14);
  phLevel = constrain(phLevel, 0, 14);
  
  // Calculate nutrient level
  float nutrientLevel = calculateNutrientLevel(phLevel, soilMoisture);
  
  // Create JSON data
  String jsonData = "{";
  jsonData += "\"temperature\":" + String(temperature, 1) + ",";
  jsonData += "\"humidity\":" + String(humidity, 1) + ",";
  jsonData += "\"soil_moisture\":" + String(soilMoisture, 1) + ",";
  jsonData += "\"ph_level\":" + String(phLevel, 1) + ",";
  jsonData += "\"nutrient_level\":" + String(nutrientLevel, 1);
  jsonData += "}";
  
  // Send via Serial
  Serial.println(jsonData);
  
  // Debug info
  Serial.print("Debug - Temp: ");
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

float calculateNutrientLevel(float ph, float moisture) {
  // Same calculation as Arduino version
  float phScore = 0;
  if (ph >= 6.0 && ph <= 7.0) {
    phScore = 100;
  } else if (ph >= 5.5 && ph <= 7.5) {
    phScore = 80;
  } else if (ph >= 5.0 && ph <= 8.0) {
    phScore = 60;
  } else {
    phScore = 30;
  }
  
  float moistureScore = 0;
  if (moisture >= 40 && moisture <= 80) {
    moistureScore = 100;
  } else if (moisture >= 30 && moisture <= 90) {
    moistureScore = 80;
  } else if (moisture >= 20 && moisture <= 95) {
    moistureScore = 60;
  } else {
    moistureScore = 30;
  }
  
  return (phScore + moistureScore) / 2;
}
```

## 📚 Library yang Dibutuhkan

### **Arduino IDE:**
1. **DHT sensor library** by Adafruit
   - Install via Library Manager: "DHT sensor library"
   - Atau download: https://github.com/adafruit/DHT-sensor-library

### **ESP32:**
1. **DHT sensor library** by Adafruit
2. **ESP32 Board Package** (jika belum ada)
   - File → Preferences → Additional Board Manager URLs
   - Tambahkan: `https://dl.espressif.com/dl/package_esp32_index.json`
   - Tools → Board → Boards Manager → cari "ESP32" → Install

## 🔧 Cara Upload Sketch

### **1. Arduino Uno/Nano:**
1. Buka Arduino IDE
2. Install library DHT sensor
3. Copy kode Arduino Uno ke IDE
4. Pilih board: Tools → Board → Arduino AVR Boards → Arduino Uno
5. Pilih port: Tools → Port → (pilih port USB Arduino)
6. Upload: Sketch → Upload (Ctrl+U)

### **2. ESP32:**
1. Install ESP32 board package
2. Copy kode ESP32 ke IDE
3. Pilih board: Tools → Board → ESP32 Arduino → ESP32 Dev Module
4. Pilih port: Tools → Port → (pilih port USB ESP32)
5. Upload: Sketch → Upload (Ctrl+U)

## 🧪 Testing Arduino

### **1. Serial Monitor Test:**
1. Buka Serial Monitor: Tools → Serial Monitor
2. Set baud rate ke 9600
3. Anda harus melihat output seperti:
```
TerraAssessment IoT Sensor Monitor Started
Format: JSON via Serial USB
{"temperature":25.5,"humidity":60.2,"soil_moisture":45.8,"ph_level":6.5,"nutrient_level":75.0}
Debug - Temp: 25.5°C, Humidity: 60.2%, Soil: 45.8%, pH: 6.5, Nutrients: 75.0%
```

### **2. Sensor Test:**
- **DHT22**: Tiup sensor untuk test perubahan suhu/kelembaban
- **Soil Moisture**: Celupkan sensor ke air untuk test kelembaban tinggi
- **pH Sensor**: Test dengan larutan pH berbeda

## ⚠️ Troubleshooting Arduino

### **Masalah Umum:**

1. **DHT22 tidak terbaca:**
   - Periksa koneksi kabel
   - Pastikan pull-up resistor 10kΩ terpasang
   - Cek library DHT sudah terinstall

2. **Soil Moisture selalu 0 atau 100:**
   - Periksa koneksi sensor
   - Kalibrasi nilai SOIL_MOISTURE_DRY dan SOIL_MOISTURE_WET
   - Test dengan air dan udara kering

3. **pH Sensor tidak akurat:**
   - Kalibrasi dengan larutan pH standar (4.0, 7.0, 10.0)
   - Periksa koneksi dan power supply

4. **Serial tidak muncul:**
   - Periksa kabel USB
   - Pilih port yang benar di Arduino IDE
   - Restart Arduino IDE

## 📊 Format Data Output

Arduino mengirim data dalam format JSON setiap 1 detik:

```json
{
  "temperature": 25.5,      // Suhu dalam Celsius
  "humidity": 60.2,         // Kelembaban udara dalam %
  "soil_moisture": 45.8,    // Kelembaban tanah (humus) dalam %
  "ph_level": 6.5,          // pH tanah (0-14)
  "nutrient_level": 75.0    // Level nutrisi dalam %
}
```

## 🎯 Kalibrasi Sensor

### **Soil Moisture Sensor:**
1. **Tanah Kering**: Baca nilai analog saat sensor di udara kering
2. **Tanah Basah**: Baca nilai analog saat sensor di air
3. **Update kode**: Ganti SOIL_MOISTURE_DRY dan SOIL_MOISTURE_WET

### **pH Sensor:**
1. **pH 4.0**: Baca nilai analog dengan larutan pH 4.0
2. **pH 7.0**: Baca nilai analog dengan larutan pH 7.0
3. **pH 10.0**: Baca nilai analog dengan larutan pH 10.0
4. **Update kode**: Sesuaikan PH_ACID dan PH_ALKALINE

## 🚀 Langkah Selanjutnya

Setelah Arduino berhasil mengirim data JSON via Serial:

1. **Koneksi ke Browser**: Gunakan Web Serial API di dashboard IoT
2. **Monitoring Real-time**: Lihat data di dashboard TerraAssessment
3. **Penyimpanan Data**: Data otomatis tersimpan ke database
4. **Analisis**: Gunakan fitur research projects untuk analisis data

---

**Dibuat dengan ❤️ untuk TerraAssessment - Sistem Manajemen Pembelajaran**
