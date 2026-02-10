@extends('layouts.unified-layout')

@section('container')
    {{-- Cek peran pengguna --}}
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    {{-- Navigasi Breadcrumb --}}
    <div class="col-15 ps-1 pe-1 mb-">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelasId), 'mapel_id' => $mapel['id']]) }}">
                        {{ $mapel['name'] }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Tugas</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-1 pe-1 mt-1  pt-1">
        <h5 class="display-6 fw-bold">
            <a
                href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelasId), 'mapel_id' => $mapel['id']]) }}">
                <button type="button" class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a> Tambah Tugas
        </h5>
    </div>

    {{-- Formulir Tambah Tugas --}}
    <div class="">
        <div class="row p-1">
            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Tugas</h4>
            <form action="{{ route('createTugas') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12 col-lg-12 bg-white rounded-2">
                    <div class="mt-4">
                        <div class="p-4">
                            {{-- Status Open / Close --}}
                            <div class="mb-2 row">
                                <div class="col-8 col-lg-4">
                                    <label for="opened" class="form-label d-block">Aktif<span class="small">(apakah
                                            sudah bisa diakses?)</span></label>
                                </div>
                                <div class="col-4 col-lg form-check form-switch">
                                    <input class="form-check-input" name="opened" type="checkbox" role="switch"
                                        id="opened" checked>
                                </div>
                            </div>
                            {{-- Nama Tugas --}}
                            <div class="mb-2">
                                <label for="name" class="form-label">Judul Tugas</label>
                                <input type="hidden" name="kelasId" value="{{ encrypt($kelasId) }}" readonly>
                                <input type="hidden" name="mapelId" value="{{ $mapel['id'] }}" readonly>
                                <input type="hidden" name="tipe" value="{{ $tipe }}" readonly>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Inputkan judul Tugas..." value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- Due Date Picker --}}
                            <div class="mb-2">
                                <label for="due" class="form-label">Due Date</label>
                                <input class="form-control" id="due" name="due" autocomplete="off"
                                    placeholder="Pilih tanggal jatuh tempo..." required value="{{ old('due') }}">
                                @error('due')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="tipe" class="form-label">Tipe Tugas</label>
                                <input class="form-control" id="tipe" autocomplete="off" placeholder="tipeTugas"
                                    disabled
                                    value="@if ($tipe == 1) Assesment Mandiri @elseif ($tipe == 2) Quiz Mandiri @elseif($tipe == 3) Pilihan Ganda Mandiri @else Assesment Kelompok @endif">
                            </div>
                            {{-- Konten Tugas --}}

                            {{-- Upload --}}

                            {{-- Tombol Submit --}}

                            {{-- V1.1 Baru --}}
                            {{-- V1.1 Baru --}}
                            {{-- V1.1 Baru --}}
                            {{-- V1.1 Baru --}}

                            @if ($tipe == 1)
                                <div class="mb-2">
                                    <label for="nama" class="form-label">Konten <span
                                            class="small text-info">(Opsional)</span></label>
                                    <textarea id="tinymce" id="content2" name="content"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="uploadFile" class="form-label">Upload <span
                                            class="small text-info">(Opsional)</span></label>
                                    <!-- Dropzone -->
                                    <div id="my-dropzone" class="dropzone"></div>
                                </div>
                            @elseif($tipe == 2)
                                <!-- Essay - konten akan diisi di bagian Data Soal -->
                            @elseif($tipe == 3)
                                <!-- Mandiri - konten sudah diisi di atas, tidak perlu duplikasi -->
                            @endif




                        </div>
                    </div>
                </div>

                @if ($tipe == 2 || $tipe == 5)
                    <div class="mb-2">
                        <label for="nama" class="form-label">Konten <span
                                class="small text-info">(Opsional)</span></label>
                        <textarea id="tinymce" id="content2" name="content"></textarea>
                    </div>
                    <hr>
                    <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Soal</h4>
                    <div class=" bg-white p-4" id="containerPertanyaan">
                    </div>
                    {{-- Tombol Tambah Pertanyaan --}}
                    <div class="mt-2 mb-2">
                        <button type="button" class="btn btn-outline-success w-100 btn-lg" id="btnTambahPertanyaan">Tambah
                            Pertanyaan</button>
                    </div>
                @elseif($tipe == 3)
                    {{-- Tugas Mandiri - tidak perlu Data Soal, hanya menggunakan konten di atas --}}
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tugas Mandiri:</strong> Deskripsi tugas sudah diisi di bagian atas. Siswa dapat mengumpulkan tugas dengan mengetik langsung atau upload file.
                    </div>
                @endif

                @if ($tipe == 5)
                        @php
                            $user = App\Models\User::where('kelas_id', $kelasId)->get();
                            // dd($kelas);
                        @endphp

                        Jumlah Siswa : {{ count($user) }}
                        <div class=" bg-white p-4" id="containerKelompok">
                        </div>

                        <div class="mt-2 mb-2">
                            <button type="button" class="btn btn-outline-success w-100 btn-lg"
                                id="btnTambahKelompok">Tambah
                                Kelompok</button>
                        </div>
                    @endif
                @elseif($tipe == 4)
                    <div class="mb-2">
                        <label for="nama" class="form-label">Konten <span
                                class="small text-info">(Opsional)</span></label>
                        <textarea id="tinymce" id="content2" name="content"></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="uploadFile" class="form-label">Upload <span
                                class="small text-info">(Opsional)</span></label>
                        <!-- Dropzone -->
                        <div id="my-dropzone" class="dropzone"></div>
                    </div>
                    <hr>
                    <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Kelompok</h4>
                    @php
                        $user = App\Models\User::where('kelas_id', $kelasId)->get();
                        // dd($kelas);
                    @endphp

                    Jumlah Siswa : {{ count($user) }}
                    <div class=" bg-white p-4" id="containerKelompok">
                    </div>

                    <div class="mt-2 mb-2">
                        <button type="button" class="btn btn-outline-success w-100 btn-lg" id="btnTambahKelompok">Tambah
                            Kelompok</button>
                    </div>
                @endif


                <div class="">
                    <button type="submit" class="btn-lg btn btn-primary w-100" id="btnSimpan">Simpan dan
                        Lanjutkan</button>
                </div>
        </div>
    </div>


    </form>

    {{-- Script yang dibutuhkan --}}
    <script src="https://cdn.tiny.cloud/1/o4kte2cpt33yekucl40v8vcbvtiqpwi5exj2c4yg359exsob/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>;

    <script src="{{ url('/asset/js/rich-text-editor.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Menangkap submit form

            // Aktifkan date picker dengan format tanggal dan jam
            $(function() {
                $('#due').datetimepicker({
                    format: 'Y-m-d H:i',
                    locale: 'id',
                });
            });




            $('form').submit(function(e) {
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
                        @if ($tipe == 3 || $tipe == 2)
                            window.location.href =
                                "{{ route('redirectBack', ['kelasId' => $kelasId, 'mapelId' => $mapel['id'], 'message' => 'Tambah']) }}";
                        @else
                            uploadFiles();
                        @endif
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

    @if ($tipe == 1 || $tipe == 4)
        <script>
            // Inisialisasi Dropzone
            Dropzone.autoDiscover = false; // Untuk menghindari Dropzone menginisialisasi dirinya sendiri secara otomatis

            $('#btnSimpan').on('click', function() {
                if ($('#name').val() != "" && $('#content2').val() != "") {

                } else {
                    console.log('gagal');
                }
            });

            // Fungsi untuk memeriksa apakah konten TinyMCE tidak kosong
            function validateTinyMCE() {
                var content = tinymce.get("tinymce").getContent();
                if (!content.trim()) {
                    alert("Konten tidak boleh kosong.");
                    return false; // Membatalkan pengiriman formulir jika konten kosong
                }
                return true; // Lanjutkan pengiriman formulir jika konten tidak kosong
            }
            $("form").on("submit", function() {
                return validateTinyMCE();
            });

            var totalFilesToUpload = 0; // Total file yang diharapkan diunggah
            var completedFiles = 0; // Jumlah file yang sudah selesai diunggah

            // Konfigurasi Dropzone
            var myDropzone = new Dropzone("#my-dropzone", {
                url: "{{ route('uploadFileTugas', ['action' => 'tambah']) }}", // Ganti dengan rute yang sesuai untuk menangani unggahan file
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
                                    "{{ route('redirectBack', ['kelasId' => $kelasId, 'mapelId' => $mapel['id'], 'message' => 'Tambah']) }}";
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
                        "{{ route('redirectBack', ['kelasId' => $kelasId, 'mapelId' => $mapel['id'], 'message' => 'Tambah']) }}";
                } else {
                    // Ada file yang diunggah, proses antrian Dropzone
                    myDropzone.processQueue();
                }
            }
        </script>
    @endif

    @if ($tipe == 2 || $tipe == 3 || $tipe == 5)
        <script>
            $(document).ready(function() {

                // Aktifkan date picker dengan format tanggal dan jam

                // Tombol Tambah Pertanyaan diklik
                $('#btnTambahPertanyaan').click(function() {
                    // Mengambil jumlah pertanyaan saat ini
                    const jumlahPertanyaan = $('.pertanyaan').length;

                    console.log(jumlahPertanyaan);

                    // Membuat nomor pertanyaan yang akan digunakan
                    var nomorPertanyaan = jumlahPertanyaan + 1;
                    @if ($tipe == 2)
                        // Buat formulir pertanyaan baru Essay
                        const formulirPertanyaanBaru = `
     <div class="bg-white border border-dark-subtle rounded-2 p-4 mt-4 pertanyaan">
      <div class="">
                    <h3>Soal <span class="badge badge-primary">${nomorPertanyaan}</span>
                          <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
                    </h3>
                    <div class="mb-3">
                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea class="tinymce form-control" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" ></textarea>
                    </div>
                </div>
                </div>
`;
                    @elseif ($tipe == 3)
                        // Buat formulir pertanyaan baru Multiple
                        const formulirPertanyaanBaru = `
     <div class="bg-white border border-dark-subtle rounded-2 p-1 mt-1 pertanyaan">
                        <div class="">
                            <h3>Soal <span class="badge badge-primary">${nomorPertanyaan}</span>
                                <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
                            </h3>
                            <div class="mb-3 row">
                                <div class="col-lg-7 col-12">
                                    <label for="pertanyaan${nomorPertanyaan}"
                                        class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea class="tinymce form-control" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" ></textarea>
                                </div>
                                <div class="col-lg-5 col-12 row">
                                    <div class="col-5 m-1">
                                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">A
                                              <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="a[]" required
                                            id="">
                                    </div>
                                    <div class="col-5 m-1">
                                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">B
                                              <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="b[]" required
                                            id="">
                                    </div>
                                    <div class="col-5 m-1">
                                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">C
                                            <span class="text-danger">*</span>
                                            </label>
                                        <input type="text" class="form-control" name="c[]" required
                                            id="">
                                    </div>
                                    <div class="col-5 m-1">
                                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">D</label>
                                        <input type="text" class="form-control" name="d[]"
                                            id="">
                                    </div>
                                    <div class="col-5 m-1">
                                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">E</label>
                                        <input type="text" class="form-control" name="e[]"
                                            id="">
                                    </div>
                                    <div class="col-5 m-1">
                                        <label for="pertanyaan${nomorPertanyaan}"
                                            class="form-label text-primary fw-bold">Jawaban</label>
                                        <select name="jawaban[]" class="form-select" id="">
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                            <option value="e">E</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
`;
                    @else
                        const formulirPertanyaanBaru = `
     <div class="bg-white border border-dark-subtle rounded-2 p-4 mt-2 pertanyaan">
      <div class="">
                    <h5>Soal <span class="badge badge-primary">${nomorPertanyaan}</span>
                          <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
                    </h5>
                    <div class="mb-2">
                        <label for="pertanyaan${nomorPertanyaan}" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea class="tinymce form-control" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" ></textarea>
                    </div>
                </div>
                <div class="col-5 m-1">
                                        <label for="pertanyaan${nomorPertanyaan}"
                                            class="form-label text-primary fw-bold">Jawaban</label>
                                        <select name="jawaban[]" class="form-select" id="">
                                            <option value="ya">ya</option>
                                            <option value="tidak">tidak</option>
                                        </select>
                                    </div>
                </div>

`;
                    @endif
                    console.log("here1");

                    // Tambahkan formulir pertanyaan baru ke dalam container
                    $('#containerPertanyaan').append(formulirPertanyaanBaru);

                    tinymce.init({
                        selector: ".tinymce",
                        plugins: "image link lists media",
                        toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat",
                        menubar: false,
                        paste_data_images: false,
                        statusbar: false,

                        images_upload_handler: function(blobInfo, success, failure) {
                            // Fungsi penanganan unggah gambar, dapat diisi sesuai kebutuhan.
                            // Di sini, kami mengembalikan false untuk menonaktifkan unggah gambar.
                            return false;
                        },
                        ai_request: (request, respondWith) =>
                            respondWith.string(() =>
                                Promise.reject("See docs to implement AI Assistant")
                            ),
                    });






                    console.log("here2");

                    // Aktifkan tombol Kurangi pada pertanyaan sebelumnya (jika ada)
                    $('.pertanyaan:last').prev().find('.btnKurangi').show();
                });


                // Tombol Kurangi diklik
                $('#containerPertanyaan').on('click', '.btnKurangi', function() {
                    // Hapus formulir pertanyaan yang terkait
                    $(this).closest('.pertanyaan').remove();

                    // Update nomor pertanyaan pada pertanyaan yang tersisa
                    $('.pertanyaan').each(function(index) {
                        // Menggunakan $(this) untuk merujuk pada elemen pertanyaan saat ini
                        const nomorPertanyaan = index + 1;
                        $(this).find('h3 span.badge').text(nomorPertanyaan);
                    });

                });
            });
        </script>
    @endif



    @if ($tipe == 4)
        <script>
            $(document).ready(function() {

                // Aktifkan date picker dengan format tanggal dan jam

                // Tombol Tambah Pertanyaan diklik
                $('#btnTambahKelompok').click(function() {
                    // Mengambil jumlah pertanyaan saat ini
                    const jumlahPertanyaan = $('.pertanyaan').length;

                    console.log(jumlahPertanyaan);

                    // Membuat nomor pertanyaan yang akan digunakan
                    var nomorPertanyaan = jumlahPertanyaan + 1;

                    // Buat formulir pertanyaan baru Essay
                    const formulirPertanyaanBaru = `
<div class="bg-white border border-dark-subtle rounded-2 p-2 mt-1 pertanyaan">
<div class="">
    <h3>Kelompok <span class="badge badge-primary">${nomorPertanyaan}</span>
          <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
    </h3>
    <div class="mb-3">
        <label for="pertanyaan${nomorPertanyaan}" class="form-label">Nama Kelompok <span class="text-danger">*</span></label>
        <input class="form-control" id="pertanyaan${nomorPertanyaan}" name="kelompok[]" rows="3" >
    </div>
</div>
</div>
`;

                    // Buat formulir pertanyaan baru Multiple

                    console.log("here1");

                    // Tambahkan formulir pertanyaan baru ke dalam container
                    $('#containerKelompok').append(formulirPertanyaanBaru);

                    console.log("here2");

                    // Aktifkan tombol Kurangi pada pertanyaan sebelumnya (jika ada)
                    $('.pertanyaan:last').prev().find('.btnKurangi').show();
                });


                // Tombol Kurangi diklik
                $('#containerKelompok').on('click', '.btnKurangi', function() {
                    // Hapus formulir pertanyaan yang terkait
                    $(this).closest('.pertanyaan').remove();

                    // Update nomor pertanyaan pada pertanyaan yang tersisa
                    $('.pertanyaan').each(function(index) {
                        // Menggunakan $(this) untuk merujuk pada elemen pertanyaan saat ini
                        const nomorPertanyaan = index + 1;
                        $(this).find('h3 span.badge').text(nomorPertanyaan);
                    });

                });
            });
        </script>
    @elseif ($tipe == 5)
        <script>
            $(document).ready(function() {

                // Aktifkan date picker dengan format tanggal dan jam

                // Tombol Tambah Pertanyaan diklik
                $('#btnTambahKelompok').click(function() {
                    // Mengambil jumlah pertanyaan saat ini
                    const jumlahPertanyaan = $('.kelompok').length;

                    console.log(jumlahPertanyaan);

                    // Membuat nomor pertanyaan yang akan digunakan
                    var kelompok = jumlahPertanyaan + 1;

                    // Buat formulir pertanyaan baru Essay
                    const formulirPertanyaanBaru = `
<div class="bg-white border border-dark-subtle rounded-2 p-4 mt-4 kelompok">
<div class="">
    <h3>Kelompok
          <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
    </h3>
    <div class="mb-3">
        <label for="pertanyaan${kelompok}" class="form-label">Nama Kelompok <span class="text-danger">*</span></label>
        <input class="form-control" id="pertanyaan${kelompok}" name="kelompok[]" rows="3" >
    </div>
</div>
</div>
`;

                    // Buat formulir pertanyaan baru Multiple

                    console.log("here1");

                    // Tambahkan formulir pertanyaan baru ke dalam container
                    $('#containerKelompok').append(formulirPertanyaanBaru);

                    console.log("here2");

                    // Aktifkan tombol Kurangi pada pertanyaan sebelumnya (jika ada)
                    $('.pertanyaan:last').prev().find('.btnKurangi').show();
                });


                // Tombol Kurangi diklik
                $('#containerKelompok').on('click', '.btnKurangi', function() {
                    // Hapus formulir pertanyaan yang terkait
                    $(this).closest('.kelompok').remove();

                    // Update nomor pertanyaan pada pertanyaan yang tersisa
                    $('.kelompok').each(function(index) {
                        // Menggunakan $(this) untuk merujuk pada elemen pertanyaan saat ini
                        const kelompok = index + 1;
                        $(this).find('h3 span.badge').text(kelompok);
                    });

                });
            });
        </script>
    @endif
@endsection
