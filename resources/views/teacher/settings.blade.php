@extends('layouts.unified-layout-new')

@section('title', 'Pengaturan Guru')

@section('content')
@include('components.page-header', [
    'title' => 'Pengaturan',
    'description' => 'Kelola pengaturan akun dan preferensi',
    'icon' => 'fas fa-cog',
    'breadcrumbs' => [
        ['text' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['text' => 'Pengaturan']
    ]
])

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Profil</h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" rows="3" placeholder="Ceritakan tentang diri Anda"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ubah Password</h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" class="form-control" placeholder="Masukkan password lama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" placeholder="Masukkan password baru">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" placeholder="Konfirmasi password baru">
                    </div>
                    <button type="submit" class="btn btn-warning">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Preferensi Notifikasi</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notifTugas" checked>
                    <label class="form-check-label" for="notifTugas">
                        Notifikasi Tugas Baru
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notifUjian" checked>
                    <label class="form-check-label" for="notifUjian">
                        Notifikasi Ujian
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notifSistem">
                    <label class="form-check-label" for="notifSistem">
                        Notifikasi Sistem
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notifEmail" checked>
                    <label class="form-check-label" for="notifEmail">
                        Notifikasi via Email
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notifBrowser" checked>
                    <label class="form-check-label" for="notifBrowser">
                        Notifikasi Browser
                    </label>
                </div>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Simpan Preferensi</button>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Tema & Tampilan</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tema Warna</label>
                    <select class="form-select">
                        <option value="light">Terang</option>
                        <option value="dark">Gelap</option>
                        <option value="auto">Otomatis</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Bahasa</label>
                    <select class="form-select">
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en">English</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Tema</button>
    </div>
</div>
@endsection
