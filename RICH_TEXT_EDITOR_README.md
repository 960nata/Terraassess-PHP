# Rich Text Editor untuk Materi Tugas dan Ujian

## 🎯 Overview

Sistem rich text editor modern menggunakan Quill.js yang memungkinkan pengajar untuk membuat materi tugas dan ujian yang kaya dengan format teks, gambar, link, dan elemen lainnya.

## ✨ Fitur Utama

### 📝 Format Teks
- **Bold, Italic, Underline, Strikethrough**
- **Heading 1, 2, 3** untuk struktur konten
- **Blockquote** untuk kutipan penting
- **Code Block** untuk kode atau formula

### 📋 List & Indentasi
- **Numbered List** (1, 2, 3...)
- **Bullet List** (•, •, •...)
- **Increase/Decrease Indent** untuk struktur hierarkis

### 🖼️ Media & Link
- **Upload Gambar** dengan drag & drop atau klik
- **Insert Link** ke sumber referensi
- **Insert Video** (YouTube, Vimeo, dll)
- **Insert Formula** matematika

### 🎨 Styling
- **Clean Formatting** untuk menghapus format
- **Responsive Design** untuk semua perangkat
- **Modern UI** dengan tema yang konsisten

## 🚀 Cara Penggunaan

### 1. Menggunakan Komponen Editor

```php
@include('components.rich-text-editor', [
    'name' => 'content',                    // Nama field form
    'content' => old('content'),            // Konten awal (opsional)
    'placeholder' => 'Tuliskan di sini...', // Placeholder text
    'height' => '200px',                    // Tinggi editor
    'maxHeight' => '400px',                 // Tinggi maksimal (opsional)
    'required' => true                      // Field wajib diisi
])
```

### 2. Implementasi di Form

```php
<!-- Form Tugas -->
<form action="{{ route('createTugas') }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label>Deskripsi Tugas *</label>
        @include('components.rich-text-editor', [
            'name' => 'content',
            'content' => old('content'),
            'placeholder' => 'Tuliskan instruksi yang jelas untuk siswa...',
            'height' => '250px',
            'required' => true
        ])
        @error('content')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan Tugas</button>
</form>
```

### 3. Validasi di Controller

```php
public function createTugas(Request $request)
{
    $request->validate([
        'content' => 'required|string',
        // ... validasi lainnya
    ]);
    
    // Konten HTML akan tersimpan di $request->content
    $tugas = Tugas::create([
        'content' => $request->content,
        // ... field lainnya
    ]);
    
    return redirect()->back()->with('success', 'Tugas berhasil dibuat!');
}
```

## 🖼️ Upload Gambar

### Konfigurasi Upload
- **Maksimal ukuran**: 5MB
- **Format yang didukung**: JPG, PNG, GIF, BMP, ICO
- **Direktori penyimpanan**: `storage/app/public/editor-images/`
- **URL akses**: `asset('storage/editor-images/filename')`

### Cara Upload Gambar
1. Klik tombol **gambar** di toolbar editor
2. Pilih file gambar dari komputer
3. Gambar akan otomatis diupload dan disisipkan
4. Gambar akan tersimpan di server dan dapat diakses kapan saja

## 🎨 Kustomisasi

### Mengubah Tinggi Editor
```php
@include('components.rich-text-editor', [
    'name' => 'content',
    'height' => '300px',        // Tinggi default
    'maxHeight' => '500px'      // Tinggi maksimal saat scroll
])
```

### Mengubah Toolbar
Edit file `resources/views/components/rich-text-editor.blade.php` pada bagian toolbar:

```javascript
toolbar: {
    container: [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        // Tambah atau hapus tombol sesuai kebutuhan
    ]
}
```

## 📱 Responsive Design

Editor otomatis menyesuaikan dengan ukuran layar:
- **Desktop**: Toolbar lengkap dengan semua fitur
- **Tablet**: Toolbar yang lebih kompak
- **Mobile**: Toolbar yang dioptimalkan untuk sentuhan

## 🔧 Troubleshooting

### Gambar Tidak Muncul
1. Pastikan storage link sudah dibuat: `php artisan storage:link`
2. Cek permission direktori: `chmod -R 755 storage/app/public/editor-images/`
3. Pastikan file gambar tidak rusak

### Editor Tidak Load
1. Pastikan Quill.js sudah dimuat di layout
2. Cek console browser untuk error JavaScript
3. Pastikan komponen editor sudah di-include dengan benar

### Upload Gambar Gagal
1. Cek ukuran file (maksimal 5MB)
2. Pastikan format file didukung
3. Cek koneksi internet
4. Lihat log error di browser console

## 📊 Demo

Akses halaman demo untuk melihat semua fitur editor:
```
http://localhost:8000/rich-text-editor-demo.html
```

## 🔄 Update & Maintenance

### Menambah Fitur Baru
1. Edit komponen editor di `resources/views/components/rich-text-editor.blade.php`
2. Update toolbar dan handler sesuai kebutuhan
3. Test di halaman demo terlebih dahulu

### Backup Data
- Konten editor disimpan sebagai HTML di database
- Gambar disimpan di `storage/app/public/editor-images/`
- Lakukan backup reguler untuk data penting

## 🎯 Best Practices

### Untuk Pengajar
1. **Gunakan heading** untuk struktur materi yang jelas
2. **Sisipkan gambar** untuk penjelasan visual
3. **Buat list** untuk instruksi yang terstruktur
4. **Gunakan blockquote** untuk informasi penting
5. **Test di berbagai perangkat** sebelum publish

### Untuk Developer
1. **Validasi input** di controller untuk keamanan
2. **Sanitize HTML** jika diperlukan
3. **Backup reguler** data editor
4. **Monitor ukuran file** upload gambar
5. **Test responsivitas** di berbagai ukuran layar

## 📞 Support

Jika mengalami masalah dengan editor:
1. Cek dokumentasi ini terlebih dahulu
2. Lihat halaman demo untuk referensi
3. Cek console browser untuk error
4. Hubungi tim developer untuk bantuan lebih lanjut

---

**Dibuat dengan ❤️ untuk Terra Assessment System**
