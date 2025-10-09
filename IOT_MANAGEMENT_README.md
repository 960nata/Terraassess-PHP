# Manajemen IoT - Implementasi Adreno

## Overview
Sistem Manajemen IoT yang telah diperbarui dengan fokus pada implementasi **Adreno** untuk perangkat IoT yang menggunakan koneksi kabel. Menghilangkan kompleksitas sensor, aktuator, gateway, dan kustom yang tidak perlu, dan fokus pada platform Adreno yang dirancang khusus untuk perangkat dengan koneksi kabel.

## 🎯 Perubahan Utama

### ❌ **Yang Dihapus:**
- Card Sensor (Perangkat sensor untuk monitoring)
- Card Aktuator (Perangkat kontrol otomatis)  
- Card Gateway (Gateway komunikasi IoT)
- Card Kustom (Perangkat IoT kustom)
- Kompleksitas yang tidak perlu

### ✅ **Yang Ditambahkan:**
- **Implementasi Adreno** - Platform khusus untuk perangkat kabel
- **Tombol "+ Perangkat IoT"** yang jelas
- **Fokus pada Koneksi Kabel** - USB, Ethernet, Serial
- **Interface yang Sederhana** dan mudah digunakan
- **Monitoring Real-time** untuk perangkat Adreno

## 🛠️ Fitur Utama

### 1. **Platform Adreno**
- **USB Connection**: Koneksi langsung via USB untuk Arduino, ESP32, mikrokontroler
- **Ethernet Support**: Koneksi jaringan untuk perangkat IoT canggih
- **Serial Communication**: Komunikasi serial untuk perangkat legacy
- **Real-time Monitoring**: Monitoring data sensor dan kontrol perangkat

### 2. **Tombol + Perangkat IoT**
- Tombol yang jelas di header
- Langsung membuka popup untuk menambah perangkat
- Desain yang menarik dengan hover effects

### 3. **Popup Tambah Perangkat**
- **Nama Perangkat**: Input text untuk nama perangkat
- **Tipe Koneksi**: Dropdown (USB, Ethernet, Serial)
- **ID Perangkat**: Input opsional untuk ID unik
- **Deskripsi**: Textarea untuk deskripsi detail
- **Lokasi**: Input untuk lokasi perangkat
- **Kelas**: Dropdown untuk mengaitkan dengan kelas

### 4. **List Perangkat IoT**
- **Tabel Responsif**: Menampilkan semua perangkat
- **Informasi Lengkap**: Nama, tipe koneksi, status, lokasi, kelas
- **Status Badge**: Visual indicator untuk status terhubung/terputus
- **Aksi Cepat**: Tombol Lihat, Edit, dan Hapus

### 5. **Fitur Edit & Delete**
- **Edit**: Modal popup untuk mengedit perangkat
- **Delete**: Konfirmasi sebelum menghapus
- **Connect/Disconnect**: Kontrol koneksi perangkat

## 📁 File yang Dibuat/Dimodifikasi

### 1. **View**
- `resources/views/superadmin/iot-management-new.blade.php`
  - Interface baru yang clean dan modern
  - Modal untuk tambah/edit perangkat
  - Tabel responsif dengan aksi
  - Section khusus untuk Adreno implementation

### 2. **Controller**
- `app/Http/Controllers/SuperAdmin/IotManagementController.php`
  - `index()` - Tampilkan halaman utama
  - `store()` - Tambah perangkat IoT baru
  - `edit()` - Ambil data untuk edit
  - `update()` - Update perangkat
  - `destroy()` - Hapus perangkat
  - `connect()` - Hubungkan perangkat
  - `disconnect()` - Putuskan perangkat
  - `getDeviceData()` - Ambil data perangkat

### 3. **Model**
- `app/Models/IotDevice.php`
  - Menambahkan field untuk Adreno: `connection_type`, `description`, `location`, `class_id`, `platform`, `data_points`
  - Relationship dengan `Kelas` dan `User`
  - Scopes untuk filtering
  - Methods untuk status dan data points

### 4. **Migration**
- `database/migrations/2025_10_04_075542_add_adreno_fields_to_iot_devices_table.php`
  - Menambahkan kolom untuk implementasi Adreno
  - Foreign key ke tabel `kelas`
  - Timestamps untuk koneksi/diskonneksi

### 5. **Routes**
- `GET /superadmin/iot-management-new` - Halaman utama
- `POST /superadmin/iot-management` - Tambah perangkat
- `GET /superadmin/iot-management/{id}/edit` - Data edit
- `PUT /superadmin/iot-management/{id}` - Update perangkat
- `DELETE /superadmin/iot-management/{id}` - Hapus perangkat
- `POST /superadmin/iot-management/{id}/connect` - Hubungkan
- `POST /superadmin/iot-management/{id}/disconnect` - Putuskan
- `GET /superadmin/iot-management/{id}/data` - Data perangkat

## 🎨 UI/UX Design

### **Header Section**
```css
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}
```

### **Adreno Section**
- Section khusus untuk menjelaskan implementasi Adreno
- Feature cards untuk USB, Ethernet, Serial, Real-time Monitoring
- Desain yang informatif dan menarik

### **Statistics Cards**
- Perangkat Terhubung (hijau)
- Perangkat Terputus (merah)
- Data Points (biru)
- Adreno Devices (ungu)

### **Table Design**
- Clean white background
- Hover effects pada rows
- Status badges dengan warna yang jelas
- Connection type badges
- Action buttons yang mudah diakses

## 🔧 Cara Penggunaan

