# Elass 2 - API Documentation

## Overview
Elass 2 adalah Learning Management System (LMS) yang dibangun dengan Laravel 10 dan menyediakan API RESTful untuk manajemen tugas, ujian, materi, dan dashboard.

## Base URL
```
http://your-domain.com/api
```

## Authentication
API menggunakan Laravel Sanctum untuk authentication. Semua endpoint memerlukan token authentication.

### Headers Required
```
Authorization: Bearer {your-token}
Content-Type: application/json
Accept: application/json
```

## Endpoints

### Dashboard

#### GET /dashboard
Mendapatkan data dashboard berdasarkan role user.

**Response:**
```json
{
  "success": true,
  "data": {
    "totalSiswa": 150,
    "totalPengajar": 25,
    "totalKelas": 12,
    "totalMapel": 8,
    "totalMateri": 45,
    "totalTugas": 30,
    "totalUjian": 15,
    "activeUsers": 120
  },
  "message": "Dashboard data berhasil diambil"
}
```

### Tugas (Assignments)

#### GET /tugas
Mendapatkan daftar tugas berdasarkan role user.

**Query Parameters:**
- `per_page` (optional): Jumlah data per halaman (default: 10)
- `page` (optional): Nomor halaman

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "judul": "Tugas Matematika",
        "deskripsi": "Kerjakan soal-soal berikut",
        "deadline": "2024-01-15T23:59:59.000000Z",
        "tipe_tugas": "individual",
        "kelas_mapel": {
          "id": 1,
          "kelas": {
            "id": 1,
            "name": "X IPA 1"
          },
          "mapel": {
            "id": 1,
            "name": "Matematika"
          }
        },
        "tugas_files": [
          {
            "id": 1,
            "nama_file": "soal.pdf",
            "path": "tugas/soal.pdf",
            "ukuran_file": 1024000
          }
        ],
        "user": {
          "id": 1,
          "name": "John Doe"
        },
        "created_at": "2024-01-01T10:00:00.000000Z",
        "updated_at": "2024-01-01T10:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 3,
    "per_page": 10,
    "total": 25
  },
  "message": "Tugas berhasil diambil"
}
```

#### POST /tugas
Membuat tugas baru.

**Request Body:**
```json
{
  "judul": "Tugas Matematika",
  "deskripsi": "Kerjakan soal-soal berikut",
  "deadline": "2024-01-15T23:59:59.000000Z",
  "tipe_tugas": "individual",
  "kelas_mapel_id": 1
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "judul": "Tugas Matematika",
    "deskripsi": "Kerjakan soal-soal berikut",
    "deadline": "2024-01-15T23:59:59.000000Z",
    "tipe_tugas": "individual",
    "kelas_mapel_id": 1,
    "user_id": 1,
    "created_at": "2024-01-01T10:00:00.000000Z",
    "updated_at": "2024-01-01T10:00:00.000000Z"
  },
  "message": "Tugas berhasil dibuat"
}
```

#### GET /tugas/{id}
Mendapatkan detail tugas.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "judul": "Tugas Matematika",
    "deskripsi": "Kerjakan soal-soal berikut",
    "deadline": "2024-01-15T23:59:59.000000Z",
    "tipe_tugas": "individual",
    "kelas_mapel": {
      "id": 1,
      "kelas": {
        "id": 1,
        "name": "X IPA 1"
      },
      "mapel": {
        "id": 1,
        "name": "Matematika"
      }
    },
    "tugas_files": [],
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2024-01-01T10:00:00.000000Z",
    "updated_at": "2024-01-01T10:00:00.000000Z"
  },
  "message": "Tugas berhasil diambil"
}
```

#### PUT /tugas/{id}
Memperbarui tugas.

**Request Body:**
```json
{
  "judul": "Tugas Matematika Updated",
  "deskripsi": "Kerjakan soal-soal berikut (Updated)",
  "deadline": "2024-01-20T23:59:59.000000Z",
  "tipe_tugas": "kelompok"
}
```

#### DELETE /tugas/{id}
Menghapus tugas.

**Response:**
```json
{
  "success": true,
  "message": "Tugas berhasil dihapus"
}
```

#### GET /tugas/statistics
Mendapatkan statistik tugas.

**Response:**
```json
{
  "success": true,
  "data": {
    "total": 25,
    "this_week": 5,
    "this_month": 15,
    "by_type": {
      "individual": 10,
      "kelompok": 8,
      "quiz": 5,
      "multiple": 2
    }
  },
  "message": "Statistik tugas berhasil diambil"
}
```

#### GET /tugas/search
Mencari tugas.

