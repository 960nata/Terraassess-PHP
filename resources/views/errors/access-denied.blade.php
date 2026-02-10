@extends('layouts.unified-layout')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="access-denied-container">
                <div class="access-denied-content">
                    <div class="access-denied-icon">
                        <i class="ph-lock"></i>
                    </div>
                    <h2 class="access-denied-title">Akses Ditolak</h2>
                    <p class="access-denied-message">
                        Maaf, Anda tidak memiliki izin untuk melakukan operasi ini. 
                        Sebagai guru, Anda hanya dapat melihat data tetapi tidak dapat membuat, mengedit, atau menghapus data master seperti:
                    </p>
                    <ul class="access-denied-list">
                        <li><i class="ph-x-circle"></i> Membuat kelas baru</li>
                        <li><i class="ph-x-circle"></i> Membuat pengajar baru</li>
                        <li><i class="ph-x-circle"></i> Membuat admin baru</li>
                        <li><i class="ph-x-circle"></i> Membuat siswa baru</li>
                    </ul>
                    <div class="access-denied-actions">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="ph-house"></i>
                            Kembali ke Dashboard
                        </a>
                        <a href="javascript:history.back()" class="btn btn-secondary">
                            <i class="ph-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.access-denied-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 2rem;
}

.access-denied-content {
    text-align: center;
    max-width: 600px;
    padding: 3rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.access-denied-icon {
    font-size: 5rem;
    color: #ef4444;
    margin-bottom: 1.5rem;
}

.access-denied-title {
    color: #1f2937;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.access-denied-message {
    color: #6b7280;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.access-denied-list {
    list-style: none;
    padding: 0;
    margin: 2rem 0;
    text-align: left;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.access-denied-list li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: #6b7280;
    font-size: 1rem;
}

.access-denied-list i {
    color: #ef4444;
    font-size: 1.2rem;
}

.access-denied-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.access-denied-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

@media (max-width: 640px) {
    .access-denied-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .access-denied-actions .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>
@endsection
