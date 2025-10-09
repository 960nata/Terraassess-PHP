@extends('layout.template.loginRegistTemplate')
@section('container')
    <div class="row justify-content-center">
        {{-- Box Kiri - Register Form --}}
        <div class="col-lg-5 col-md-6 col-sm-8 col-12" style="margin-top: 50px;">
            <div class="col-12 text-center d-block d-lg-none mb-4">
                <div id="anim2" class="p-4"></div>
            </div>
            <div class="col-12 text-center mb-4">
                <div class="space-animation-placeholder">
                    <i class="fas fa-user-plus text-primary"></i>
                    <small class="text-white-75">TerraAssessment IoT System</small>
                </div>
            </div>
            <div class="login-card px-4 py-5">
                <div class="card-body">

                    {{-- Mulai Form Registrasi --}}
                    <form action="{{ route('validate') }}" method="POST">
                        @csrf

                        <div class="text-center mb-4">
                            <h1 class="text-white display-4 fw-bold">Join Us</h1>
                            <span class="text-white-75 fs-5">Buat akun baru untuk memulai perjalanan antariksa</span>
                            <hr class="border-white-25 my-4">
                        </div>

                        {{-- Alert Kesalahan --}}
                        @if (session()->has('nis-error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('nis-error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Input Email --}}
                        <div class="form-group mb-4">
                            <label for="email" class="text-white-75 fw-semibold mb-2">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input class="form-control" id="email" type="email"
                                placeholder="Masukan email anda..." name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Input Nomor Telepon (Opsional) --}}
                        <div class="form-group mb-4">
                            <label for="noTelp" class="text-white-75 fw-semibold mb-2">
                                <i class="fas fa-phone me-2"></i>Nomor Telepon <span class="text-white-50 small">(Opsional)</span>
                            </label>
                            <input class="form-control" id="noTelp" type="number" placeholder="0851xxx"
                                name="noTelp" value="{{ old('noTelp') }}">
                            @error('noTelp')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div class="form-group mb-4">
                            <label for="password" class="text-white-75 fw-semibold mb-2">
                                <i class="fas fa-lock me-2"></i>Password <span class="text-white-50 small">(Min : 8)</span>
                            </label>
                            <input class="form-control" id="password" name="password" type="password"
                                placeholder="Masukan Password anda..." required>
                            @error('password')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="form-group mb-4">
                            <label for="confirm-password" class="text-white-75 fw-semibold mb-2">
                                <i class="fas fa-lock me-2"></i>Confirm Password
                            </label>
                            <input class="form-control" id="confirm-password" name="confirm-password"
                                type="password" placeholder="Konfirmasi Password anda..." required>
                            @error('confirm-password')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Input Nomor Induk Siswa (NIS) --}}
                        <div class="form-group mb-4">
                            <label for="nis" class="text-white-75 fw-semibold mb-2">
                                <i class="fas fa-id-card me-2"></i>Nomor Induk Siswa (NIS)
                            </label>
                            <input class="form-control" id="nis" name="nis"
                                placeholder="Masukan NIS anda..." value="{{ old('nis') }}" required>
                            @error('nis')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Checkbox Persetujuan --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                            <label class="form-check-label text-white-75" for="flexCheckDefault">
                                Saya mengisi data saya dengan benar.
                            </label>
                        </div>

                        {{-- Tombol Registrasi --}}
                        <button class="btn btn-primary w-100 py-3 mt-4 animate-btn-small" type="submit">
                            <i class="fas fa-user-plus me-2"></i>Join the System
                        </button>
                    </form>

                    {{-- Form Selesai --}}

                    {{-- Informasi untuk Login --}}
                    <div class="mt-2">
                        <hr class="border-white-25">
                        <div class="text-center">
                            <span class="small text-white-50">Sudah memiliki akun? <a href="#" onclick="openLoginPopup()" class="text-white">Login</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
