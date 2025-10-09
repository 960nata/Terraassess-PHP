@extends('layout.template.mainTemplate')

@section('container')
{{-- Cek peran pengguna --}}
@if (Auth()->user()->roles_id == 1)
@include('menu.admin.adminHelper')
@endif

{{-- Navigasi Breadcrumb --}}
<div class="col-12 ps-4 pe-4 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapel['id']]) }}">
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
<div class="ps-4 pe-4 mt-4  pt-4">
    <h2 class="display-6 fw-bold">
        <a href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapel['id']]) }}">
            <button type="button" class="btn btn-outline-secondary rounded-circle">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
        </a> Tugas
    </h2>
</div>



{{-- Baris utama --}}
<div class="col-12 ps-4 pe-4 mb-4">
    <div class="row">
        {{-- Bagian Kiri --}}
        <div class="col-xl-9 col-lg-12 col-md-12">
            <div class="row">

                {{-- Informasi Tugas --}}
                <div class="mb-4 p-4 bg-white rounded-4">
                    <div class=" p-4">
                        <h4 class="fw-bold mb-2">Informasi
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
                            @if ($dueDateTime < $now) <div class="border p-3 fw-bold col-lg-3 col-12">
                                Status : <span class="badge badge-danger p-2">Ditutup</span>
                        </div>
                        <div class="border p-3 fw-bold col-lg-3 col-12">
                            Waktu : <span class="badge badge-danger p-2">
                                -
                            </span>
                        </div>
                        @else
                        @if ($daysUntilDue >= 0 || ($daysUntilDue == 0 && $hoursUntilDue >= 0 && $minutesUntilDue >= 0))
                        <div class="border p-3 fw-bold col-lg-3 col-12">
                            Status : <span class="badge badge-primary p-2">Dibuka</span>
                        </div>
                        <div class="border p-3 fw-bold col-lg-3 col-12">
                            Waktu : <span class="badge badge-primary p-2">
                                {{ $daysUntilDue }} hari, {{ $hoursUntilDue }} jam, {{ $minutesUntilDue }}
                                menit lagi
                            </span>
                        </div>
                        @endif
                        @endif
                        <div class="col-12 border p-3 col-lg-3">
                            <span class="fw-bold">Deadline :</span>
                            {{ $localTime->formatLocalized('%d %B %Y %H:%M') }}
                        </div>
                        <div class="col-12 border p-3 col-lg-3">
                            <span class="fw-bold">Tipe :</span>
                            @if ($tugas->tipe == 1)
                            <span class="badge badge-primary p-2">Self Assesment</span>
                            @elseif($tugas->tipe == 2)
                            <span class="badge badge-primary p-2">Quiz</span>
                            @elseif($tugas->tipe == 3)
                            <span class="badge badge-primary p-2">Pre Test</span>
                            @else
                            <span class="badge badge-primary p-2">Peer Assesment</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- Assesment Kelompok Nilai --}}
            @if ($tugas->tipe == 4 && Auth()->User()->roles_id == 3)
            <div class="col-12 mb-4">
                <div class="p-4 bg-white rounded-4">
                    <div class="h-100 p-4 border border-primary text-center">
                        <div class="w-100 h-50 text-center rounded-2 bg-primary text-white p-4">

                            <h2 class="fw-bold">Nilai Anda</h2>
                            <h3 class="fw-bold">99</h3>
                        </div>
                        <div class="text-center mt-2">
                            <h2 class="fw-bold text-primary ">Daftar Kelompok :</h2>
                        </div>
                        @php
                        $kelompok = App\models\TugasKelompok::where('tugas_id', $tugas->id)->get();
                        @endphp
                        @if ($kelompok)
                        @if (count($kelompok) > 0)
                        <div class="row mt-2">
                            @foreach ($kelompok as $key)
                            <div class="p-4 text-center rounded-2 border border-primary m-3 col-md-3 col-6">
                                <h4> {{ $key->name }}</h4>
                                <h5>
                                    <span class="badge badge-danger">Belum Mengisi Nilai</span>
                                </h5>
                                <h6 class="border border-primary p-2 text-primary">sdsdds.jpg</h6>
                                <form action="">
                                    <div class="row">
                                        <div class="col-8">
                                            <input type="number" class="form-control" placeholder="Nilai">
                                        </div>
                                        <div class="col-4">
                                            <button class="btn btn-primary">Save</button>
                                        </div>
                                    </div>


                                </form>
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
            <div class="col-12 mb-4">
                <div class="p-4 bg-white rounded-4">
                    <div class="h-100 p-4">
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

                <div class="col-lg-12 col-12 bg-white rounded-2 mb-4 p-4 ">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" border border border-primary shadow-sm  p-4 mt-4 pertanyaan">
                                <div class="">
                                    <h3>Soal <span class="badge badge-primary">{{ $loop->iteration }}</span>
                                    </h3>
                                    <div class="mb-3">
                                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">Pertanyaan</label>
                                        <div class="border border-secondary p-4 rounded-2" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" disabled>{!! $key->soal !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="accordion mb-4 mt-4" id="ujian{{ $loop->iteration }}">
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-outline-danger  collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#ujian{{ $loop->iteration }}-collapseOne" aria-controls="ujian{{ $loop->iteration }}-collapseOne">
                                            Submittion Siswa
                                        </button>
                                    </h2>
                                    <div id="ujian{{ $loop->iteration }}-collapseOne" class="accordion-collapse collapse">
                                        <div class="accordion-body table-responsive" style="max-height: 300px; overflow-y: auto;">
                                            <table id="table" class="table table-striped table-hover table-lg ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Nama</th>
                                                        <th scope="col">Submittion</th>
                                                        <th scope="col">Nilai</th>
                                                        <th scope="col">Input Nilai</th>
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
                                                    } else {
                                                    $nilai = null;
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
                                                        <input type="hidden" name="siswaId[]" value="{{ $key2->id }}">
                                                        <input type="hidden" name="quizId[]" value="{{ $key->id }}">
                                                        @if ($tugas->tipe == 2)
                                                        <td class="w-25">
                                                            <input type="number" class="form-control w-100" placeholder="-" aria-label="nilai" name="nilai[]" value="{{ $nilai !== null ? $nilai : '' }}">
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
                <div class="col-lg-12">
                    <div class="accordion mb-4 mt-4">
                        <div class="accordion-item ">
                            <h2 class="accordion-header">
                                <button class="accordion-button bg-outline-danger  collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#total-collapseOne" aria-controls="total-collapseOne">
                                    Nilai Total
                                </button>
                            </h2>
                            <div id="total-collapseOne" class="accordion-collapse collapse">
                                <div class="accordion-body table-responsive" style="max-height: 300px; overflow-y: auto;">
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
                                            $tugasQuiz = App\models\TugasQuiz::where('tugas_id', $tugas->id)->pluck('id');

                                            // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan
                                            // $userTugas = $key2->TugasJawabanMultiple
                                            // ->wherein('tugas_quiz_id', $tugasQuiz)
                                            // ->where('user_id', $key2->id)
                                            // ->get();
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
                @foreach ($tugas->TugasMultiple as $key)
                <input type="text" name="token" value="{{ encrypt($key->id) }}">
                <div class="col-lg-12 col-12 bg-white rounded-2 mb-4 p-4 ">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class=" border border border-primary shadow-sm  p-4 mt-4 pertanyaan">
                                        <div class="">
                                            <h3>Soal <span class="badge badge-primary">{{ $loop->iteration }}</span>
                                            </h3>
                                            <div class="mb-3">
                                                <label for="pertanyaan${nomorPertanyaan}" class="form-label">Pertanyaan</label>
                                                <div class="border border-secondary p-4 rounded-2" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" disabled>
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
                                            <input type="text" class="form-control @if ($key->jawaban == 'a') text-white fw-bold bg-success @endif" disabled value="{{ $key->a }}">
                                        </div>
                                        <div class="col-6 mb-1">
                                            <label class="form-label">B
                                            </label>
                                            <input type="text" class="form-control @if ($key->jawaban == 'b') text-white fw-bold bg-success @endif" disabled value="{{ $key->b }}">
                                        </div>
                                        <div class="col-6 mb-1">
                                            <label class="form-label">C
                                            </label>
                                            <input type="text" class="form-control @if ($key->jawaban == 'c') text-white fw-bold bg-success @endif" disabled value="{{ $key->c }}">
                                        </div>
                                        @if ($key->d)
                                        <div class="col-6 mb-1">
                                            <label class="form-label">D
                                            </label>
                                            <input type="text" class="form-control @if ($key->jawaban == 'd') text-white fw-bold bg-success @endif" disabled value="{{ $key->d }}">
                                        </div>
                                        @endif
                                        @if ($key->e)
                                        <div class="col-6 mb-1">
                                            <label class="form-label">E
                                            </label>
                                            <input type="text" class="form-control @if ($key->jawaban == 'e') text-white fw-bold bg-success @endif" disabled value="{{ $key->e }}">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="accordion mb-4 mt-4" id="ujian{{ $loop->iteration }}">
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-outline-danger  collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#ujian{{ $loop->iteration }}-collapseOne" aria-controls="ujian{{ $loop->iteration }}-collapseOne">
                                            Submittion Siswa
                                        </button>
                                    </h2>

                                    <div id="ujian{{ $loop->iteration }}-collapseOne" class="accordion-collapse collapse">
                                        <div class="accordion-body table-responsive" style="max-height: 300px; overflow-y: auto;">
                                            <table id="table" class="table table-striped table-hover table-lg ">
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
                                                    ->where('id', $key->id)
                                                    ->first();
                                                    $nilai = $userTugas && is_numeric($userTugas->nilai) ? intval($userTugas->nilai) : null;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key2->name }}</td>
                                                        <td>
                                                            @if ($submition)
                                                            {{ $submition['user_jawaban'] }}.
                                                            @if ($submition['user_jawaban'] == 'A')
                                                            {{ $key->a }}
                                                            @elseif($submition['user_jawaban'] == 'B')
                                                            {{ $key->b }}
                                                            @elseif($submition['user_jawaban'] == 'C')
                                                            {{ $key->c }}
                                                            @elseif($submition['user_jawaban'] == 'D')
                                                            {{ $key->d }}
                                                            @elseif($submition['user_jawaban'] == 'E')
                                                            {{ $key->e }}
                                                            @endif
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
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
                    </div>
                </div>
                @endforeach

                <button class="btn btn-primary btn-lg w-100" type="submit">Simpan</button>


            </form>
            @endif

            @if (Auth()->User()->roles_id == 2 && $tugas->tipe == 1)
            <form action="{{ route('siswaUpdateNilai', ['token' => encrypt($tugas['id'])]) }}" method="post">
                @csrf
                {{-- Siswa dan Assignment --}}
                <div class="accordion mb-4" id="sdsd">
                    <div class="accordion-item ">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-outline-primary  fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sdsd-collapseOne" aria-controls="sdsd-collapseOne">
                                Submittion Siswa
                            </button>
                        </h2>
                        <div id="sdsd-collapseOne" class="accordion-collapse collapse show">
                            <div class="accordion-body table-responsive">
                                <table id="table" class="table table-striped table-hover table-lg ">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Submittion</th>
                                            <th scope="col">Nilai</th>
                                            <th scope="col">Input Nilai</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($kelas->User as $key)
                                        @php
                                        // Mencari UserTugas sesuai dengan ID tugas yang Anda inginkan
                                        $userTugas = $key->UserTugas->where('tugas_id', $tugas['id'])->first();
                                        $nilai = $userTugas && is_numeric($userTugas->nilai) ? intval($userTugas->nilai) : null;
                                        @endphp

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $key->name }}</td>
                                            <td>
                                                @if ($userTugas)
                                                @if ($userTugas->UserTugasFile)
                                                @foreach ($userTugas->UserTugasFile as $file)
                                                <a class="d-block" href="{{ route('getFileUser', ['namaFile' => $file->file]) }}">{{ $file->file }}</a>
                                                @endforeach
                                                @endif
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
                                            <input type="hidden" name="siswaId[]" value="{{ $key->id }}">
                                            <td class="w-25">
                                                <input type="number" class="form-control w-100" placeholder="-" aria-label="nilai" name="nilai[]" value="{{ $nilai !== null ? $nilai : '' }}">
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary w-100" type="submit">submit</button>
            </form>
            @elseif ($tugas->tipe == 4 && Auth()->User()->roles_id == 2)
            <div class="col-lg-12 col-12 bg-white rounded-2 mb-4 p-4 ">
                <h4>Data Kelompok <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#tambah-kelompok">Tambah</button></h4>
                @php
                $kelompok = App\models\TugasKelompok::where('tugas_id', $tugas->id)->get();
                @endphp

                @foreach ($kelompok as $key)
                <div class="p-4 border border-dark  rounded-2 m-3">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class=""> {{ $key->name }}</h5> <span class="p-2 badge badge-danger mb-2">Jumlah Anggota
                                :
                                {{ count($key->AnggotaTugasKelompok) }}</span>
                            @php
                            $ketua = App\models\AnggotaTugasKelompok::where('tugas_kelompok_id', $key->id)
                            ->where('isKetua', 1)
                            ->first();
                            if ($ketua) {
                            $ketuaNama = App\models\User::where('id', $ketua['user_id'])->first();
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
                                <div class="col-2  mb-2">
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteKelompok" onclick="changeValueKelompok({{ $key->id }})">X</button>
                                </div>
                                <div class="col-10 mb-2">
                                    <a href="{{ route('settingKelompok', ['tugas' => $tugas->id, 'id_kelompok' => $key->id]) }}" class="btn btn-primary h-100 w-100">Setting</a>
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
            <div class="col-12 mb-4">
                <div class="p-4 bg-white rounded-4">
                    <div class="h-100 p-4">
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
                                        <a href="{{ route('getFileUser', ['namaFile' => $key->file]) }}" class="text-decoration-none">
                                            {{ Str::substr($key->file, 5, 10) }}
                                        </a>
                                        @if ($dueDateTime > $now)
                                        @if ($userTugas)
                                        @if ($userTugas->status != 'Telah dinilai')
                                        <button type="button" class="btn btn-danger btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="changeValue('{{ $key->file }}')">
                                            X
                                        </button>
                                        @endif
                                        @else
                                        <button type="button" class="btn btn-danger btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="changeValue('{{ $key->file }}')">
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
            <form action="{{ route('submitTugas', ['token' => encrypt($kelas['id']), 'tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}" method="post" enctype="multipart/form-data" id="submitTugas">
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
            @else
            {{-- Telat --}}
            <div class="mb-3 text-center bg-white p-4">
                <div class="border border-primary p-4">
                    <span class="fw-bold text-danger">Upload ditutup</span>
                </div>

            </div>
            @endif
            @else
            <form action="{{ route('submitTugas', ['token' => encrypt($kelas['id']), 'tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}" method="post" enctype="multipart/form-data" id="submitTugas">
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
            $tugasQuiz = App\models\TugasQuiz::where('tugas_id', $tugas->id)->get();
            @endphp
            <form action="{{ route('submit-quiz') }}" method="post">
                @csrf

                @foreach ($tugasQuiz as $key)
                @php
                $tugasStatus = App\models\TugasJawabanMultiple::where('tugas_quiz_id', $key->id)
                ->where('user_id', Auth()->user()->id)
                ->first();
                if ($tugasStatus) {
                $userJawaban = $tugasStatus['user_jawaban'];
                // dd($userJawaban);
                } else {
                $userJawaban = null;
                }
                @endphp
                <div class="mb-3  bg-white p-4">
                    <h4>Soal {{ $loop->iteration }}</h4>
                    <p>
                        {!! $key->soal !!}
                    </p>
                    <input type="hidden" name="quizId[]" value="{{ $key->id }}">
                    <div class="border border-primary p-4">
                        <textarea name="jawabanUser[]" class="form-control" required cols="30">{{ $userJawaban }}</textarea>
                    </div>
                </div>
                @endforeach
                <button class="btn btn-primary w-100">Save & Submit</button>
            </form>
            @elseif (Auth()->User()->roles_id == 3 && $tugas->tipe == 4)
            @php

            $anggota = App\models\AnggotaTugasKelompok::where('user_id', Auth()->user()->id)
            ->where('tugas_id', $tugas->id)
            ->first();
            if ($anggota) {
            $tugasKelompok = App\models\TugasKelompok::where('tugas_id', $tugas->id)
            ->where('id', $anggota['tugas_kelompok_id'])
            ->first();
            $allAnggota = App\models\AnggotaTugasKelompok::where('tugas_kelompok_id', $tugasKelompok['id'])->get();
            if ($anggota['isKetua'] == 1) {
            $authStatus = 1;
            } else {
            $authStatus = 0;
            }
            } else {
            }
            @endphp
            <div class="col-12 mb-4">
                <div class="p-4 bg-white rounded-4">
                    <div class="h-100 p-4">
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
                                        $username = App\models\User::where('id', $key->user_id)->first();
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
            @if ($userTugas)
            {{-- Tampilan File --}}
            <div class="col-12 mb-4">
                <div class="p-4 bg-white rounded-4">
                    <div class="h-100 p-4">
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
                                        <a href="{{ route('getFileUser', ['namaFile' => $key->file]) }}" class="text-decoration-none">
                                            {{ Str::substr($key->file, 5, 10) }}
                                        </a>
                                        @if ($dueDateTime > $now)
                                        @if ($userTugas)
                                        @if ($userTugas->status != 'Telah dinilai')
                                        <button type="button" class="btn btn-danger btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="changeValue('{{ $key->file }}')">
                                            X
                                        </button>
                                        @endif
                                        @else
                                        <button type="button" class="btn btn-danger btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="changeValue('{{ $key->file }}')">
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
            <form action="{{ route('submitTugas', ['token' => encrypt($kelas['id']), 'tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}" method="post" enctype="multipart/form-data" id="submitTugas">
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
            @else
            {{-- Telat --}}
            <div class="mb-3 text-center bg-white p-4">
                <div class="border border-primary p-4">
                    <span class="fw-bold text-danger">Upload ditutup</span>
                </div>

            </div>
            @endif
            @else
            <form action="{{ route('submitTugas', ['token' => encrypt($kelas['id']), 'tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}" method="post" enctype="multipart/form-data" id="submitTugas">
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
            @else
            Hanya ketua kelompok yang dapat mengumpulkan file...
            @endif
            @endif
        </div>
    </div>

    {{-- Bagian Kanan --}}
    <div class="col-xl-3 col-lg-12 col-md-12">
        {{-- Info Pengajar --}}
        <div class="mb-4 p-4 bg-white rounded-4">
            <div class="h-100 p-4">
                <h4 class="fw-bold mb-2">Pengajar</h4>
                <hr>
                <div class="row">
                    <div class="col-lg-4 d-none d-lg-none d-xl-block">
                        @if ($editor->gambar == null)
                        <img src="/asset/icons/profile-women.svg" class="rounded-circle  img-fluid" alt="">
                        @else
                        <img src="{{ asset('storage/user-images/' . $editor->gambar) }}" alt="placeholder" class="rounded-circle  img-fluid">
                        @endif
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('viewProfilePengajar', ['token' => encrypt($editor['id'])]) }}">
                            {{ $editor['name'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Tugas --}}
        <div class="mb-4 p-4 bg-white rounded-4">
            <div class="h-100 p-4">
                <h4 class="fw-bold mb-2">List Tugas</h4>
                <hr>
                <ul class="list-group">
                    @foreach ($tugasAll as $key)
                    @if ($key->isHidden != 1 || Auth()->User()->roles_id == 2)
                    @if ($tugas['id'] != $key->id)
                    <a href="{{ route('viewTugas', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($tugas['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}">
                        @endif
                        <li class="list-group-item  @if ($tugas['id'] == $key->id) active @endif">
                            {{ $key->name }}
                        </li>
                        @if ($tugas['id'] != $key->id)
                    </a>
                    @endif
                    @endif
                    @endforeach
                </ul>
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
@if (Auth()->User()->roles_id == 3 && $tugas->tipe == 4)
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

<script>
    // Inisialisasi Dropzone
    Dropzone.autoDiscover = false; // Untuk menghindari Dropzone menginisialisasi dirinya sendiri secara otomatis

    var totalFilesToUpload = 0; // Total file yang diharapkan diunggah
    var completedFiles = 0; // Jumlah file yang sudah selesai diunggah

    // Konfigurasi Dropzone
    @if($tugas - > tipe == 1)
    var url =
        "{{ route('submitFileTugas', ['tugasId' => encrypt($tugas['id']), 'userId' => encrypt(Auth()->User()->id)]) }}"
    @elseif($tugas - > tipe == 4)
    var url =
        "{{ route('submitFileKelompok', ['tugasId' => encrypt($tugas['id']), 'kelompokId' => $tugasKelompok]) }}"
    @endif

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

    function changeValueKelompok(id) {
        console.log(id);
        $('#idKelompok').val(id);
    }
</script>
@endsection