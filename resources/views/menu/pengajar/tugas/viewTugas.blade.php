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
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapel['id']]) }}">
                        {{ $mapel['name'] }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> Tugas</li>
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
    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="display-6 fw-bold">
            <a
                href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapel['id']]) }}">
                <button type="button" class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a> 
            <i class="fa-solid fa-clipboard-list text-primary me-2"></i>Detail Tugas
        </h2>
    </div>

    {{-- Baris utama --}}
    <div class="ps-4 pe-4 mb-4">
        <div class="row">
            {{-- Bagian Kiri --}}
            <div class="col-xl-9 col-lg-12 col-md-12">
                <div class="row">

                    {{-- Informasi Tugas --}}
                    <div class="mb-4 p-4 bg-white rounded-2 shadow-sm">
                        <div class="p-2">
                            <h4 class="fw-bold mb-3 text-primary">
                                <i class="fa-solid fa-info-circle me-2"></i>Informasi Tugas
                            </h4>
                            <hr>

                            <div class="row">
                                <h3 class="fw-bold text-primary">{{ $tugas->name }}@if ($tugas->isHidden == 1)
                                        <i class="fa-solid fa-lock fa-bounce text-danger"></i>
                                    @endif
                                </h3>
                                <hr>
                                @php
                                    $dueDateTime = \Carbon\Carbon::parse($tugas->due); // Mengatur timezone ke Indonesia (ID)
                                    $localTime = \Carbon\Carbon::parse($tugas->due)->setTimeZone('asia/jakarta'); // Mengatur timezone ke Indonesia (ID)
                                    $now = \Carbon\Carbon::now(); // Mengatur timezone ke Indonesia (ID)
                                    $timeUntilDue = $dueDateTime->diff($now); // Perbedaan waktu antara sekarang dan waktu jatuh tempo
                                    // dd($dueDateTime, $now, $timeUntilDue);
                                    $daysUntilDue = $timeUntilDue->days; // Jumlah hari hingga jatuh tempo
                                    $hoursUntilDue = $timeUntilDue->h; // Jumlah jam hingga jatuh tempo
                                    $minutesUntilDue = $timeUntilDue->i; // Jumlah menit hingga jatuh tempo
                                @endphp
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-muted mb-2">Status Tugas</h6>
                                            @if ($dueDateTime < $now)
                                                <span class="badge bg-danger fs-6">Ditutup</span>
                                            @else
                                                <span class="badge bg-success fs-6">Dibuka</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-muted mb-2">Sisa Waktu</h6>
                                            @if ($dueDateTime < $now)
                                                <span class="text-danger fw-bold">Tugas Ditutup</span>
                                            @else
                                                <span class="text-primary fw-bold">
                                                    {{ $daysUntilDue }} hari, {{ $hoursUntilDue }} jam, {{ $minutesUntilDue }} menit lagi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-muted mb-2">Deadline</h6>
                                            <span class="fw-bold text-dark">{{ $localTime->formatLocalized('%d %B %Y %H:%M') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-muted mb-2">Tipe Tugas</h6>
                                            @if ($tugas->tipe == 1)
                                                <span class="badge bg-primary fs-6">Self Assessment</span>
                                            @elseif($tugas->tipe == 2)
                                                <span class="badge bg-info fs-6">Quiz</span>
                                            @elseif($tugas->tipe == 3)
                                                <span class="badge bg-warning fs-6">Pre Test</span>
                                            @elseif($tugas->tipe == 4)
                                                <span class="badge bg-success fs-6">Peer Assessment</span>
                                            @elseif($tugas->tipe == 5)
                                                <span class="badge bg-secondary fs-6">Kelompok: Pilihan Ganda</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Assesment Kelompok Nilai --}}
                    @if ($tugas->tipe == 4 && Auth()->User()->roles_id == 3)
                        <div class="col-15 mb-1">
                            <div class="p-1 bg-white rounded-4">
                                <div class="h-100 p-1 border border-primary text-center">
                                    <div class="w-100 h-50 text-center rounded-2 bg-primary text-white p-4">
                                        @php
                                            $kelompok = App\Models\TugasKelompok::where('tugas_id', $tugas->id)->get();
                                            $anggota = App\Models\AnggotaTugasKelompok::where('user_id', Auth()->user()->id)->first();
                                            $kelompokNow = App\Models\TugasKelompok::where('id', $anggota['tugas_kelompok_id'])->first();
                                        @endphp
                                        <h2 class="fw-bold">Nilai Anda</h2>
                                        <h3 class="fw-bold">{{ $kelompokNow['nilai'] }}</h3>
                                    </div>
                                    <div class="text-center mt-2">
                                        <h2 class="fw-bold text-primary ">Daftar Kelompok :</h2>
                                    </div>

                                    @if ($kelompok)
                                        @if (count($kelompok) > 0)
                                            <div class="row mt-1">
                                                @foreach ($kelompok as $key)
                                                    <div
                                                        class="p-1 text-center rounded-2 border border-primary m-3 col-md-3 col-6">
                                                        <h4> {{ $key->name }}</h4>
                                                        <h5>
                                                            @if ($anggota['tugas_kelompok_id'] != $key->id)
                                                                @php
                                                                    $exist = App\Models\KelompokNilai::where('tugas_kelompok_id', $key->id)
                                                                        ->where('to_kelompok', $anggota['tugas_kelompok_id'])
                                                                        ->first();
                                                                    if ($exist != null) {
                                                                        $statusMengisi = 1;
                                                                    } else {
                                                                        $statusMengisi = 0;
                                                                    }
                                                                @endphp
                                                                @if ($statusMengisi == 1)
                                                                    <span class="badge badge-success">Telah Mengisi
                                                                        Nilai</span>
                                                                @else
                                                                    <span class="badge badge-danger">Belum Mengisi
                                                                        Nilai</span>
                                                                @endif
                                                            @elseif($anggota['tugas_kelompok_id'] == $key->id)
                                                                <span class="badge badge-success">Kelompok anda</span>
                                                            @endif
                                                        </h5>
                                                        <h6 class="border border-primary p-2 text-primary">

                                                            @if (count($key->fileKelompok) > 0)
                                                                @foreach ($key->fileKelompok as $key2)
                                                                    <a class="mb-2"
                                                                        href="{{ route('getFileTugas', ['namaFile' => $key2->file]) }}">{{ $key2->file }}</a>
                                                                @endforeach
                                                            @endif
                                                        </h6>
                                                        @if ($anggota['tugas_kelompok_id'] != $key->id)
                                                            @php

                                                                $exist = App\Models\KelompokNilai::where('tugas_kelompok_id', $anggota['tugas_kelompok_id'])
                                                                    ->where('to_kelompok', $key->id)
                                                                    ->first();
                                                                if ($exist != null) {
                                                                    $status = 1;
                                                                } else {
                                                                    $status = 0;
                                                                }
                                                                // dd($status);
                                                            @endphp
                                                            @if (count($key->fileKelompok) > 0 && ($status == 0 && $anggota['isKetua'] == 1))
                                                                <form method="post"
                                                                    action="{{ route('submitNilaiKelompok', ['fromKelompok' => $anggota['tugas_kelompok_id'], 'toKelompok' => $key->id]) }}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-8">
                                                                            <div class="">
                                                                                <div class="form-check text-left">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="nilai"
                                                                                        value="100"
                                                                                        id="flexRadioDefault1">
                                                                                    <label class="form-check-label"
                                                                                        for="flexRadioDefault1">
                                                                                        Sangat Setuju
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check text-left">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="nilai"
                                                                                        value="75"
                                                                                        id="flexRadioDefault2" checked>
                                                                                    <label class="form-check-label"
                                                                                        for="flexRadioDefault2">
                                                                                        Setuju
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check text-left">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="nilai"
                                                                                        value="50"
                                                                                        id="flexRadioDefault3" checked>
                                                                                    <label class="form-check-label"
                                                                                        for="flexRadioDefault3">
                                                                                        Tidak Setuju
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check text-left">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="nilai"
                                                                                        value="25"
                                                                                        id="flexRadioDefault3" checked>
                                                                                    <label class="form-check-label"
                                                                                        for="flexRadioDefault3">
                                                                                        Sangat Tidak Setuju
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <button class="btn btn-primary">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            @elseif($status == 1 && $anggota['isKetua'] == 1)
                                                                Sudah anda nilai
                                                            @elseif($status == 0 && $anggota['isKetua'] == 0)
                                                                Hanya ketua kelompok yang bisa menilai
                                                            @else
                                                                belum mengerjakan
                                                            @endif
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Tampilan Tugas --}}
                    <div class="col-12 mb-4">
                        <div class="p-4 bg-white rounded-4">
                            <div class="h-100 p-4 border border-primary">
                                <h5 class="fw-bold">Perintah :</h5>
                                <p>
                                    {!! $tugas->content !!}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Tampilan File --}}
                    @if ($tugas->tipe == 1 || $tugas->tipe == 4)
                        <div class="col-15 mb-2">
                            <div class="p-2 bg-white rounded-4">
                                <div class="h-100 p-2">
                                    <h4 class="fw-bold mb-2">Files</h4>
                                    <hr>
                                    @if (count($tugas->TugasFile) > 0)
                                        <ul class="list-group">
                                            <div class="row">
                                                @foreach ($tugas->TugasFile as $key)
                                                    <div class="col-lg-4 col-sm-6 col-12 mb-2">

                                                        <a href="{{ route('getFileTugas', ['namaFile' => $key->file]) }}">
                                                            <li class="list-group-item">
                                                                @if (Str::endsWith($key->file, ['.jpg', '.jpeg', '.png', '.gif']))
                                                                    <i class="fa-solid fa-image"></i>
                                                                @elseif (Str::endsWith($key->file, ['.mp4', '.avi', '.mov']))
                                                                    <i class="fa-solid fa-video"></i>
                                                                @elseif (Str::endsWith($key->file, ['.pdf']))
                                                                    <i class="fa-solid fa-file-pdf"></i>
                                                                @elseif (Str::endsWith($key->file, ['.doc', '.docx']))
                                                                    <i class="fa-solid fa-file-word"></i>
                                                                @elseif (Str::endsWith($key->file, ['.ppt', '.pptx']))
                                                                    <i class="fa-solid fa-file-powerpoint"></i>
                                                                @elseif (Str::endsWith($key->file, ['.xls', '.xlsx']))
                                                                    <i class="fa-solid fa-file-excel"></i>
                                                                @elseif (Str::endsWith($key->file, ['.txt']))
                                                                    <i class="fa-solid fa-file-alt"></i>
                                                                @elseif (Str::endsWith($key->file, ['.mp3']))
                                                                    <i class="fa-solid fa-music"></i>
                                                                @else
                                                                    <i class="fa-solid fa-file"></i>
                                                                @endif
                                                                {{ Str::substr($key->file, 5, 20) }}
                                                            </li>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="small">(Tidak ada file untuk Tugas ini)</span>
                                    @endif

                                    </ul>
                                </div>
                            </div>
                        </div>

                    @endif


                    @if (Auth()->User()->roles_id == 2 && $tugas->tipe == 2)
                        <hr>
                        <h4>Soal</h4>
                        @foreach ($tugas->TugasQuiz as $key)
                            <form action="{{ route('tugasNilaiQuiz') }}" method="POST">
                                @csrf
                                {{-- <input type=""> --}}

                                <div class="col-lg-12 col-12 bg-white rounded-2 mb-2 p-2 ">
                                    <div class="row">
                                        <div class="col-lg-15">
                                            <div class=" border border border-primary shadow-sm  p-2 mt-2 pertanyaan">
                                                <div class="">
                                                    <h3>Soal <span
                                                            class="badge badge-primary">{{ $loop->iteration }}</span>
                                                    </h3>
                                                    <div class="mb-3">
                                                        <label for="pertanyaan${nomorPertanyaan}"
                                                            class="form-label">Pertanyaan</label>
                                                        <div class="border border-secondary p-4 rounded-2"
                                                            id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]"
                                                            rows="3" disabled>{!! $key->soal !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="accordion mb-2 mt-2" id="ujian{{ $loop->iteration }}">
                                                <div class="accordion-item ">
                                                    <h2 class="accordion-header">
                                                        <button
                                                            class="accordion-button bg-outline-danger  collapsed fw-bold"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#ujian{{ $loop->iteration }}-collapseOne"
                                                            aria-controls="ujian{{ $loop->iteration }}-collapseOne">
                                                            Submittion Siswa
                                                        </button>
                                                    </h2>
                                                    <div id="ujian{{ $loop->iteration }}-collapseOne"
                                                        class="accordion-collapse collapse">
                                                        <div class="accordion-body table-responsive"
                                                            style="max-height: 300px; overflow-y: auto;">
                                                            <table id="table"
                                                                class="table table-striped table-hover table-lg ">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Nama</th>
                                                                        <th scope="col">Submittion</th>
                                                                        <th scope="col">Nilai</th>
                                                                        <th scope="col">Input Nilai</th>
                                                                        <th scope="col">Koreksi</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($kelas->User as $key2)
                                                                        @php
                                                                            // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan
                                                                            $userTugas = $key->TugasJawabanMultiple
                                                                                ->where('tugas_quiz_id', $key->id)
                                                                                ->where('user_id', $key2->id)
                                                                                ->first();
                                                                            // dd('here');
                                                                            $submition = App\Models\TugasJawabanMultiple::where('user_id', $key2->id)
                                                                                ->where('tugas_quiz_id', $key->id)
                                                                                ->first();
                                                                            // $nilai = $userTugas && is_numeric($submition['nilai']) ? intval($submition['nilai']) : null;
                                                                            if ($submition) {
                                                                                $nilai = $submition['nilai'];
                                                                                $koreksi = $submition['koreksi'];
                                                                            } else {
                                                                                $nilai = null;
                                                                                $koreksi = null;
                                                                            }
                                                                            // dd($nilai);
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>{{ $key2->name }}</td>
                                                                            <td>
                                                                                @if ($submition)
                                                                                    {{ $submition['user_jawaban'] }}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($userTugas)
                                                                                    @if ($nilai !== null && $nilai >= 0)
                                                                                        {{ $nilai }}
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                            <input type="hidden" name="siswaId[]"
                                                                                value="{{ $key2->id }}">
                                                                            <input type="hidden" name="quizId[]"
                                                                                value="{{ $key->id }}">
                                                                            @if ($tugas->tipe == 2)
                                                                                <td class="w-25">
                                                                                    <input type="number"
                                                                                        class="form-control w-100"
                                                                                        placeholder="-" aria-label="nilai"
                                                                                        name="nilai[]"
                                                                                        value="{{ $nilai !== null ? $nilai : '' }}">
                                                                                </td>
                                                                                <td class="w-25">
                                                                                    <input type="text"
                                                                                        class="form-control w-100"
                                                                                        placeholder="-"
                                                                                        aria-label="koreksi"
                                                                                        name="koreksi[]"
                                                                                        value="{{ $koreksi !== null ? $koreksi : '' }}">
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach

                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                        @endforeach

                        <h5>Nilai Total</h5>
                        <div class="col-lg-15">
                            <div class="accordion mb-2 mt-2">
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-outline-danger  collapsed fw-bold"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#total-collapseOne"
                                            aria-controls="total-collapseOne">
                                            Nilai Total
                                        </button>
                                    </h2>
                                    <div id="total-collapseOne" class="accordion-collapse collapse">
                                        <div class="accordion-body table-responsive"
                                            style="max-height: 300px; overflow-y: auto;">
                                            <table id="table" class="table table-striped table-hover table-lg ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Nama</th>

                                                        <th scope="col">Nilai</th>

                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($kelas->User as $key2)
                                                        @php
                                                            $tugasQuiz = App\Models\TugasQuiz::where('tugas_id', $tugas->id)->pluck('id');

                                                            // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan
                                                            // $userTugas = $key2->TugasJawabanMultiple
                                                            //     ->wherein('tugas_quiz_id', $tugasQuiz)
                                                            //     ->where('user_id', $key2->id)
                                                            //     ->get();
                                                            $submition = App\Models\TugasJawabanMultiple::where('user_id', $key2->id)
                                                                ->wherein('tugas_quiz_id', $tugasQuiz)
                                                                ->get();
                                                            // $nilai = $userTugas && is_numeric($submition['nilai']) ? intval($submition['nilai']) : null;
                                                            // dd($submition);
                                                            if ($submition) {
                                                                $nilai = 0;
                                                                foreach ($submition as $key) {
                                                                    $nilai += $key['nilai'];
                                                                }
                                                                // dd($nilai);
                                                            } else {
                                                                $nilai = 0;
                                                            }

                                                            // dd($nilai);

                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $key2->name }}</td>
                                                            <td>
                                                                {{ $nilai }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-lg w-100" type="submit">Simpan</button>
                        </form>
                    @endif

                    @if (Auth()->User()->roles_id == 2 && $tugas->tipe == 3)
                        <hr>
                        <h4>Soal Pilihan Ganda</h4>
                        <form action="{{ route('ujianUpdateNilai') }}" method="POST">
                            @csrf
                            {{-- <input type="text" name="token" value="{{ encrypt($key->id) }}"> --}}
                            @foreach ($tugas->TugasMultiple as $key)
                                <div class="col-lg-15 col-15 bg-white rounded-2 mb-2 p-2 ">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div
                                                        class=" border border border-primary shadow-sm  p-4 mt-4 pertanyaan">
                                                        <div class="">
                                                            <h3>Soal <span
                                                                    class="badge badge-primary">{{ $loop->iteration }}</span>
                                                            </h3>
                                                            <div class="mb-3">
                                                                <label for="pertanyaan${nomorPertanyaan}"
                                                                    class="form-label">Pertanyaan</label>
                                                                <div class="border border-secondary p-4 rounded-2"
                                                                    id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]"
                                                                    rows="3" disabled>
                                                                    {!! $key->soal !!}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row">
                                                        <div class="col-6 mb-1">
                                                            <label class="form-label">A
                                                            </label>
                                                            <input type="text"
                                                                class="form-control @if ($key->jawaban == 'a') text-white fw-bold bg-success @endif"
                                                                disabled value="{{ $key->a }}">
                                                        </div>
                                                        <div class="col-6 mb-1">
                                                            <label class="form-label">B
                                                            </label>
                                                            <input type="text"
                                                                class="form-control @if ($key->jawaban == 'b') text-white fw-bold bg-success @endif"
                                                                disabled value="{{ $key->b }}">
                                                        </div>
                                                        <div class="col-6 mb-1">
                                                            <label class="form-label">C
                                                            </label>
                                                            <input type="text"
                                                                class="form-control @if ($key->jawaban == 'c') text-white fw-bold bg-success @endif"
                                                                disabled value="{{ $key->c }}">
                                                        </div>
                                                        @if ($key->d)
                                                            <div class="col-6 mb-1">
                                                                <label class="form-label">D
                                                                </label>
                                                                <input type="text"
                                                                    class="form-control @if ($key->jawaban == 'd') text-white fw-bold bg-success @endif"
                                                                    disabled value="{{ $key->d }}">
                                                            </div>
                                                        @endif
                                                        @if ($key->e)
                                                            <div class="col-6 mb-1">
                                                                <label class="form-label">E
                                                                </label>
                                                                <input type="text"
                                                                    class="form-control @if ($key->jawaban == 'e') text-white fw-bold bg-success @endif"
                                                                    disabled value="{{ $key->e }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-15">
                                            <div class="accordion mb-2 mt-2" id="ujian{{ $loop->iteration }}">
                                                <div class="accordion-item ">
                                                    <h2 class="accordion-header">
                                                        <button
                                                            class="accordion-button bg-outline-danger  collapsed fw-bold"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#ujian{{ $loop->iteration }}-collapseOne"
                                                            aria-controls="ujian{{ $loop->iteration }}-collapseOne">
                                                            Submittion Siswa
                                                        </button>
                                                    </h2>

                                                    <div id="ujian{{ $loop->iteration }}-collapseOne"
                                                        class="accordion-collapse collapse">
                                                        <div class="accordion-body table-responsive"
                                                            style="max-height: 300px; overflow-y: auto;">
                                                            <table id="table"
                                                                class="table table-striped table-hover table-lg ">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Nama</th>
                                                                        <th scope="col">Submittion</th>
                                                                        <th scope="col">Nilai</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($kelas->User as $key2)
                                                                        @php
                                                                            // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan
                                                                            $userTugas = $key->TugasJawabanMultiple->where('tugasQuiz_id', $key->id)->first();
                                                                            $submition = App\Models\TugasJawabanMultiple::where('user_id', $key2->id)
                                                                                ->where('tugas_multiple_id', $key->id)
                                                                                ->first();
                                                                            $nilai = $userTugas && is_numeric($userTugas->nilai) ? intval($userTugas->nilai) : null;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>{{ $key2->name }}</td>
                                                                            <td>
                                                                                @if ($submition)
                                                                                    {{ $submition['user_jawaban'] }}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($submition)
                                                                                    {{ $submition['nilai'] }}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <h5>Nilai Total</h5>
                            <div class="col-lg-15">
                                <div class="accordion mb-2 mt-2">
                                    <div class="accordion-item ">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button bg-outline-danger  collapsed fw-bold"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#total-collapseOne" aria-controls="total-collapseOne">
                                                Nilai Total
                                            </button>
                                        </h2>
                                        <div id="total-collapseOne" class="accordion-collapse collapse">
                                            <div class="accordion-body table-responsive"
                                                style="max-height: 300px; overflow-y: auto;">
                                                <table id="table" class="table table-striped table-hover table-lg ">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Nama</th>

                                                            <th scope="col">Nilai</th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($kelas->User as $key2)
                                                            @php
                                                                $tugasQuiz = App\Models\TugasMultiple::where('tugas_id', $tugas->id)->pluck('id');
                                                                // dd($tugasQuiz);
                                                                // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan
                                                                // $userTugas = $key2->TugasJawabanMultiple
                                                                //     ->wherein('tugas_quiz_id', $tugasQuiz)
                                                                //     ->where('user_id', $key2->id)
                                                                //     ->get();
                                                                $submition = App\Models\TugasJawabanMultiple::where('user_id', $key2->id)
                                                                    ->wherein('tugas_multiple_id', $tugasQuiz)
                                                                    ->get();
                                                                // $nilai = $userTugas && is_numeric($submition['nilai']) ? intval($submition['nilai']) : null;
                                                                // dd($submition);
                                                                if ($submition) {
                                                                    $nilai = 0;
                                                                    foreach ($submition as $key) {
                                                                        $nilai += $key['nilai'];
                                                                    }
                                                                    // dd($nilai);
                                                                } else {
                                                                    $nilai = 0;
                                                                }

                                                            @endphp
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $key2->name }}</td>
                                                                <td>
                                                                    {{ $nilai }}
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-lg w-100" type="submit">Simpan</button>


                        </form>
                    @endif
                    @if (Auth()->User()->roles_id == 2 && $tugas->tipe == 5)
                        <hr>
                        <h4>Kelompok Pilihan ganda</h4>
                        {{-- <form action="{{ route('ujianUpdateNilai') }}" method="POST"> --}}
                        {{-- @csrf --}}
                        {{-- <input type="text" name="token" value="{{ encrypt($key->id) }}"> --}}
                        @foreach ($tugas->TugasKelompokQuiz as $key)
                            <div class="col-lg-12 col-12 bg-white rounded-2 mb-2 p-2 ">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-17">
                                                <div class=" border border border-primary shadow-sm  p-1 mt-1 pertanyaan">
                                                    <div class="">
                                                        <h3>Soal <span
                                                                class="badge badge-primary">{{ $loop->iteration }}</span>
                                                        </h3>
                                                        <div class="mb-2">
                                                            <label for="pertanyaan${nomorPertanyaan}"
                                                                class="form-label">Pertanyaan</label>
                                                            <div class="border border-secondary p-4 rounded-2"
                                                                id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]"
                                                                rows="3" disabled>
                                                                {!! $key->soal !!}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-6 mb-1">
                                                        <label class="form-label">Jawaban
                                                        </label>
                                                        <input type="text"
                                                            class="form-control text-white fw-bold bg-success"
                                                            value="{{ $key->jawaban }}" disabled>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="accordion mb-2 mt-2" id="ujian{{ $loop->iteration }}">
                                            <div class="accordion-item ">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button bg-outline-danger  collapsed fw-bold"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#ujian{{ $loop->iteration }}-collapseOne"
                                                        aria-controls="ujian{{ $loop->iteration }}-collapseOne">
                                                        Submittion Kelompok
                                                    </button>
                                                </h2>

                                                <div id="ujian{{ $loop->iteration }}-collapseOne"
                                                    class="accordion-collapse collapse">
                                                    <div class="accordion-body table-responsive"
                                                        style="max-height: 300px; overflow-y: auto;">
                                                        <table id="table"
                                                            class="table table-striped table-hover table-lg ">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Nama</th>
                                                                    <th scope="col">Submittion</th>
                                                                    <th scope="col">Nilai</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @php
                                                                    $kelompok = App\Models\TugasKelompok::where('tugas_id', $tugas->id)->get();
                                                                @endphp
                                                                @foreach ($kelompok as $key2)
                                                                    @php
                                                                        // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan

                                                                        $submition = App\Models\TugasKelompokQuizJawaban::where('tugas_kelompok_id', $key2['id'])->first();
                                                                        // $nilai = $userTugas && is_numeric($userTugas->nilai) ? intval($userTugas->nilai) : null;
                                                                    @endphp
                                                                    <tr>
                                                                        {{-- {{ dd($kelompok) }} --}}
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $key2['name'] }}</td>
                                                                        <td>
                                                                            @if (isset($submition))
                                                                                @if ($submition->jawaban != null)
                                                                                    {{ $submition->jawaban }}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if (isset($submition))
                                                                                @if ($submition->nilai != null)
                                                                                    {{ $submition->nilai }}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <h5>Nilai Total</h5>
                        <div class="col-lg-15">
                            <div class="accordion mb-2 mt-2">
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-outline-danger  collapsed fw-bold"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#total-collapseOne"
                                            aria-controls="total-collapseOne">
                                            Nilai Total
                                        </button>
                                    </h2>
                                    <div id="total-collapseOne" class="accordion-collapse collapse">
                                        <div class="accordion-body table-responsive"
                                            style="max-height: 300px; overflow-y: auto;">
                                            <table id="table" class="table table-striped table-hover table-lg ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Nama</th>
                                                        <th scope="col">Nilai</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php
                                                        $kelompok = App\Models\TugasKelompok::where('tugas_id', $tugas->id)->get();
                                                        $tugasQuiz = App\Models\TugasKelompokQuiz::where('tugas_id', $tugas->id)->pluck('id');
                                                    @endphp
                                                    @foreach ($kelompok as $key2)
                                                        @php
                                                            // $tugasKelompokJawaban = App\Models\TugasKelompokQuizJawaban::where('tugas_kelompok_id', $tugas->id)->pluck('id');
                                                            $x = App\Models\TugasKelompokQuizJawaban::where('tugas_kelompok_id', $key2['id'])
                                                                ->wherein('tugas_kelompok_quiz_id', $tugasQuiz)
                                                                ->get();
                                                            // dd($x);
                                                            // $submition = App\Models\TugasJawabanMultiple::where('user_id', $key2->id)
                                                            //     ->wherein('tugas_multiple_id', $tugasQuiz)
                                                            //     ->get();
                                                            // $nilai = $userTugas && is_numeric($submition['nilai']) ? intval($submition['nilai']) : null;
                                                            // dd($submition);
                                                            if ($x) {
                                                                $nilai = 0;
                                                                foreach ($x as $key) {
                                                                    $nilai += $key['nilai'];
                                                                }
                                                                // dd($nilai);
                                                            } else {
                                                                $nilai = 0;
                                                            }

                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $key2->name }}</td>
                                                            <td>
                                                                {{ $nilai }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <button class="btn btn-primary btn-lg w-100" type="submit">Simpan</button> --}}


                        {{-- </form> --}}
                    @endif

                    @if (Auth()->User()->roles_id == 2 && $tugas->tipe == 1)
                        @php
                            // Calculate progress statistics
                            $totalStudents = count($kelas->User);
                            $submittedStudents = 0;
                            $gradedStudents = 0;
                            $pendingStudents = 0;
                            
                            foreach ($kelas->User as $student) {
                                $userTugas = $student->UserTugas->where('tugas_id', $tugas['id'])->first();
                                if ($userTugas) {
                                    $submittedStudents++;
                                    if ($userTugas->nilai !== null && $userTugas->nilai >= 0) {
                                        $gradedStudents++;
                                    } else {
                                        $pendingStudents++;
                                    }
                                }
                            }
                            
                            $notStartedStudents = $totalStudents - $submittedStudents;
                            $completionPercentage = $totalStudents > 0 ? round(($gradedStudents / $totalStudents) * 100) : 0;
                        @endphp

                        {{-- Progress Tracking Card --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fa-solid fa-chart-line me-2"></i>Progress Siswa</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="text-center">
                                                    <div class="h4 text-primary fw-bold">{{ $totalStudents }}</div>
                                                    <div class="text-muted small">Total Siswa</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="text-center">
                                                    <div class="h4 text-success fw-bold">{{ $gradedStudents }}</div>
                                                    <div class="text-muted small">Sudah Dinilai</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="text-center">
                                                    <div class="h4 text-warning fw-bold">{{ $pendingStudents }}</div>
                                                    <div class="text-muted small">Menunggu Penilaian</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="text-center">
                                                    <div class="h4 text-danger fw-bold">{{ $notStartedStudents }}</div>
                                                    <div class="text-muted small">Belum Mengerjakan</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">Progress Penilaian</span>
                                                <span class="text-primary fw-bold">{{ $completionPercentage }}%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ $completionPercentage }}%" 
                                                     aria-valuenow="{{ $completionPercentage }}" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('siswaUpdateNilai', ['token' => encrypt($tugas['id'])]) }}"
                            method="post">
                            @csrf
                            {{-- Siswa dan Assignment --}}
                            <div class="accordion mb-4" id="sdsd">
                                <div class="accordion-item border-0 shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-primary text-white fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#sdsd-collapseOne"
                                            aria-controls="sdsd-collapseOne">
                                            <i class="fa-solid fa-users me-2"></i>Submittion Siswa
                                        </button>
                                    </h2>
                                    <div id="sdsd-collapseOne" class="accordion-collapse collapse show">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table id="table" class="table table-hover mb-0">
                                                    <thead class="table-primary">
                                                        <tr>
                                                            <th scope="col" class="text-dark fw-bold">#</th>
                                                            <th scope="col" class="text-dark fw-bold">Nama</th>
                                                            <th scope="col" class="text-dark fw-bold">Submittion</th>
                                                            <th scope="col" class="text-dark fw-bold">Nilai</th>
                                                            <th scope="col" class="text-dark fw-bold">Input Nilai</th>
                                                            <th scope="col" class="text-dark fw-bold">Feedback</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($kelas->User as $key)
                                                            @php
                                                                // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan
                                                                $userTugas = $key->UserTugas->where('tugas_id', $tugas['id'])->first();
                                                                $nilai = $userTugas && is_numeric($userTugas->nilai) ? intval($userTugas->nilai) : null;
                                                            @endphp

                                                            <tr class="align-middle">
                                                                <td class="text-dark fw-bold">{{ $loop->iteration }}</td>
                                                                <td class="text-dark fw-bold">{{ $key->name }}</td>
                                                                <td>
                                                                    @if ($userTugas)
                                                                        @if ($userTugas->UserTugasFile)
                                                                            @foreach ($userTugas->UserTugasFile as $file)
                                                                                <a class="d-block text-primary text-decoration-none fw-bold"
                                                                                    href="{{ route('getFileUser', ['namaFile' => $file->file]) }}">
                                                                                    <i class="fa-solid fa-file me-1"></i>{{ $file->file }}
                                                                                </a>
                                                                            @endforeach
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($userTugas)
                                                                        @if ($nilai !== null && $nilai >= 0)
                                                                            <span class="badge bg-success text-white fw-bold">{{ $nilai }}</span>
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <input type="hidden" name="siswaId[]"
                                                                    value="{{ $key->id }}">
                                                                <td class="w-25">
                                                                    <input type="number" class="form-control border-primary"
                                                                        placeholder="0-100" aria-label="nilai" name="nilai[]"
                                                                        value="{{ $nilai !== null ? $nilai : '' }}"
                                                                        min="0" max="100">
                                                                </td>
                                                                <td class="w-30">
                                                                    <div class="feedback-container">
                                                                        <textarea class="form-control feedback-textarea border-primary" 
                                                                            name="komentar[]" 
                                                                            rows="2" 
                                                                            placeholder="Berikan feedback untuk siswa...">{{ $userTugas->komentar ?? '' }}</textarea>
                                                                        <div class="quick-comments mt-2">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary quick-comment" data-comment="Bagus! Kerja yang sangat baik.">Bagus</button>
                                                                            <button type="button" class="btn btn-sm btn-outline-warning quick-comment" data-comment="Perlu perbaikan pada bagian ini.">Perlu Perbaikan</button>
                                                                            <button type="button" class="btn btn-sm btn-outline-success quick-comment" data-comment="Sangat memuaskan! Pertahankan!">Memuaskan</button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-lg w-100" type="submit">
                                <i class="fa-solid fa-save me-2"></i>Simpan Nilai & Feedback
                            </button>
                        </form>
                    @elseif ($tugas->tipe == 4 && Auth()->User()->roles_id == 2)
                        <div class="col-lg-12 col-12 bg-white rounded-2 mb-4 p-4 ">
                            <h4>Data Kelompok <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#tambah-kelompok">Tambah</button></h4>
                            @php
                                $kelompok = App\Models\TugasKelompok::where('tugas_id', $tugas->id)->get();
                            @endphp

                            @foreach ($kelompok as $key)
                                <div class="p-2 border border-dark  rounded-2 m-3">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h5 class=""> {{ $key->name }}</h5> <span
                                                class="p-2 badge badge-danger mb-2">Jumlah Anggota
                                                :
                                                {{ count($key->AnggotaTugasKelompok) }}</span>
                                            @php
                                                $ketua = App\Models\AnggotaTugasKelompok::where('tugas_kelompok_id', $key->id)
                                                    ->where('isKetua', 1)
                                                    ->first();
                                                if ($ketua) {
                                                    $ketuaNama = App\Models\User::where('id', $ketua['user_id'])->first();
                                                } else {
                                                    $ketuaNama['name'] = '-';
                                                }
                                            @endphp
                                            <span class="p-2 badge badge-danger mb-2">Nama Ketua
                                                :
                                                @if (isset($ketuaNama))
                                                    {{ $ketuaNama['name'] }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <div class="row">
                                                <div class="col-2 me-4  mb-2">
                                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalDeleteKelompok"
                                                        data-kelompok-id="{{ $key->id }}">X</button>
                                                </div>
                                                <div class="col-10 mb-2">
                                                    <a href="{{ route('settingKelompok', ['tugas' => $tugas->id, 'id_kelompok' => $key->id]) }}"
                                                        class="btn btn-primary h-100 w-100">Setting</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @elseif ($tugas->tipe == 5 && Auth()->User()->roles_id == 2)
                        <div class="col-lg-12 col-12 bg-white rounded-2 mb-2 p-2 ">
                            <h4>Data Kelompok <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#tambah-kelompok">Tambah</button></h4>
                            @php
                                $kelompok = App\Models\TugasKelompok::where('tugas_id', $tugas->id)->get();
                            @endphp

                            @foreach ($kelompok as $key)
                                <div class="p-4 border border-dark  rounded-2 m-3">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h5 class=""> {{ $key->name }}</h5> <span
                                                class="p-2 badge badge-danger mb-2">Jumlah Anggota
                                                :
                                                {{ count($key->AnggotaTugasKelompok) }}</span>
                                            @php
                                                $ketua = App\Models\AnggotaTugasKelompok::where('tugas_kelompok_id', $key->id)
                                                    ->where('isKetua', 1)
                                                    ->first();
                                                if ($ketua) {
                                                    $ketuaNama = App\Models\User::where('id', $ketua['user_id'])->first();
                                                } else {
                                                    $ketuaNama['name'] = '-';
                                                }
                                            @endphp
                                            <span class="p-2 badge badge-danger mb-2">Nama Ketua
                                                :
                                                @if (isset($ketuaNama))
                                                    {{ $ketuaNama['name'] }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <div class="row">
                                                <div class="col-2 me-4  mb-2">
                                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalDeleteKelompok"
                                                        data-kelompok-id="{{ $key->id }}">X</button>
                                                </div>
                                                <div class="col-10 mb-2">
                                                    <a href="{{ route('settingKelompok', ['tugas' => $tugas->id, 'id_kelompok' => $key->id]) }}"
                                                        class="btn btn-primary h-100 w-100">Setting</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @elseif (Auth()->User()->roles_id == 3 && $tugas->tipe == 1)
                        <h3 class="fw-bold text-primary">Submit Tugas
                            @if ($userTugas)
                                @if ($userTugas->status == 'Belum Mengerjakan')
                                    <span class="badge badge-danger p-2">{{ $userTugas->status }}</span>
                                @elseif($userTugas->status == 'Telah dinilai')
                                    <span class="badge badge-primary p-2">{{ $userTugas->status }}</span>
                                    <span class="badge badge-success p-2">{{ $userTugas->nilai }}</span>
                                @else
                                    <span class="badge badge-primary p-2">{{ $userTugas->status }}</span>
                                @endif
                            @else
                                <span class="badge badge-danger p-2">Belum Mengerjakan</span>
                            @endif
                        </h3>
                        @if ($userTugas)
                            {{-- Tampilan File --}}
                            <div class="col-15 mb-2">
                                <div class="p-4 bg-white rounded-4">
                                    <div class="h-100 p-2">
                                        <h4 class="fw-bold mb-2">Pekerjaan anda</h4>
                                        <hr>
                                        @if (count($userTugas->UserTugasFile) > 0)
                                            <ul class="list-group">
                                                <div class="row">
                                                    @foreach ($userTugas->UserTugasFile as $key)
                                                        <div class="col-lg-4 col-sm-6 col-12 mb-2">

                                                            <div class="list-group-item">
                                                                @if (Str::endsWith($key->file, ['.jpg', '.jpeg', '.png', '.gif']))
                                                                    <i class="fa-solid fa-image"></i>
                                                                @elseif (Str::endsWith($key->file, ['.mp4', '.avi', '.mov']))
                                                                    <i class="fa-solid fa-video"></i>
                                                                @elseif (Str::endsWith($key->file, ['.pdf']))
                                                                    <i class="fa-solid fa-file-pdf"></i>
                                                                @elseif (Str::endsWith($key->file, ['.doc', '.docx']))
                                                                    <i class="fa-solid fa-file-word"></i>
                                                                @elseif (Str::endsWith($key->file, ['.ppt', '.pptx']))
                                                                    <i class="fa-solid fa-file-powerpoint"></i>
                                                                @elseif (Str::endsWith($key->file, ['.xls', '.xlsx']))
                                                                    <i class="fa-solid fa-file-excel"></i>
                                                                @elseif (Str::endsWith($key->file, ['.txt']))
                                                                    <i class="fa-solid fa-file-alt"></i>
                                                                @elseif (Str::endsWith($key->file, ['.mp3']))
                                                                    <i class="fa-solid fa-music"></i>
                                                                @else
                                                                    <i class="fa-solid fa-file"></i>
                                                                @endif
                                                                <a href="{{ route('getFileUser', ['namaFile' => $key->file]) }}"
                                                                    class="text-decoration-none">
                                                                    {{ Str::substr($key->file, 5, 10) }}
                                                                </a>
                                                                @if ($dueDateTime > $now)
                                                                    @if ($userTugas)
                                                                        @if ($userTugas->status != 'Telah dinilai')
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm float-end"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modalDelete"
                                                                                onclick="changeValue('{{ $key->file }}')">
                                                                                X
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm float-end"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#modalDelete"
                                                                            onclick="changeValue('{{ $key->file }}')">
                                                                            X
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="small">(anda belum mengupload file)</span>
                                        @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($dueDateTime > $now)
                            @if ($userTugas)
                                @if ($userTugas->status != 'Telah dinilai')
                                    <form
                                        action="{{ route('submitTugas', ['token' => encrypt($kelas['id']), 'tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}"
                                        method="post" enctype="multipart/form-data" id="submitTugas">
                                        @csrf
                                        {{-- Konten Materi --}}
                                        <div class="mb-3">
                                            <label for="uploadFile" class="form-label">Upload</label>
                                            <!-- Dropzone -->
                                            <div id="my-dropzone" class="dropzone"></div>
                                        </div>
                                        {{-- Tombol Submit --}}
                                        <div class="">
                                            <button type="submit" class="btn-lg btn btn-primary w-100"
                                                id="btnSimpan">Simpan
                                                dan
                                                Lanjutkan</button>
                                        </div>
                                    </form>
                                @else
                                    {{-- Telat --}}
                                    <div class="mb-3 text-center bg-white p-4">
                                        <div class="border border-primary p-4">
                                            <span class="fw-bold text-danger">Upload ditutup</span>
                                        </div>

                                    </div>
                                @endif
                            @else
                                <form
                                    action="{{ route('submitTugas', ['token' => encrypt($kelas['id']), 'tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}"
                                    method="post" enctype="multipart/form-data" id="submitTugas">
                                    @csrf
                                    {{-- Konten Materi --}}
                                    <div class="mb-3">
                                        <label for="uploadFile" class="form-label">Upload</label>
                                        <!-- Dropzone -->
                                        <div id="my-dropzone" class="dropzone"></div>
                                    </div>
                                    {{-- Tombol Submit --}}
                                    <div class="">
                                        <button type="submit" class="btn-lg btn btn-primary w-100" id="btnSimpan">Simpan
                                            dan
                                            Lanjutkan</button>
                                    </div>
                                </form>
                            @endif
                        @endif
                    @elseif (Auth()->User()->roles_id == 3 && $tugas->tipe == 2)
                        @php
                            $tugasQuiz = App\Models\TugasQuiz::where('tugas_id', $tugas->id)->get();
                        @endphp
                        <form action="{{ route('submit-quiz', ['token' => encrypt($tugas->id)]) }}" method="post">
                            @csrf
                            @foreach ($tugasQuiz as $key)
                                @php
                                    $tugasStatus = App\Models\TugasJawabanMultiple::where('tugas_quiz_id', $key->id)
                                        ->where('user_id', Auth()->user()->id)
                                        ->first();
                                    $nilai = 0;
                                    if ($tugasStatus) {
                                        $userJawaban = $tugasStatus['user_jawaban'];
                                        $userKoreksi = $tugasStatus['koreksi'];
                                        $nilai += $tugasStatus['nilai'];
                                        // dd($userJawaban);
                                    } else {
                                        $userJawaban = null;
                                        $userKoreksi = '';
                                    }

                                @endphp
                                <div class="mb-2  bg-white p-4">
                                    <h4>Soal {{ $loop->iteration }}</h4>
                                    <p>
                                        {!! $key->soal !!}
                                    </p>
                                    <input type="hidden" name="quizId[]" value="{{ $key->id }}">
                                    <div class="border border-primary p-4">
                                        <textarea name="jawabanUser[]" class="form-control" required cols="30">{{ $userJawaban }}</textarea>
                                        Catatan : {{ $userKoreksi }}
                                    </div>
                                </div>
                            @endforeach

                            <h2>Nilai Anda</h2>
                            <h3>{{ $nilai }}</h3>

                            <button class="btn btn-primary w-100">Save & Submit</button>
                        </form>
                        {{-- Pre Test --}}
                    @elseif (Auth()->User()->roles_id == 3 && $tugas->tipe == 3)
                        @php
                            // dd($tugas->id);
                            $tugasMultiple = App\Models\TugasMultiple::where('tugas_id', $tugas->id)->get();
                            // dd($tugasMultiple);
                            $nilai = 0;
                            $completed = 0;
                            foreach ($tugasMultiple as $key) {
                                $tugasJawabanMultiple = App\Models\TugasJawabanMultiple::where('user_id', Auth()->user()->id)
                                    ->where('tugas_multiple_id', $key['id'])
                                    ->first();
                                if ($tugasJawabanMultiple) {
                                    $nilai += $tugasJawabanMultiple['nilai'];
                                    $completed = 1;
                                } else {
                                    $tugasJawabanMultiple = null;
                                }
                            }
                        @endphp
                        @if ($completed == 1)
                            <div class="text-center">
                                <h2>Nilai anda</h2>
                                <h3 class="text-primary">{{ $nilai }}</h3>
                            </div>
                        @elseif($dueDateTime >= $now)
                            <form action="{{ route('submitTugasMultiple', ['tugasId' => $tugas->id]) }}" method="POST">
                                @csrf
                                @foreach ($tugasMultiple as $key)
                                    {{-- Main Section --}}
                                    <div class="row">
                                        {{-- Question Section --}}
                                        <div class="col-lg-12 col-12 mb-2">
                                            <div class="bg-white p-4 rounded-2 row">
                                                {{-- Soal --}}
                                                <div class="border border-primary rounded-2 p-4 mb-2 col-12"
                                                    id="soal-container">
                                                    <h1 class="text-primary fw-bold" id="soal-title">Soal
                                                        {{ $loop->iteration }}
                                                    </h1>
                                                    <hr>
                                                    <p>{!! $key->soal !!}</p>
                                                </div>

                                                {{-- Jawaban --}}

                                                <div class="rounded-2 mb-2 col-12">
                                                    <div class="rounded-2 mb-2 col-12">
                                                        <h6 class="text-primary fw-bold">Pilihan Jawaban</h6>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" required
                                                                name="jawaban{{ $loop->iteration }}"
                                                                id="pilihan-1{{ $loop->iteration }}" value="1">
                                                            <label class="form-check-label"
                                                                for="pilihan-1{{ $loop->iteration }}">
                                                                1. {{ $key->a }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" required
                                                                name="jawaban{{ $loop->iteration }}"
                                                                id="pilihan-2{{ $loop->iteration }}" value="2">
                                                            <label class="form-check-label"
                                                                for="pilihan-2{{ $loop->iteration }}">
                                                                2. {{ $key->b }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" required
                                                                name="jawaban{{ $loop->iteration }}"
                                                                id="pilihan-3{{ $loop->iteration }}" value="3">
                                                            <label class="form-check-label"
                                                                for="pilihan-3{{ $loop->iteration }}">
                                                                3. {{ $key->c }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check" id="soal-4{{ $loop->iteration }}">
                                                            <input class="form-check-input" type="radio" required
                                                                name="jawaban{{ $loop->iteration }}"
                                                                id="pilihan-4{{ $loop->iteration }}" value="4">
                                                            <label class="form-check-label"
                                                                for="pilihan-4{{ $loop->iteration }}">
                                                                4. {{ $key->d }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check" id="soal-5{{ $loop->iteration }}">
                                                            <input class="form-check-input" type="radio" required
                                                                name="jawaban{{ $loop->iteration }}"
                                                                id="pilihan-5{{ $loop->iteration }}" value="5">
                                                            <label class="form-check-label"
                                                                for="pilihan-5{{ $loop->iteration }}">
                                                                5. {{ $key->e }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary w-100">Save & Submit</button>
                            </form>
                        @else
                            <div class="text-center">
                                <h4 class="mb-0">Nilai anda </h4>
                                <h5 class="text-primary">{{ $nilai }}</h5>
                                <br><small>(anda belum mengerjakan)</small>
                            </div>
                        @endif
                    @elseif (Auth()->User()->roles_id == 3 && $tugas->tipe == 4)
                        @php
                            $anggota = App\Models\AnggotaTugasKelompok::where('user_id', Auth()->user()->id)
                                ->where('tugas_id', $tugas->id)
                                ->first();
                            if ($anggota) {
                                $tugasKelompok = App\Models\TugasKelompok::where('tugas_id', $tugas->id)
                                    ->where('id', $anggota['tugas_kelompok_id'])
                                    ->first();
                                $allAnggota = App\Models\AnggotaTugasKelompok::where('tugas_kelompok_id', $tugasKelompok['id'])->get();
                                if ($anggota['isKetua'] == 1) {
                                    $authStatus = 1;
                                } else {
                                    $authStatus = 0;
                                }
                            } else {
                            }
                        @endphp
                        <div class="col-12 mb-2">
                            <div class="p-4 bg-white rounded-4">
                                <div class="h-100 p-2">
                                    <h4 class="fw-bold mb-2">Kelompok : {{ $tugasKelompok['name'] }}</h4>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Posisi</th>
                                            </thead>
                                            <tbody>

                                                @if (isset($allAnggota))
                                                    @foreach ($allAnggota as $key)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            @php
                                                                $username = App\Models\User::where('id', $key->user_id)->first();
                                                            @endphp
                                                            <td>{{ $username['name'] }}</td>
                                                            <td>
                                                                @if ($key->isKetua == 1)
                                                                    <span class="badge badge-primary p-1">Ketua</span>
                                                                @else
                                                                    <span class="badge badge-primary p-1">Anggota</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    anda belum masuk kelompok...
                                                @endif


                                            </tbody>
                                        </table>
                                    </div>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if ($authStatus == 1)
                            <h3 class="fw-bold text-primary">Submit Tugas
                                @if ($userTugas)
                                    @if ($userTugas->status == 'Belum Mengerjakan')
                                        <span class="badge badge-danger p-2">{{ $userTugas->status }}</span>
                                    @elseif($userTugas->status == 'Telah dinilai')
                                        <span class="badge badge-primary p-2">{{ $userTugas->status }}</span>
                                        <span class="badge badge-success p-2">{{ $userTugas->nilai }}</span>
                                    @else
                                        <span class="badge badge-primary p-2">{{ $userTugas->status }}</span>
                                    @endif
                                @else
                                    <span class="badge badge-danger p-2">Belum Mengerjakan</span>
                                @endif
                            </h3>
                            @php

                            @endphp
                            @if ($tugasKelompok->fileKelompok)
                                {{-- Tampilan File --}}
                                <div class="col-12 mb-2">
                                    <div class="p-2 bg-white rounded-4">
                                        <div class="h-100 p-2">
                                            <h4 class="fw-bold mb-2">Pekerjaan anda</h4>
                                            <hr>
                                            @if (count($tugasKelompok->fileKelompok) > 0)
                                                <ul class="list-group">
                                                    <div class="row">
                                                        @foreach ($tugasKelompok->fileKelompok as $key)
                                                            <div class="col-lg-4 col-sm-6 col-12 mb-2">

                                                                <div class="list-group-item">
                                                                    @if (Str::endsWith($key->file, ['.jpg', '.jpeg', '.png', '.gif']))
                                                                        <i class="fa-solid fa-image"></i>
                                                                    @elseif (Str::endsWith($key->file, ['.mp4', '.avi', '.mov']))
                                                                        <i class="fa-solid fa-video"></i>
                                                                    @elseif (Str::endsWith($key->file, ['.pdf']))
                                                                        <i class="fa-solid fa-file-pdf"></i>
                                                                    @elseif (Str::endsWith($key->file, ['.doc', '.docx']))
                                                                        <i class="fa-solid fa-file-word"></i>
                                                                    @elseif (Str::endsWith($key->file, ['.ppt', '.pptx']))
                                                                        <i class="fa-solid fa-file-powerpoint"></i>
                                                                    @elseif (Str::endsWith($key->file, ['.xls', '.xlsx']))
                                                                        <i class="fa-solid fa-file-excel"></i>
                                                                    @elseif (Str::endsWith($key->file, ['.txt']))
                                                                        <i class="fa-solid fa-file-alt"></i>
                                                                    @elseif (Str::endsWith($key->file, ['.mp3']))
                                                                        <i class="fa-solid fa-music"></i>
                                                                    @else
                                                                        <i class="fa-solid fa-file"></i>
                                                                    @endif
                                                                    <a href="{{ route('getFileUser', ['namaFile' => $key->file]) }}"
                                                                        class="text-decoration-none">
                                                                        {{ Str::substr($key->file, 5, 10) }}
                                                                    </a>
                                                                    @if ($dueDateTime > $now)
                                                                        @if ($userTugas)
                                                                            @if ($userTugas->status != 'Telah dinilai')
                                                                                <button type="button"
                                                                                    class="btn btn-danger btn-sm float-end"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#modalDeleteKelompokFile"
                                                                                    onclick="changeValueFile('{{ $key->file }}')">
                                                                                    X
                                                                                </button>
                                                                            @endif
                                                                        @else
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm float-end"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modalDeleteKelompokFile"
                                                                                onclick="changeValueFile('{{ $key->file }}')">
                                                                                X
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="small">(anda belum mengupload file)</span>
                                            @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($dueDateTime > $now)
                                @if ($userTugas)
                                    @if ($userTugas->status != 'Telah dinilai')
                                        <form
                                            action=" {{ route('submitFileKelompok', ['tugasId' => $tugas['id'], 'kelompokId' => $tugasKelompok]) }}"
                                            method="post" enctype="multipart/form-data" id="submitTugas">
                                            @csrf
                                            {{-- Konten Materi --}}
                                            <div class="mb-2">
                                                <label for="uploadFile" class="form-label">Upload</label>
                                                <!-- Dropzone -->
                                                <input type="file" class="form-control" name="file">
                                            </div>
                                            {{-- Tombol Submit --}}
                                            <div class="">
                                                <button type="submit" class="btn-lg btn btn-primary w-100"
                                                    id="btnSimpan">Simpan
                                                    dan
                                                    Lanjutkan</button>
                                            </div>
                                        </form>
                                    @else
                                        {{-- Telat --}}
                                        <div class="mb-2 text-center bg-white p-4">
                                            <div class="border border-primary p-4">
                                                <span class="fw-bold text-danger">Upload ditutup</span>
                                            </div>

                                        </div>
                                    @endif
                                @else
                                    <form
                                        action= "{{ route('submitFileKelompok', ['tugasId' => $tugas['id'], 'kelompokId' => $tugasKelompok]) }}"
                                        method="post" enctype="multipart/form-data" id="submitTugas">
                                        @csrf
                                        {{-- Konten Materi --}}
                                        <div class="mb-2">
                                            <label for="uploadFile" class="form-label">Upload</label>
                                            <!-- Dropzone -->
                                            <input type="file" class="form-control" name="file">
                                        </div>
                                        {{-- Tombol Submit --}}
                                        <div class="">
                                            <button type="submit" class="btn-lg btn btn-primary w-100"
                                                id="btnSimpan">Simpan
                                                dan
                                                Lanjutkan</button>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        @else
                            Hanya ketua kelompok yang dapat mengumpulkan file...
                        @endif
                    @elseif (Auth()->User()->roles_id == 3 && $tugas->tipe == 5)
                        @php
                            $anggota = App\Models\AnggotaTugasKelompok::where('user_id', Auth()->user()->id)
                                ->where('tugas_id', $tugas->id)
                                ->first();
                            if ($anggota) {
                                // dd('here');
                                $tugasKelompok = App\Models\TugasKelompok::where('tugas_id', $tugas->id)
                                    ->where('id', $anggota['tugas_kelompok_id'])
                                    ->first();
                                $allAnggota = App\Models\AnggotaTugasKelompok::where('tugas_kelompok_id', $tugasKelompok['id'])->get();

                                if ($anggota['isKetua'] == 1) {
                                    $authStatus = 1;
                                } else {
                                    $authStatus = 0;
                                }
                                $tugasMultiple = App\Models\TugasKelompokQuiz::where('tugas_id', $tugas->id)->get();
                                // dd($tugasMultiple);
                                $nilai = 0;
                                $completed = 0;
                                foreach ($tugasMultiple as $key) {
                                    $tugasJawabanMultiple = App\Models\TugasKelompokQuizJawaban::where('tugas_kelompok_id', $tugasKelompok['id'])
                                        ->where('tugas_kelompok_quiz_id', $key['id'])
                                        ->first();
                                    if ($tugasJawabanMultiple) {
                                        $nilai += $tugasJawabanMultiple['nilai'];
                                        $completed = 1;
                                    } else {
                                        $tugasJawabanMultiple = null;
                                    }
                                }
                            } else {
                            }
                        @endphp
                        @if ($completed == 1)
                            <div class="text-center">
                                <h4>Nilai anda</h4>
                                <h5 class="text-primary">{{ $nilai }}</h5>
                            </div>
                        @elseif($dueDateTime >= $now)
                            @if ($authStatus == 1)
                                <form action="{{ route('submitTugasKelompokQuiz', ['tugasId' => $tugas->id]) }}"
                                    method="POST">
                                    @csrf
                                    @foreach ($tugasMultiple as $key)
                                        {{-- Main Section --}}
                                        <div class="row">
                                            {{-- Question Section --}}
                                            <div class="col-lg-15 col-15 mb-2">
                                                <div class="bg-white p-4 rounded-2 row">
                                                    {{-- Soal --}}
                                                    <div class="border border-primary rounded-2 p-2 mb-2 col-15"
                                                        id="soal-container">
                                                        <h4 class="text-primary fw-bold" id="soal-title">Soal
                                                            {{ $loop->iteration }}
                                                        </h4>
                                                        <hr>
                                                        <p>{!! $key->soal !!}</p>
                                                    </div>
                                                    {{-- Jawaban --}}
                                                    <div class="rounded-2 mb-2 col-15">
                                                        <div class="rounded-2 mb-2 col-15">
                                                            <h6 class="text-primary fw-bold">Pilihan Jawaban</h6>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" required
                                                                    name="jawaban{{ $loop->iteration }}" checked
                                                                    id="pilihan-a{{ $loop->iteration }}" value="ya">
                                                                <label class="form-check-label"
                                                                    for="pilihan-a{{ $loop->iteration }}">
                                                                    Ya
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" required
                                                                    name="jawaban{{ $loop->iteration }}"
                                                                    id="pilihan-b{{ $loop->iteration }}" value="tidak">
                                                                <label class="form-check-label"
                                                                    for="pilihan-b{{ $loop->iteration }}">
                                                                    Tidak
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <button type="submit" class="btn btn-primary w-100">Save & Submit</button>
                                </form>
                            @else
                                hanya ketua kelompok yang bisa
                            @endif
                        @else
                            <div class="text-center">
                                <h4 class="mb-0">Nilai anda </h4>
                                <h5 class="text-primary">{{ $nilai }}</h5>
                                <br><small>(anda belum mengerjakan)</small>
                            </div>
                        @endif
                        <div class="col-15 mb-2">
                            <div class="p-2 bg-white rounded-2">
                                <div class="h-100 p-2">
                                    <h5 class="fw-bold mb-2">Kelompok : {{ $tugasKelompok['name'] }}</h5>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Posisi</th>
                                            </thead>
                                            <tbody>

                                                @if (isset($allAnggota))
                                                    @foreach ($allAnggota as $key)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            @php
                                                                $username = App\Models\User::where('id', $key->user_id)->first();
                                                            @endphp
                                                            <td>{{ $username['name'] }}</td>
                                                            <td>
                                                                @if ($key->isKetua == 1)
                                                                    <span class="badge badge-primary p-1">Ketua</span>
                                                                @else
                                                                    <span class="badge badge-primary p-1">Anggota</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    anda belum masuk kelompok...
                                                @endif


                                            </tbody>
                                        </table>
                                    </div>

                                    </ul>
                                </div>
                            </div>
                        </div>

                    @endif
                </div>
            </div>

            {{-- Bagian Kanan --}}
            <div class="col-xl-3 col-lg-12 col-md-12">
                {{-- Info Pengajar --}}
                <div class="mb-4 p-4 bg-white rounded-2 shadow-sm">
                    <div class="h-100 p-2">
                        <h4 class="fw-bold mb-3 text-primary">
                            <i class="fa-solid fa-chalkboard-teacher me-2"></i>Pengajar
                        </h4>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-lg-4 d-none d-lg-none d-xl-block">
                                @if ($editor->gambar == null)
                                    <img src="/asset/icons/profile-women.svg" class="rounded-circle img-fluid shadow-sm"
                                        alt="Profile" style="width: 60px; height: 60px;">
                                @else
                                    <img src="{{ asset('storage/user-images/' . $editor->gambar) }}" alt="Profile"
                                        class="rounded-circle img-fluid shadow-sm" style="width: 60px; height: 60px;">
                                @endif
                            </div>
                            <div class="col-lg-8">
                                <a href="{{ route('viewProfilePengajar', ['token' => encrypt($editor['id'])]) }}" 
                                   class="text-decoration-none">
                                    <h6 class="fw-bold text-dark mb-1">{{ $editor['name'] }}</h6>
                                    <small class="text-muted">Pengajar</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Tugas --}}
                <div class="mb-4 p-4 bg-white rounded-2 shadow-sm">
                    <div class="h-100 p-2">
                        <h4 class="fw-bold mb-3 text-primary">
                            <i class="fa-solid fa-list me-2"></i>Daftar Tugas
                        </h4>
                        <hr>
                        <div class="list-group list-group-flush">
                            @foreach ($tugasAll as $key)
                                @if ($key->isHidden != 1 || Auth()->User()->roles_id == 2)
                                    @if ($tugas['id'] != $key->id)
                                        <a href="{{ route('viewTugas', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($tugas['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}"
                                           class="list-group-item list-group-item-action border-0 py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-clipboard-list text-primary me-3"></i>
                                                <div>
                                                    <h6 class="mb-1 text-dark">{{ $key->name }}</h6>
                                                    <small class="text-muted">
                                                        @if ($key->tipe == 1) Self Assessment
                                                        @elseif($key->tipe == 2) Quiz
                                                        @elseif($key->tipe == 3) Pre Test
                                                        @elseif($key->tipe == 4) Peer Assessment
                                                        @elseif($key->tipe == 5) Kelompok
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="list-group-item border-0 py-3 bg-primary text-white rounded">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-clipboard-check me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">{{ $key->name }}</h6>
                                                    <small>Sedang dilihat</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah Kelompok --}}
    <div class="modal fade" id="tambah-kelompok" tabindex="-1" aria-labelledby="modalDelete" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Tambah Kelompok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tambahKelompok') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        Nama Kelompok
                        <input type="text" name="nama" class="form-control">
                        {{-- <input type="hidden" name="tugas_id" value="{{ $tugas[] }}" class="form-control"> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="idTugas" id="tugas_id" value="{{ $tugas['id'] }}">
                        <input type="hidden" name="fileName" id="fileName" value="">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDelete" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus File ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('deleteKelompok') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        {{-- <input type="hidden" name="idTugas" id="idTugas" value="{{ $tugas['id'] }}"> --}}
                        <input type="hidden" name="fileName" id="fileName" value="">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDeleteKelompokFile" tabindex="-1" aria-labelledby="modalDelete"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus File ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('deleteKelompokFile') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        {{-- <input type="hidden" name="idTugas" id="idTugas" value="{{ $tugas['id'] }}"> --}}
                        <input type="hidden" name="fileName" id="fileName2" value="">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalDeleteKelompok" tabindex="-1" aria-labelledby="modalDelete" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kelompok ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('deleteKelompok') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="id" id="idKelompok" value="">
                        {{-- <input type="hidden" name="fileName" id="fileName" value=""> --}}
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk mengatur gambar agar responsif --}}
    <script>
        var img = document.querySelectorAll('img');

        img.forEach(function(element) {
            element.classList.add('img-fluid');
        });

        function changeValue(itemId) {
            console.log(itemId);
            const fileName = document.getElementById('fileName');
            fileName.setAttribute('value', itemId);
        }

        function changeValueFile(itemId) {
            console.log(itemId);
            const fileName2 = document.getElementById('fileName2');
            fileName2.setAttribute('value', itemId);
        }
    </script>

    {{-- Script tambahan jika diperlukan --}}
    <script src="{{ url('/asset/js/lottie.js') }}"></script>
    <script src="{{ url('/asset/js/customJS/simpleAnim.js') }}"></script>
    @if (Auth()->User()->roles_id == 3 && $tugas->tipe == 1)
        <script>
            $(document).ready(function() {


                // Menangkap submit form
                $('#submitTugas').submit(function(e) {
                    e.preventDefault(); // Mencegah form melakukan submit default

                    // Mengambil data form
                    var formData = new FormData(this);

                    // Menggunakan AJAX untuk mengirim data ke server
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Berhasil, lakukan sesuatu dengan respons dari server jika diperlukan
                            console.log(response);
                            uploadFiles();
                        },
                        error: function(error) {
                            // Terjadi kesalahan, tangani kesalahan jika diperlukan
                            console.log(error);
                            // Di sini Anda dapat menambahkan logika lain atau menampilkan pesan kesalahan kepada pengguna.
                        }
                    });
                });
            });
        </script>
    @endif

    @if ($tugas->tipe == 1 || $tugas->tipe == 4)
        <script>
            // Inisialisasi Dropzone
            Dropzone.autoDiscover = false; // Untuk menghindari Dropzone menginisialisasi dirinya sendiri secara otomatis

            var totalFilesToUpload = 0; // Total file yang diharapkan diunggah
            var completedFiles = 0; // Jumlah file yang sudah selesai diunggah

            // Konfigurasi Dropzone

            var url =
                "{{ route('submitFileTugas', ['tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}"



            var myDropzone = new Dropzone("#my-dropzone", {
                url: url, // Ganti dengan rute yang sesuai untuk menangani unggahan file
                paramName: "file", // Nama parameter untuk mengirim file
                maxFilesize: 10, // Batasan ukuran file (dalam MB)
                acceptedFiles: ".jpg, .jpeg, .png, .gif, .mp4, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx, .txt, .mp3, .avi, .mov",
                addRemoveLinks: true, // Menampilkan tautan untuk menghapus file yang diunggah
                timeout: 60000, // Menampilkan tautan untuk menghapus file yang diunggah
                dictDefaultMessage: "Seret file ke sini atau klik untuk mengunggah", // Pesan default
                autoProcessQueue: false,

                parallelUploads: 100,
                init: function() {
                    this.on("addedfile", function(file) {
                        if (file.size <=
                            10485760
                        ) { // Misalnya, hanya mengunggah file yang berukuran kurang dari atau sama dengan 10MB
                            totalFilesToUpload++; // Menambah total file yang diharapkan saat file ditambahkan
                        } else {
                            // Jika file tidak memenuhi ketentuan, Anda dapat memberikan pesan kesalahan kepada pengguna atau tindakan lain yang sesuai.
                            this.removeFile(file);
                            alert("File terlalu besar! Maksimal ukuran file adalah 10MB.");
                        }
                    });

                    this.on("sending", function(file, xhr, formData) {
                        // Tambahkan token CSRF ke dalam header permintaan
                        xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
                    });

                    this.on("success", function(file, response) {
                        console.log(response);
                        console.log("completedFiles : " + completedFiles);
                        console.log("totalFilesToUpload : " + totalFilesToUpload);
                    });

                    this.on("complete", function(file, response) {
                        if (file.size <=
                            10485760) { // Hanya menambahkan file yang memenuhi ketentuan ke completedFiles
                            completedFiles++; // Menambah jumlah file yang selesai diunggah
                            console.log("completedFiles : " + completedFiles);
                            console.log("totalFilesToUpload : " + totalFilesToUpload);
                            if (completedFiles === totalFilesToUpload) {
                                // Semua file yang memenuhi ketentuan sudah selesai diunggah, lakukan pengalihan
                                window.location.href =
                                    "{{ route('redirectBack', ['kelasId' => $kelas['id'], 'mapelId' => $mapel['id'], 'message' => 'Tambah']) }}";
                            }
                        }
                    });

                    this.on("removedfile", function(file) {

                        totalFilesToUpload--;

                        // Pastikan completedFiles tidak kurang dari 0
                        if (completedFiles < 0) {
                            completedFiles = 0;
                        }
                    });

                    // Tambahkan event lain yang Anda perlukan di sini
                }
            });



            function uploadFiles() {
                if (myDropzone.getQueuedFiles().length === 0) {
                    // Tidak ada file yang diunggah, lakukan pengalihan (redirect)
                    window.location.href =
                        "{{ route('redirectBack', ['kelasId' => $kelas['id'], 'mapelId' => $mapel['id'], 'message' => 'Tambah']) }}";
                } else {
                    // Ada file yang diunggah, proses antrian Dropzone
                    myDropzone.processQueue();
                }
            }

            // Event listener untuk tombol delete kelompok
            document.addEventListener('click', function(e) {
                if (e.target.matches('[data-kelompok-id]')) {
                    const kelompokId = e.target.getAttribute('data-kelompok-id');
                    document.getElementById('idKelompok').value = kelompokId;
                }
            });

            function changeValueKelompok(id) {
                console.log(id);
                $('#idKelompok').val(id);
            }

            // Quick Comments Functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Quick comment buttons
                document.querySelectorAll('.quick-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        const comment = this.getAttribute('data-comment');
                        const textarea = this.closest('.feedback-container').querySelector('.feedback-textarea');
                        textarea.value = comment;
                        textarea.focus();
                    });
                });

                // Auto-save functionality
                let autoSaveTimeout;
                document.querySelectorAll('.feedback-textarea').forEach(textarea => {
                    textarea.addEventListener('input', function() {
                        clearTimeout(autoSaveTimeout);
                        autoSaveTimeout = setTimeout(() => {
                            // Auto-save logic here
                            console.log('Auto-saving feedback...');
                        }, 2000);
                    });
                });
            });
        </script>

        <style>
            .feedback-container {
                min-width: 300px;
            }
            
            .feedback-textarea {
                resize: vertical;
                min-height: 60px;
                border: 2px solid #e9ecef;
                transition: border-color 0.3s ease;
            }
            
            .feedback-textarea:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            }
            
            .quick-comments {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }
            
            .quick-comment {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                transition: all 0.3s ease;
            }
            
            .quick-comment:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .table th {
                background-color: #f8f9fa;
                border-top: none;
                font-weight: 600;
                color: #495057;
            }
            
            .table td {
                vertical-align: middle;
                border-color: #e9ecef;
            }
            
            .table-hover tbody tr:hover {
                background-color: rgba(13, 110, 253, 0.05);
            }
            
            .progress-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
            }
            
            .stat-card {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                padding: 1rem;
                text-align: center;
                transition: transform 0.3s ease;
            }
            
            .stat-card:hover {
                transform: translateY(-2px);
            }
            
            .accordion-button:not(.collapsed) {
                background-color: #0d6efd;
                color: white;
            }
            
            .accordion-button:focus {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }
            
            .card {
                border: none;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                transition: box-shadow 0.3s ease;
            }
            
            .card:hover {
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                transition: all 0.3s ease;
            }
            
            .btn-primary:hover {
                background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            }
            
            @media (max-width: 768px) {
                .feedback-container {
                    min-width: 200px;
                }
                
                .quick-comments {
                    flex-direction: column;
                }
                
                .quick-comment {
                    width: 100%;
                    margin-bottom: 0.5rem;
                }
                
                .stat-card {
                    margin-bottom: 1rem;
                }
            }
        </style>
    @endif
@endsection
