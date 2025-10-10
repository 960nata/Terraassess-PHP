# 📚 Manual Pengguna - Sistem Penilaian & Feedback Terintegrasi

## 🎯 Daftar Isi

1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Login dan Dashboard](#login-dan-dashboard)
3. [Untuk Guru/Pengajar](#untuk-gurupengajar)
4. [Untuk Siswa](#untuk-siswa)
5. [Untuk Admin](#untuk-admin)
6. [Troubleshooting](#troubleshooting)
7. [FAQ](#faq)

---

## 🎓 Pengenalan Sistem

Sistem Penilaian & Feedback Terintegrasi adalah platform digital yang memungkinkan guru memberikan penilaian yang komprehensif dan feedback yang detail kepada siswa. Sistem ini mendukung:

- ✅ **Penilaian dengan Rubrik** - Penilaian multi-aspek dengan bobot yang dapat disesuaikan
- ✅ **Feedback Detail** - Komentar mendalam untuk setiap aspek penilaian
- ✅ **History Tracking** - Riwayat lengkap semua revisi nilai
- ✅ **Notifikasi Real-time** - Pemberitahuan otomatis setelah penilaian
- ✅ **Analytics Dashboard** - Insights mendalam tentang performa
- ✅ **Export/Import** - Kemudahan dalam mengelola data nilai

---

## 🔐 Login dan Dashboard

### Cara Login
1. Buka browser dan akses alamat website
2. Klik tombol **"Login"** di pojok kanan atas
3. Masukkan **Email** dan **Password** Anda
4. Klik **"Masuk"**

### Dashboard Berdasarkan Role

#### 👨‍🏫 **Dashboard Guru**
- **Statistik Penilaian** - Jumlah tugas yang sudah dinilai
- **Tugas Terbaru** - Daftar tugas yang perlu dinilai
- **Notifikasi** - Pemberitahuan dari sistem
- **Quick Actions** - Tombol cepat untuk fitur utama

#### 👨‍🎓 **Dashboard Siswa**
- **Tugas Saya** - Daftar tugas yang perlu dikerjakan
- **Nilai Terbaru** - Hasil penilaian terbaru
- **Feedback** - Komentar dari guru
- **Notifikasi** - Pemberitahuan tentang tugas dan nilai

#### 👨‍💼 **Dashboard Admin**
- **Overview Sistem** - Statistik keseluruhan
- **Manajemen User** - Kelola pengguna sistem
- **Laporan** - Generate berbagai laporan
- **Analytics** - Insights mendalam sistem

---

## 👨‍🏫 Untuk Guru/Pengajar

### 1. Membuat Rubrik Penilaian

#### Langkah-langkah:
1. **Buka Tugas** yang ingin dibuat rubrik
2. Klik tombol **"Buat Rubrik"** atau akses menu **"Rubrik Penilaian"**
3. **Tambah Aspek Penilaian**:
   - Klik **"Tambah Aspek"** untuk menambah aspek baru
   - Isi nama aspek (contoh: "Isi & Analisis")
   - Set bobot dalam persentase (contoh: 40%)
   - Tulis deskripsi kriteria penilaian
4. **Validasi Bobot**:
   - Pastikan total bobot semua aspek = **100%**
   - Sistem akan menampilkan warning jika tidak sesuai
5. Klik **"Simpan Rubrik"**

#### Tips Membuat Rubrik yang Baik:
- ✅ **Gunakan aspek yang jelas** dan mudah dipahami
- ✅ **Bobot yang seimbang** sesuai tingkat kesulitan
- ✅ **Deskripsi yang detail** untuk setiap kriteria
- ✅ **Maksimal 5-7 aspek** untuk efisiensi penilaian

### 2. Penilaian dengan Rubrik

#### Langkah-langkah:
1. **Buka halaman penilaian** tugas yang sudah memiliki rubrik
2. **Isi nilai per aspek**:
   - Masukkan nilai 0-100 untuk setiap aspek
   - Berikan komentar untuk setiap aspek (opsional)
3. **Komentar umum**:
   - Tulis feedback keseluruhan untuk siswa
   - Gunakan **Quick Comments** untuk efisiensi:
     - 👍 **Bagus** - "Bagus sekali! Teruskan kerja bagusmu."
     - 📝 **Perbaikan** - "Perlu perbaikan di beberapa aspek."
     - 💪 **Cukup Baik** - "Cukup baik, tingkatkan lagi."
4. **Review nilai total** yang dihitung otomatis
5. Klik **"Simpan Penilaian"**

#### Fitur Auto-save:
- ✅ Progress tersimpan otomatis setiap **30 detik**
- ✅ Warning jika mencoba keluar dengan data belum tersimpan
- ✅ Toast notification untuk konfirmasi save

### 3. Penilaian Tanpa Rubrik (Tradisional)

#### Langkah-langkah:
1. **Buka halaman penilaian** tugas
2. **Masukkan nilai** 0-100 untuk setiap siswa
3. **Tulis komentar** (opsional) untuk setiap siswa
4. Gunakan **Quick Comments** untuk efisiensi
5. Klik **"Simpan Penilaian"**

### 4. Melihat History Revisi

#### Langkah-langkah:
1. Dari halaman penilaian, klik tombol **"History"** di samping nama siswa
2. **Modal akan terbuka** dengan riwayat lengkap:
   - Tanggal dan waktu revisi
   - Nilai lama vs nilai baru
   - Komentar lama vs komentar baru
   - Siapa yang melakukan revisi
   - Alasan revisi
3. Klik **"Detail"** untuk melihat perubahan lengkap
4. Klik **"Tutup"** untuk menutup modal

### 5. Analytics Dashboard

#### Akses Analytics:
1. Klik menu **"Analytics"** di sidebar
2. Pilih **"Grading Analytics"**

#### Metrics yang Tersedia:
- 📊 **Rata-rata Waktu Penilaian** - Berapa lama rata-rata menilai satu tugas
- 📈 **Distribusi Nilai** - Persentase nilai per grade (A, B, C, D, E)
- 👨‍🏫 **Performa Guru** - Statistik penilaian per guru
- 📉 **Trend Siswa** - Perkembangan performa siswa dari waktu ke waktu
- 📅 **Frekuensi Penilaian** - Kapan guru paling sering menilai
- 💬 **Kualitas Feedback** - Persentase tugas yang mendapat feedback

### 6. Export Data

#### Export ke Excel:
1. Klik menu **"Export"** di halaman penilaian
2. Pilih **"Export ke Excel"**
3. File akan terdownload dengan format:
   - Nama siswa, email, kelas
   - Nilai dan grade
   - Feedback/komentar
   - Tanggal penilaian
   - Conditional formatting (warna berdasarkan grade)

#### Export Laporan PDF:
1. **Transkrip Siswa**: Klik nama siswa → "Generate Transkrip"
2. **Laporan Kelas**: Menu "Laporan" → "Laporan Kelas"
3. **Laporan Guru**: Menu "Laporan" → "Laporan Performa Guru"

---

## 👨‍🎓 Untuk Siswa

### 1. Melihat Feedback dari Guru

#### Langkah-langkah:
1. **Login** ke sistem dengan akun siswa
2. **Buka Dashboard** - lihat notifikasi terbaru
3. **Klik tugas** yang sudah dinilai
4. **Lihat hasil penilaian**:
   - Nilai akhir yang didapat
   - Feedback/komentar dari guru
   - Breakdown per aspek (jika menggunakan rubrik)
   - Tanggal dan waktu penilaian

#### Jika Menggunakan Rubrik:
- ✅ **Nilai per aspek** dengan komentar individual
- ✅ **Nilai total** yang dihitung otomatis
- ✅ **Komentar umum** dari guru
- ✅ **Visual indicator** untuk performa per aspek

### 2. Notifikasi

#### Jenis Notifikasi:
- 🔔 **Tugas Dinilai** - Ketika guru selesai menilai tugas Anda
- 📝 **Tugas Baru** - Ketika ada tugas baru yang diberikan
- ⏰ **Reminder** - Pengingat deadline tugas
- 📊 **Nilai Diperbarui** - Ketika nilai direvisi

#### Cara Melihat Notifikasi:
1. **Klik ikon bel** 🔔 di navbar
2. **Lihat notifikasi terbaru** (5 teratas)
3. **Klik "Lihat Semua"** untuk notifikasi lengkap
4. **Tandai sudah dibaca** dengan klik notifikasi
5. **Hapus notifikasi** yang tidak diperlukan

### 3. Melihat Riwayat Nilai

#### Langkah-langkah:
1. **Buka halaman tugas** yang sudah dinilai
2. **Klik "Riwayat Nilai"** (jika ada revisi)
3. **Lihat timeline** perubahan nilai:
   - Tanggal revisi
   - Nilai sebelum dan sesudah
   - Alasan revisi
   - Guru yang melakukan revisi

---

## 👨‍💼 Untuk Admin

### 1. Analytics Dashboard

#### Akses Analytics:
1. **Login** sebagai admin
2. **Klik menu "Analytics"**
3. **Pilih "Grading Analytics"**

#### Insights yang Tersedia:
- 📊 **Overview Sistem** - Statistik keseluruhan
- 👨‍🏫 **Performa Guru** - Ranking guru berdasarkan kualitas penilaian
- 👨‍🎓 **Performa Siswa** - Trend performa per kelas/mata pelajaran
- 📈 **Distribusi Nilai** - Analisis distribusi nilai sistem
- ⏱️ **Efisiensi Penilaian** - Rata-rata waktu penilaian
- 💬 **Kualitas Feedback** - Persentase tugas yang mendapat feedback

### 2. Export/Import Data

#### Export Data:
1. **Export Nilai** - Semua data nilai ke Excel
2. **Export Laporan** - Generate laporan PDF
3. **Export Analytics** - Data analytics ke Excel

#### Import Data:
1. **Siapkan file Excel** dengan format:
   - Email siswa
   - Nilai (0-100)
   - Komentar (opsional)
2. **Upload file** melalui menu Import
3. **Validasi data** - sistem akan mengecek format
4. **Konfirmasi import** - review data sebelum import

### 3. Manajemen User

#### Kelola Guru:
- ✅ **Tambah guru baru**
- ✅ **Edit profil guru**
- ✅ **Reset password**
- ✅ **Assign mata pelajaran**

#### Kelola Siswa:
- ✅ **Tambah siswa baru**
- ✅ **Edit profil siswa**
- ✅ **Assign ke kelas**
- ✅ **Reset password**

### 4. Generate Laporan

#### Jenis Laporan:
1. **📊 Laporan Kelas** - Performa per kelas
2. **👨‍🏫 Laporan Guru** - Performa per guru
3. **👨‍🎓 Transkrip Siswa** - Riwayat nilai per siswa
4. **📈 Laporan Trend** - Analisis trend performa
5. **📋 Laporan Bulanan** - Ringkasan bulanan

---

## 🔧 Troubleshooting

### Masalah Umum

#### 1. **Rubrik tidak tersimpan**
**Penyebab**: Total bobot tidak 100%
**Solusi**: 
- Pastikan total bobot semua aspek = 100%
- Gunakan kalkulator untuk memastikan
- Sistem akan menampilkan warning jika tidak sesuai

#### 2. **Notifikasi tidak muncul**
**Penyebab**: Browser cache atau session issue
**Solusi**:
- Refresh halaman (F5)
- Clear browser cache
- Logout dan login kembali
- Check koneksi internet

#### 3. **Export Excel error**
**Penyebab**: Memory limit atau file permission
**Solusi**:
- Coba export data yang lebih sedikit
- Check disk space
- Contact admin untuk increase memory limit

#### 4. **Mobile layout broken**
**Penyebab**: Browser compatibility atau CSS cache
**Solusi**:
- Update browser ke versi terbaru
- Clear browser cache
- Gunakan browser yang didukung (Chrome, Firefox, Safari)

#### 5. **Login tidak bisa**
**Penyebab**: Password salah atau akun terkunci
**Solusi**:
- Pastikan email dan password benar
- Gunakan "Forgot Password" jika lupa
- Contact admin jika akun terkunci

### Error Messages

#### **"Total bobot harus 100%"**
- Periksa kembali bobot semua aspek
- Pastikan tidak ada aspek yang kosong
- Gunakan kalkulator untuk memastikan

#### **"Unauthorized access"**
- Pastikan login dengan role yang benar
- Logout dan login kembali
- Contact admin jika masalah berlanjut

#### **"File format tidak valid"**
- Pastikan file Excel (.xlsx atau .xls)
- Check format kolom sesuai template
- Download template terbaru

---

## ❓ FAQ

### **Q: Apakah sistem mendukung penilaian offline?**
A: Tidak, sistem memerlukan koneksi internet untuk berfungsi. Namun, data akan tersimpan otomatis setiap 30 detik.

### **Q: Berapa maksimal aspek dalam rubrik?**
A: Tidak ada batasan maksimal, namun disarankan maksimal 7 aspek untuk efisiensi penilaian.

### **Q: Apakah bisa import nilai dari sistem lain?**
A: Ya, asalkan format file Excel sesuai dengan template yang disediakan.

### **Q: Bagaimana cara backup data?**
A: Admin dapat melakukan export data secara berkala. Backup otomatis dilakukan setiap hari.

### **Q: Apakah sistem mendukung multiple language?**
A: Saat ini sistem hanya mendukung Bahasa Indonesia. Multi-language support akan ditambahkan di update mendatang.

### **Q: Berapa lama data disimpan?**
A: Data disimpan permanen. Admin dapat mengatur retention policy sesuai kebutuhan.

### **Q: Apakah bisa customize template export?**
A: Ya, admin dapat mengatur template export sesuai kebutuhan institusi.

### **Q: Bagaimana cara reset password?**
A: Klik "Forgot Password" di halaman login, atau minta admin untuk reset password.

### **Q: Apakah sistem mendukung penilaian kelompok?**
A: Ya, sistem mendukung penilaian individu dan kelompok dengan fitur yang sama.

### **Q: Bagaimana cara menghapus data lama?**
A: Contact admin untuk menghapus data lama. Data yang sudah dihapus tidak dapat dikembalikan.

---

## 📞 Support

### **Technical Support**
- 📧 Email: support@example.com
- 📱 WhatsApp: +62-xxx-xxx-xxxx
- 🕒 Jam Kerja: Senin-Jumat, 08:00-17:00 WIB

### **Bug Reports**
- 📧 Email: bugs@example.com
- 📝 Form: /bug-report
- 🔄 Response Time: 24-48 jam

### **Feature Requests**
- 📧 Email: features@example.com
- 💡 Portal: /feature-request
- 📊 Status: Ditinjau setiap bulan

### **Training & Documentation**
- 📚 User Manual: /docs/user-manual
- 🎥 Video Tutorial: /tutorials
- 📖 API Documentation: /docs/api

---

## 🔄 Update & Maintenance

### **Update Jadwal**
- 🔄 **Minor Updates**: Setiap 2 minggu
- 🚀 **Major Updates**: Setiap 3 bulan
- 🔧 **Hotfixes**: Sesuai kebutuhan

### **Maintenance Window**
- 🕒 **Jadwal**: Minggu, 02:00-04:00 WIB
- 📢 **Notifikasi**: 24 jam sebelumnya
- ⏱️ **Durasi**: Maksimal 2 jam

### **Changelog**
- 📝 **Version History**: /changelog
- 🆕 **New Features**: /whats-new
- 🐛 **Bug Fixes**: /bug-fixes

---

**📚 Manual ini akan terus diperbarui sesuai dengan perkembangan sistem. Terima kasih telah menggunakan Sistem Penilaian & Feedback Terintegrasi!**
