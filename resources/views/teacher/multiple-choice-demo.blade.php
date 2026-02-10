@extends('layouts.unified-layout')

@section('title', 'Demo Soal Pilihan Ganda')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Demo Soal Pilihan Ganda</li>
                    </ol>
                </div>
                <h4 class="page-title">Demo Sistem Soal Pilihan Ganda</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-info-circle me-2"></i>
                        Fitur Sistem Soal Pilihan Ganda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Fitur untuk Guru:</h6>
                            <ul class="list-unstyled">
                                <li><i class="ph-check text-success me-2"></i> Quill Editor Modern dengan toolbar lengkap</li>
                                <li><i class="ph-check text-success me-2"></i> Format teks: bold, italic, underline, strike, color</li>
                                <li><i class="ph-check text-success me-2"></i> Header H1-H6, font size, font family</li>
                                <li><i class="ph-check text-success me-2"></i> Daftar berurutan, tidak berurutan, indentasi</li>
                                <li><i class="ph-check text-success me-2"></i> Align text, blockquote, code block</li>
                                <li><i class="ph-check text-success me-2"></i> Upload gambar dan embed video</li>
                                <li><i class="ph-check text-success me-2"></i> Subscript, superscript, RTL support</li>
                                <li><i class="ph-check text-success me-2"></i> Tambah/hapus soal secara dinamis</li>
                                <li><i class="ph-check text-success me-2"></i> 2-6 pilihan jawaban per soal</li>
                                <li><i class="ph-check text-success me-2"></i> Set poin dan kategori kesulitan</li>
                                <li><i class="ph-check text-success me-2"></i> Preview soal sebelum menyimpan</li>
                                <li><i class="ph-check text-success me-2"></i> Acak urutan soal (opsional)</li>
                                <li><i class="ph-check text-success me-2"></i> Set batas waktu pengerjaan</li>
                                <li><i class="ph-check text-success me-2"></i> Sembunyikan dari siswa (opsional)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Fitur untuk Siswa:</h6>
                            <ul class="list-unstyled">
                                <li><i class="ph-check text-success me-2"></i> Interface yang bersih dan mudah digunakan</li>
                                <li><i class="ph-check text-success me-2"></i> Progress indicator (berapa soal terjawab)</li>
                                <li><i class="ph-check text-success me-2"></i> Validasi sebelum submit</li>
                                <li><i class="ph-check text-success me-2"></i> Konfirmasi sebelum mengirim jawaban</li>
                                <li><i class="ph-check text-success me-2"></i> Tampilan responsif untuk mobile</li>
                            </ul>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Cara Menggunakan:</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="ph-plus-circle display-4 text-primary mb-3"></i>
                                            <h6>1. Buat Tugas</h6>
                                            <p class="text-muted small">Klik "Tambah Soal" untuk membuat soal baru</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="ph-pencil display-4 text-success mb-3"></i>
                                            <h6>2. Isi Soal</h6>
                                            <p class="text-muted small">Tulis pertanyaan dan pilihan jawaban</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <i class="ph-check-circle display-4 text-warning mb-3"></i>
                                            <h6>3. Simpan</h6>
                                            <p class="text-muted small">Preview dan simpan tugas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('teacher.tasks.create.multiple-choice') }}" class="btn btn-primary btn-lg">
                            <i class="ph-plus me-2"></i>
                            Mulai Buat Soal Pilihan Ganda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Example Questions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-lightbulb me-2"></i>
                        Contoh Soal Pilihan Ganda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="question-example mb-4">
                                <h6>Soal 1 (Mudah)</h6>
                                <p><strong>Pertanyaan:</strong> Apa ibukota Indonesia?</p>
                                <div class="options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">A. Jakarta</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">B. Bandung</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">C. Surabaya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">D. Medan</label>
                                    </div>
                                </div>
                                <small class="text-muted">Poin: 1 | Kategori: Mudah</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="question-example mb-4">
                                <h6>Soal 2 (Sedang)</h6>
                                <p><strong>Pertanyaan:</strong> Manakah yang merupakan contoh dari inheritance dalam OOP?</p>
                                <div class="options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">A. Encapsulation</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">B. Polymorphism</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">C. Class extends ParentClass</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">D. Method overloading</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label">E. Data abstraction</label>
                                    </div>
                                </div>
                                <small class="text-muted">Poin: 2 | Kategori: Sedang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.question-example {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    background: #f8f9fa;
}

.question-example h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
}

.question-example p {
    margin-bottom: 15px;
    line-height: 1.6;
}

.options {
    background: white;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 10px;
}

.form-check {
    padding-left: 1.5rem;
}

.form-check-input:disabled {
    opacity: 0.5;
}

.form-check-label {
    font-size: 14px;
    line-height: 1.5;
}
</style>
@endpush
