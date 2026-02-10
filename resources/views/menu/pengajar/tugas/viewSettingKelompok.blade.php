@extends('layouts.unified-layout')

@section('container')
    {{-- Cek peran pengguna --}}
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    {{-- Navigasi Breadcrumb --}}
    <div class="col-15 ps-1 pe-1 mb-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Setting Kelompok</li>
            </ol>
        </nav>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Judul Halaman --}}
    <div class="ps-4 pe-4 mt-4  pt-4">
        <h2 class="display-6 fw-bold">

        </h2>
    </div>



    {{-- Baris utama --}}
    <div class="col-12 ps-4 pe-4 mb-4">
        <div class="row">
            {{-- Bagian Kiri --}}
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="row">

                    {{-- Informasi Tugas --}}
                    <div class="mb-4 p-4 bg-white rounded-4">
                        <div class=" p-4">
                            <h4 class="fw-bold mb-2">Setting Kelompok
                            </h4>


                            <hr>

                            <div class="row">
                                <h3 class="fw-bold text-primary">{{ $tugas->name }}@if ($tugas->isHidden == 1)
                                        <i class="fa-solid fa-lock fa-bounce text-danger"></i>
                                    @endif
                                </h3>

                                <h4 class="mb-4">Kelompok : {{ $kelompok->name }}</h4>


                                <hr>
                                List Siswa
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Kelompok</th>
                                            <th>Tambah</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($user as $key)
                                                @php
                                                    $userKelompok = App\Models\AnggotaTugasKelompok::where('user_id', $key->id)
                                                        ->where('tugas_id', $tugas->id)
                                                        ->first();
                                                    if ($userKelompok) {
                                                        $namaKelompok = App\Models\TugasKelompok::where('id', $userKelompok['tugas_kelompok_id'])->first();
                                                        if ($userKelompok['tugas_kelompok_id'] == $kelompok->id) {
                                                            // dd('here');
                                                            $status = 1;
                                                        } else {
                                                            $status = 0;
                                                        }
                                                    } else {
                                                        $namaKelompok['name'] = '-';
                                                        $status = 0;
                                                        echo '-';
                                                    }
                                                @endphp
                                                <tr class="@if ($status == 1) table-success @endif">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $key->name }}</td>
                                                    <td>
                                                        {{ $namaKelompok['name'] }}
                                                        @if ($userKelompok)
                                                            @if ($userKelompok['isKetua'] == 1 && $userKelompok['tugas_kelompok_id'] == $kelompok->id)
                                                                <span class="badge badge-primary p-2">Ketua Kelompok</span>
                                                            @elseif($userKelompok['isKetua'] == 1 && $userKelompok['tugas_kelompok_id'] != $kelompok->id)
                                                                <span class="badge badge-dark p-2">Ketua Kelompok
                                                                    Lain</span>
                                                            @endif
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($status == 0)
                                                            <a href="{{ route('tambah-anggota', ['user_id' => $key->id, 'tugas_kelompok_id' => $kelompok->id, 'isKetua' => 1, 'tugas_id' => $tugas->id]) }}"
                                                                class="btn btn-outline-primary">
                                                                <i class="fa-solid fa-star"></i> Ketua</a>
                                                            <a href="{{ route('tambah-anggota', ['user_id' => $key->id, 'tugas_kelompok_id' => $kelompok->id, 'isKetua' => 0, 'tugas_id' => $tugas->id]) }}"
                                                                class="btn btn-outline-primary"><i
                                                                    class="fa-solid fa-users"></i>
                                                                Anggota</a>
                                                        @else
                                                            <a href="{{ route('delete-anggota', ['user_id' => $key->id, 'tugas_kelompok_id' => $kelompok->id]) }}"
                                                                class="btn btn-danger">X</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>



                                <hr>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    @endsection