### 1. **Menambah Perangkat IoT**
1. Klik tombol "+ Perangkat IoT" di header
2. Isi form yang muncul:
   - Nama perangkat (wajib)
   - Pilih tipe koneksi (USB/Ethernet/Serial)
   - Masukkan ID perangkat (opsional)
   - Tulis deskripsi (opsional)
   - Masukkan lokasi (opsional)
   - Pilih kelas (opsional)
3. Klik "Simpan"

### 2. **Mengedit Perangkat**
1. Klik tombol "Edit" pada perangkat yang ingin diedit
2. Modal edit akan terbuka dengan data yang sudah terisi
3. Ubah data yang diperlukan
4. Klik "Update"

### 3. **Menghapus Perangkat**
1. Klik tombol "Hapus" pada perangkat yang ingin dihapus
2. Konfirmasi penghapusan
3. Perangkat akan dihapus

### 4. **Melihat Data Perangkat**
1. Klik tombol "Lihat" pada perangkat
2. Informasi detail perangkat akan ditampilkan

## 📊 Database Schema

### **Tabel `iot_devices`**
```sql
- id (primary key)
- name (string) - Nama perangkat
- device_id (string, unique) - ID unik perangkat
- bluetooth_address (string, nullable) - Alamat Bluetooth
- device_type (string) - Tipe perangkat
- connection_type (enum) - Tipe koneksi (usb, ethernet, serial)
- description (text, nullable) - Deskripsi perangkat
- location (string, nullable) - Lokasi perangkat
- class_id (foreign key) - ID kelas
- platform (string) - Platform (default: adreno)
- status (enum) - Status (connected, disconnected)
- user_id (foreign key) - ID user pemilik
- device_info (json, nullable) - Informasi tambahan
- last_seen (timestamp, nullable) - Terakhir terlihat
- data_points (integer) - Jumlah data points
- last_connected (timestamp, nullable) - Terakhir terhubung
- last_disconnected (timestamp, nullable) - Terakhir terputus
- created_at (timestamp)
- updated_at (timestamp)
```

## 🔒 Validasi & Keamanan

### **Input Validation**
- Nama perangkat: required, string, max 255 karakter
- Tipe koneksi: required, enum (usb, ethernet, serial)
- ID perangkat: optional, unique, max 100 karakter
- Deskripsi: optional, max 1000 karakter
- Lokasi: optional, max 255 karakter
- Kelas: optional, exists in kelas table

### **Business Logic**
- Semua perangkat menggunakan platform Adreno
- Status default: disconnected
- Data points default: 0
- Timestamps untuk tracking koneksi

## 🚀 JavaScript Functions

### **Modal Management**
- `openAddDeviceModal()` - Buka modal tambah
- `closeAddDeviceModal()` - Tutup modal tambah
- `openEditDeviceModal(id)` - Buka modal edit
- `closeEditDeviceModal()` - Tutup modal edit
- `openDeleteDeviceModal(id, name)` - Buka konfirmasi hapus

### **Device Management**
- `viewDevice(id)` - Lihat detail perangkat
- `editDevice(id)` - Edit perangkat
- `deleteDevice(id)` - Hapus perangkat
- `confirmDeleteDevice()` - Konfirmasi hapus
- `loadDeviceData(id)` - Load data untuk edit

### **AJAX Calls**
- Load data edit via AJAX
- Update data via AJAX
- Delete data via AJAX
- Connect/disconnect perangkat via AJAX

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

## 🔌 Implementasi Adreno

### **USB Connection**
- Koneksi langsung via USB
- Cocok untuk Arduino, ESP32, mikrokontroler
- Plug and play functionality
- Real-time data streaming

### **Ethernet Support**
- Koneksi jaringan untuk perangkat canggih
- Remote monitoring capabilities
- Network-based communication
- Scalable untuk multiple devices

### **Serial Communication**
- Komunikasi serial untuk perangkat legacy
- RS232, RS485 support
- Low-level communication
- Reliable data transmission

### **Real-time Monitoring**
- Live data streaming
- Real-time status updates
- Data visualization
- Alert notifications

## 🔄 Migration & Update

### **Database Migration**
```bash
php artisan migrate --path=database/migrations/2025_10_04_075542_add_adreno_fields_to_iot_devices_table.php
```

### **Rollback (jika diperlukan)**
```bash
php artisan migrate:rollback --path=database/migrations/2025_10_04_075542_add_adreno_fields_to_iot_devices_table.php
```

## 🐛 Troubleshooting

### **Common Issues**
1. **Modal tidak terbuka**: Check JavaScript console untuk errors
2. **Data tidak tersimpan**: Check validation rules dan database connection
3. **Perangkat tidak connect**: Check connection type dan device configuration
4. **Data tidak muncul**: Check device status dan data points

### **Debug Steps**
1. Check browser console untuk JavaScript errors
2. Check Laravel logs untuk server errors
3. Verify database connection
4. Check device connection status

## 📈 Future Enhancements

### **Planned Features**
- [ ] Real-time data visualization
- [ ] Device templates untuk Arduino/ESP32
- [ ] Bulk device import/export
- [ ] Advanced device configuration
- [ ] Data logging dan analytics
- [ ] Mobile app untuk monitoring

### **Advanced Features**
- [ ] Device firmware update
- [ ] Remote device control
- [ ] Data export ke Excel/CSV
- [ ] Integration dengan sensor libraries
- [ ] Custom device protocols
- [ ] Multi-user device sharing

## 📞 Support

Untuk bantuan teknis atau pertanyaan tentang implementasi Adreno, silakan hubungi tim development atau buat issue di repository project.

---

**Sistem Manajemen IoT v2.0 dengan Adreno** - Platform IoT yang dirancang khusus untuk perangkat dengan koneksi kabel, memberikan kemudahan monitoring dan kontrol real-time.
