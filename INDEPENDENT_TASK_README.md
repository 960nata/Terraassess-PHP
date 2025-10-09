# Tugas Mandiri - Sistem Pertanyaan Teks

## Overview
Sistem Tugas Mandiri memungkinkan guru membuat pertanyaan yang dijawab oleh siswa dengan mengetik langsung. Fitur ini menggunakan Quill editor modern untuk pertanyaan yang kaya akan format.

## Fitur Utama

### 1. **Pertanyaan Mandiri**
- Maksimal 20 pertanyaan per tugas
- Editor Quill modern dengan toolbar lengkap
- Support untuk format teks, gambar, list, dan link
- Setiap pertanyaan memiliki poin dan kategori (mudah/sedang/sulit)

### 2. **Editor Quill Modern**
- **Headers**: H1-H6 untuk struktur pertanyaan
- **Formatting**: Bold, italic, underline, strikethrough
- **Lists**: Ordered dan bullet lists
- **Colors**: Text dan background colors
- **Alignment**: Left, center, right, justify
- **Special**: Blockquote, code blocks
- **Media**: Image upload dan link insertion

### 3. **Sistem Poin dan Kategori**
- **Poin**: 1-100 per pertanyaan (default: 10)
- **Kategori**: 
  - Mudah (easy)
  - Sedang (medium) - default
  - Sulit (hard)

## Cara Penggunaan

### 1. **Membuat Tugas Mandiri**
1. Buka `/superadmin/tugas/create/3`
2. Isi informasi dasar tugas
3. Klik "Tambah Pertanyaan" untuk menambah pertanyaan
4. Gunakan editor Quill untuk menulis pertanyaan
5. Set poin dan kategori untuk setiap pertanyaan
6. Simpan tugas

### 2. **Mengelola Pertanyaan**
- **Tambah**: Klik tombol "Tambah Pertanyaan"
- **Hapus**: Klik tombol trash di pojok kanan atas pertanyaan
- **Edit**: Gunakan editor Quill untuk mengedit pertanyaan

## Struktur Database

### Tabel `tugas_quizzes`
```sql
- id (primary key)
- tugas_id (foreign key)
- soal (text) - pertanyaan dalam HTML
- poin (integer) - poin pertanyaan
- kategori (string) - easy/medium/hard
- created_at
- updated_at
```

## File yang Dimodifikasi

### 1. **View**
- `resources/views/superadmin/create-tugas.blade.php`
  - Menambahkan section "Pertanyaan Mandiri"
  - JavaScript untuk mengelola pertanyaan
  - CSS styling untuk pertanyaan mandiri

### 2. **Controller**
- `app/Http/Controllers/TugasController.php`
  - Method `createTugas()` untuk menangani pertanyaan mandiri
  - Logika untuk menyimpan `mandiri_questions`

### 3. **Model**
- `app/Models/TugasQuiz.php`
  - Menambahkan `poin` dan `kategori` ke `$fillable`

### 4. **Migration**
- `database/migrations/2025_10_03_231515_add_poin_kategori_to_tugas_quizzes_table.php`
  - Menambahkan kolom `poin` dan `kategori` ke tabel `tugas_quizzes`

## Styling

### Pertanyaan Mandiri
- **Background**: Gradient pink (#f093fb to #f5576c)
- **Card**: Rounded corners dengan shadow
- **Header**: White text dengan shadow
- **Content**: Semi-transparent white background

## JavaScript Functions

### 1. **addMandiriQuestion()**
- Menambah pertanyaan baru
- Inisialisasi Quill editor
- Update numbering

### 2. **removeMandiriQuestion(questionNum)**
- Menghapus pertanyaan
- Cleanup Quill instance
- Update numbering

### 3. **initializeMandiriQuillEditor(questionNum)**
- Inisialisasi Quill editor
- Setup toolbar dan handlers
- Image upload support

### 4. **updateMandiriQuestionNumbers()**
- Update nomor pertanyaan setelah hapus
- Update input names dan IDs

## Validasi

### Frontend
- Maksimal 20 pertanyaan
- Pertanyaan wajib diisi
- Poin 1-100
- Kategori harus dipilih

### Backend
- Validasi input dari form
- Cek keberadaan pertanyaan
- Default values untuk poin dan kategori

## Error Handling

### Quill Editor
- Fallback ke textarea jika Quill gagal load
- Retry mechanism untuk inisialisasi
- Console logging untuk debugging

### Form Submission
- Validasi sebelum submit
- Error messages untuk input kosong
- Success feedback setelah simpan

## Browser Support
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Dependencies
- Quill.js 1.3.6
- Font Awesome 6
- Bootstrap 5 (untuk styling)

## Future Enhancements
- [ ] Auto-save draft
- [ ] Question templates
- [ ] Bulk import questions
- [ ] Rich text preview
- [ ] Question reordering
- [ ] Time limits per question