**Query Parameters:**
- `q`: Kata kunci pencarian
- `per_page` (optional): Jumlah data per halaman

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "judul": "Tugas Matematika",
        "deskripsi": "Kerjakan soal-soal berikut",
        "deadline": "2024-01-15T23:59:59.000000Z",
        "tipe_tugas": "individual"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  },
  "message": "Hasil pencarian tugas"
}
```

#### GET /tugas/upcoming-deadlines
Mendapatkan tugas dengan deadline mendatang.

**Query Parameters:**
- `days` (optional): Jumlah hari ke depan (default: 7)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "judul": "Tugas Matematika",
      "deadline": "2024-01-15T23:59:59.000000Z",
      "kelas_mapel": {
        "kelas": {
          "name": "X IPA 1"
        },
        "mapel": {
          "name": "Matematika"
        }
      }
    }
  ],
  "message": "Tugas dengan deadline mendatang"
}
```

### Ujian (Exams)

#### GET /ujian
Mendapatkan daftar ujian.

#### POST /ujian
Membuat ujian baru.

**Request Body:**
```json
{
  "judul": "Ujian Matematika",
  "deskripsi": "Ujian tengah semester",
  "waktu_mulai": "2024-01-15T08:00:00.000000Z",
  "waktu_selesai": "2024-01-15T10:00:00.000000Z",
  "durasi": 120,
  "tipe_ujian": "essay",
  "kelas_mapel_id": 1,
  "bobot_nilai": 30
}
```

#### GET /ujian/{id}
Mendapatkan detail ujian.

#### PUT /ujian/{id}
Memperbarui ujian.

#### DELETE /ujian/{id}
Menghapus ujian.

#### GET /ujian/statistics
Mendapatkan statistik ujian.

#### GET /ujian/search
Mencari ujian.

### Materi (Materials)

#### GET /materi
Mendapatkan daftar materi.

#### POST /materi
Membuat materi baru.

#### GET /materi/{id}
Mendapatkan detail materi.

#### PUT /materi/{id}
Memperbarui materi.

#### DELETE /materi/{id}
Menghapus materi.

#### GET /materi/statistics
Mendapatkan statistik materi.

#### GET /materi/search
Mencari materi.

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Data tidak valid",
  "errors": {
    "judul": ["Judul tugas harus diisi."],
    "deadline": ["Deadline harus lebih dari waktu sekarang."]
  }
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Anda tidak memiliki akses ke resource ini"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource tidak ditemukan"
}
```

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Terjadi kesalahan pada server",
  "error": "Error details"
}
```

## Validation Rules

### Tugas
- `judul`: required|string|max:255
- `deskripsi`: required|string
- `deadline`: required|date|after:now
- `tipe_tugas`: required|in:individual,kelompok,quiz,multiple
- `kelas_mapel_id`: required|exists:kelas_mapels,id
- `file_tugas.*`: nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240

### Ujian
- `judul`: required|string|max:255
- `deskripsi`: required|string
- `waktu_mulai`: required|date|after:now
- `waktu_selesai`: required|date|after:waktu_mulai
- `durasi`: required|integer|min:1|max:480
- `tipe_ujian`: required|in:essay,multiple
- `kelas_mapel_id`: required|exists:kelas_mapels,id
- `bobot_nilai`: required|numeric|min:0|max:100

## Rate Limiting
API memiliki rate limiting 60 requests per minute per user.

## Pagination
Semua endpoint yang mengembalikan daftar data menggunakan pagination Laravel dengan format:
```json
{
  "data": [...],
  "current_page": 1,
  "last_page": 3,
  "per_page": 10,
  "total": 25,
  "from": 1,
  "to": 10
}
```

## File Upload
Untuk upload file, gunakan `multipart/form-data` content type:
```
Content-Type: multipart/form-data
```

## Examples

### cURL Examples

#### Login
```bash
curl -X POST http://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

#### Get Tugas
```bash
curl -X GET http://your-domain.com/api/tugas \
  -H "Authorization: Bearer your-token" \
  -H "Accept: application/json"
```

#### Create Tugas
```bash
curl -X POST http://your-domain.com/api/tugas \
  -H "Authorization: Bearer your-token" \
  -H "Content-Type: application/json" \
  -d '{
    "judul": "Tugas Matematika",
    "deskripsi": "Kerjakan soal-soal berikut",
    "deadline": "2024-01-15T23:59:59.000000Z",
    "tipe_tugas": "individual",
    "kelas_mapel_id": 1
  }'
```

## Support
Untuk pertanyaan atau bantuan, silakan hubungi tim development.
