<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tugas;
use App\Models\TugasFile;
use App\Models\TugasQuiz;
use App\Models\UserTugas;
use App\Models\KelasMapel;
use App\Models\EditorAccess;
use App\Models\fileKelompok;
use Illuminate\Http\Request;
use App\Models\KelompokNilai;
use App\Models\TugasKelompok;
use App\Models\TugasMultiple;
use App\Models\UserTugasFile;
use App\Models\TugasKelompokQuiz;
use Illuminate\Support\Facades\DB;
use App\Models\AnggotaTugasKelompok;
use App\Models\TugasJawabanMultiple;
use App\Models\TugasKelompokQuizJawaban;

class TugasController extends Controller
{
    /**
     * Menampilkan halaman Tugas.
     *
     * @return \Illuminate\View\View
     */
    public function viewTugas(Request $request)
    {
        // Tugas id
        $id = $this->safeDecrypt($request->token);
        //kelasMapel id
        $idx = $this->safeDecrypt($request->kelasMapelId);
        
        if ($id === null || $idx === null) {
            return view('errors.token-invalid');
        }

        $tugas = Tugas::where('id', $id)->first();

        $roles = DashboardController::getRolesName();
        $kelasMapel = KelasMapel::where('id', $tugas->kelas_mapel_id)->first();

        // Dapatkan Pengajar
        $editorAccess = EditorAccess::where('kelas_mapel_id', $kelasMapel['id'])->first();
        $editorData = User::where('id', $editorAccess['user_id'])->where('roles_id', 2)->first();

        $mapel = Mapel::where('id', $request->mapelId)->first();
        $kelas = Kelas::where('id', $kelasMapel['kelas_id'])->first();

        $tugasAll = Tugas::where('kelas_mapel_id', $idx)->get();

        $userTugas = UserTugas::where('tugas_id', $tugas['id'])->where('user_id', Auth()->User()->id)->first();

        $assignedKelas = DashboardController::getAssignedClass();

        return view('menu.pengajar.tugas.viewTugas', ['userTugas' => $userTugas, 'assignedKelas' => $assignedKelas, 'editor' => $editorData, 'tugas' => $tugas, 'kelas' => $kelas, 'title' => $tugas->name, 'roles' => $roles, 'tugasAll' => $tugasAll, 'mapel' => $mapel, 'kelasMapel' => $kelasMapel]);
    }

    /**
     * Menampilkan halaman Tugas.
     *
     * @return \Illuminate\View\View
     */
    public function siswaUpdateNilai(Request $request)
    {
        $id = $this->safeDecrypt($request->token);
        
        if ($id === null) {
            return view('errors.token-invalid');
        }

        // Request SiswaId[], nilai[],

        // $userTugas = UserTugas::where('tugas_id', $id)->get();

        // Looping semua nilai user inputan
        for ($i = 0; $i < count($request->nilai); $i++) {
            // Memeriksa apakah nilai tidak sama dengan null dan tidak sama dengan string kosong
            if ($request->nilai[$i] !== null && $request->nilai[$i] !== '') {
                $exist = UserTugas::where('tugas_id', $id)->where('user_id', $request->siswaId[$i])->first();

                // Nilai Cap
                $nilai = $request->nilai[$i];

                if ($nilai >= 100) {
                    $nilai = 100;
                } elseif ($nilai <= 0) {
                    $nilai = 0;
                }

                // dd($exist);
                if ($exist) {
                    $data = [
                        'status' => 'Telah dinilai',
                        'nilai' => $nilai,
                    ];
                    $exist->update($data);
                } else {
                    $data = [
                        'tugas_id' => $id,
                        'user_id' => $request->siswaId[$i],
                        'status' => 'Telah dinilai',
                        'nilai' => $nilai,
                    ];
                    UserTugas::create($data);
                }
            }
        }

        return redirect()->back()->with('success', 'Nilai Telah diPerbaharui');
    }

    /**
     * Menampilkan halaman Tambah Tugas.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewCreateTugas($token, Request $request)
    {
        // id = Kelas Id
        $id = $this->safeDecrypt($token);
        
        if ($id === null) {
            return view('errors.token-invalid');
        }
        $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $id)->first();

        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $kelasMapel['id']) {
                $roles = DashboardController::getRolesName();
                $mapel = Mapel::where('id', $request->mapelId)->first();

                $assignedKelas = DashboardController::getAssignedClass();

                return view('menu.pengajar.tugas.viewTambahTugas', ['assignedKelas' => $assignedKelas, 'title' => 'Tambah Tugas', 'roles' => $roles, 'kelasId' => $id, 'mapel' => $mapel, "tipe" => $request->tipe]);
            }
        }
        abort(404);
    }
    public function submitTugasMultiple(Request $request)
    {
        $tugasMultiple = TugasMultiple::where('tugas_id', $request->tugasId)->get();
        $userJawabanArray = [];

        $tugasCount = count($tugasMultiple);

        for ($i = 0; $i < $tugasMultiple->count(); $i++) {
            $userJawabanArray[] = $request->input('jawaban' . ($i + 1));
        }

        // Sekarang, $userJawabanArray berisi semua jawaban dari $jawaban1, $jawaban2, dan seterusnya
        $nilaiSatuan = 100 / $tugasCount;
        // dd($nilaiSatuan);

        foreach ($userJawabanArray as $index => $userJawaban) {
            $data[] = [
                "tugas_multiple_id" => $tugasMultiple[$index]->id,
                "user_id" => Auth()->user()->id,
                "user_jawaban" => $userJawaban,
            ];
        }

        foreach ($data as $key) {

            $tugasData = TugasMultiple::where('id', $key['tugas_multiple_id'])->first();
            $getNilai = 0;
            // dd($key, $tugasData->jawaban);
            if (strtolower($key['user_jawaban']) == strtolower($tugasData->jawaban)) {
                $getNilai = $nilaiSatuan;
                // dd($getNilai);
            }

            $query = [
                "tugas_multiple_id" => $key['tugas_multiple_id'],
                "user_id" => Auth()->user()->id,
                "user_jawaban" => $userJawaban,
                "nilai" => $getNilai,
            ];
            TugasJawabanMultiple::create($query);
        }
        return redirect()->back()->with('success', "berhasil menyimpan tugas");
    }
    public function submitTugasKelompokQuiz(Request $request)
    {
        // dd($request);
        $tugasMultiple = TugasKelompokQuiz::where('tugas_id', $request->tugasId)->get();
        $userJawabanArray = [];

        $kelompok = AnggotaTugasKelompok::where('user_id', Auth()->user()->id)->first();
        // $kelompok = TugasKelompok::where('id', )->first();

        $tugasCount = count($tugasMultiple);

        for ($i = 0; $i < $tugasMultiple->count(); $i++) {
            $userJawabanArray[] = $request->input('jawaban' . ($i + 1));
        }

        // Sekarang, $userJawabanArray berisi semua jawaban dari $jawaban1, $jawaban2, dan seterusnya
        $nilaiSatuan = 100 / $tugasCount;
        // dd($nilaiSatuan);

        foreach ($userJawabanArray as $index => $userJawaban) {
            $data[] = [
                "tugas_kelompok_quiz_id" => $tugasMultiple[$index]->id,
                "user_id" => $kelompok['tugas_kelompok_id'],
                "jawaban" => $userJawaban,
            ];
        }

        for ($i = 0; $i < count($tugasMultiple); $i++) {
            $getNilai = 0;

            // Pastikan indeks $data dan $tugasMultiple ada sejumlah yang sama
            if ($i < count($data)) {
                if (strtolower($data[$i]['jawaban']) == strtolower($tugasMultiple[$i]['jawaban'])) {
                    $getNilai = $nilaiSatuan;
                }
            } else {
            }

            $query = [
                "tugas_kelompok_quiz_id" => $data[$i]['tugas_kelompok_quiz_id'],
                "tugas_kelompok_id" => $kelompok['id'], // Ganti Auth() dengan auth()
                "jawaban" => $userJawaban,  // Pastikan $userJawaban sudah didefinisikan sebelumnya
                "nilai" => $getNilai,
            ];
            // dd($query, $data);
            TugasKelompokQuizJawaban::create($query);
        }


        // for ($tugasMultiple as $key) {
        //     $getNilai = 0;
        //     // dd($key, $tugasData->jawaban);
        //     if (strtolower($key['user_jawaban']) == strtolower($key->jawaban)) {
        //         $getNilai = $nilaiSatuan;
        //         // dd($getNilai);
        //     }

        //     $query = [
        //         "tugas_multiple_id" => $key['tugas_multiple_id'],
        //         "user_id" => Auth()->user()->id,
        //         "user_jawaban" => $userJawaban,
        //         "nilai" => $getNilai,
        //     ];
        //     TugasJawabanMultiple::create($query);
        // }

        // foreach ($data as $key) {
        //     $tugasData = TugasKelompokQuiz::where('id', $key['tugas_multiple_id'])->first();
        //     $getNilai = 0;
        //     // dd($key, $tugasData->jawaban);
        //     if (strtolower($key['user_jawaban']) == strtolower($tugasData->jawaban)) {
        //         $getNilai = $nilaiSatuan;
        //         // dd($getNilai);
        //     }

        //     $query = [
        //         "tugas_multiple_id" => $key['tugas_multiple_id'],
        //         "user_id" => Auth()->user()->id,
        //         "user_jawaban" => $userJawaban,
        //         "nilai" => $getNilai,
        //     ];
        //     TugasJawabanMultiple::create($query);
        // }
        return redirect()->back()->with('success', "berhasil menyimpan tugas");
    }


    public function tambahKelompok(Request $request)
    {
        $request->validate([
            "nama" => "required",
            "idTugas" => "required",
        ]);

        $data = [
            "name" => $request->nama,
            "tugas_id" => $request->idTugas,
        ];
        TugasKelompok::create($data);
        return redirect()->back()->with("success", "berhasil menambahkan kelompok");
        // dd($request);
    }
    public function submitNilaiKelompok(Request $request)
    {
        $request->validate([
            "fromKelompok" => "required",
            "toKelompok" => "required",
            "nilai" => "required",
        ]);

        $data = [
            "tugas_kelompok_id" => $request->fromKelompok,
            "to_kelompok" => $request->toKelompok,
            "nilai" => $request->nilai,
        ];
        KelompokNilai::create($data);

        // update Nilai
        $tugasKelompok = TugasKelompok::where('id', $request->toKelompok)->first();
        $tugasId = $tugasKelompok['tugas_id'];
        $allKelompok = TugasKelompok::where('tugas_id', $tugasId)->get();
        foreach ($allKelompok as $key) {
            $kelompokNilai = KelompokNilai::where('to_kelompok', $key->id)->get();
            $nilai = 0;
            $countKelompok = count($allKelompok);
            foreach ($kelompokNilai as $key2) {
                $nilai += $key2['nilai'];
            }

            $nilaiTotal = $nilai / ($countKelompok - 1);

            TugasKelompok::where('id', $key->id)->update(["nilai" => $nilaiTotal]);

            // dd($nilai);
        }

        return redirect()->back()->with("success", "berhasil menambahkan nilai");
        // dd($request);
    }
    public function deleteKelompok(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);

        // Anggota::create($data);
        AnggotaTugasKelompok::where('tugas_kelompok_id', $request->id)->delete();
        TugasKelompok::where('id', $request->id)->delete();
        return redirect()->back()->with("success", "berhasil delete kelompok");
        // dd($request);
    }
    public function submitQuiz(Request $request)
    {
        // dd($request);
        // $request->validate([
        //     "id" => "required",
        // ]);

        for ($i = 0; $i < count($request->quizId); $i++) {
            // if exist

            $exist = TugasJawabanMultiple::where('tugas_quiz_id', $request->quizId[$i])->where('user_id', Auth()->user()->id)->first();

            if ($exist) {
                $data = [
                    "tugas_quiz_id" => $request->quizId[$i],
                    "user_id" => Auth()->user()->id,
                    "user_jawaban" => $request->jawabanUser[$i],
                ];
                TugasJawabanMultiple::where('tugas_quiz_id', $request->quizId[$i])->where('user_id', Auth()->user()->id)->update($data);
            } else {
                $data = [
                    "tugas_quiz_id" => $request->quizId[$i],
                    "user_id" => Auth()->user()->id,
                    "user_jawaban" => $request->jawabanUser[$i],
                ];
                TugasJawabanMultiple::create($data);
            }
        }

        return redirect()->back()->with("success", "Berhasil Submit Tugas");
        // dd($request);
    }

    public function tugasNilaiQuiz(Request $request)
    {
        // dd($request);

        for ($i = 0; $i < count($request->siswaId); $i++) {
            if ($request->nilai[$i] != null) {

                if ($request->siswaId[$i] == null) {
                    $koreksi = null;
                } else {
                    $koreksi = $request->koreksi[$i];
                }
                // check nilai if exist update
                $nilai = TugasJawabanMultiple::where("tugas_quiz_id", $request->quizId[$i])->where("user_id", $request->siswaId[$i])->first();
                if ($nilai != null) {
                    $data = [
                        "nilai" => $request->nilai[$i],
                        "koreksi" => $koreksi
                    ];
                    TugasJawabanMultiple::where("tugas_quiz_id", $request->quizId[$i])->where("user_id", $request->siswaId[$i])->update($data);
                } else {
                    $data = [
                        "tugas_quiz_id" => $request->quizId[$i],
                        "user_id" => $request->siswaId[$i],
                        "nilai" => $request->nilai[$i],
                        "user_jawaban" => "-",
                        "koreksi" =>  $koreksi
                    ];
                    TugasJawabanMultiple::create($data);
                }

                return redirect()->back()->with("success", "nilai diperbarui");
            }
        }
    }

    public function tambahAnggota(Request $request)
    {
        $request->validate([
            "user_id" => "required",
            "tugas_kelompok_id" => "required",
            "isKetua" => "required",
            "tugas_id" => "required",
        ]);

        $exist = AnggotaTugasKelompok::where('tugas_id', $request->tugas_id)->where('tugas_kelompok_id', $request->tugas_kelompok_id)->where('isKetua', 1)->first();

        if ($exist && $request->isKetua == 1) {
            $idKetua = $exist['id'];
            $temp = [
                "isKetua" => 0
            ];
            AnggotaTugasKelompok::where('id', $idKetua)->update($temp);
        }

        $data = [
            "tugas_kelompok_id" => $request->tugas_kelompok_id,
            "user_id" => $request->user_id,
            "isKetua" => $request->isKetua,
            "tugas_id" => $request->tugas_id,
        ];

        AnggotaTugasKelompok::create($data);
        // dd($data);
        return redirect()->back()->with('success', "berhasil menambahkan data");
    }

    public function deleteAnggota(Request $request)
    {
        $request->validate([
            "user_id" => "required",
            "tugas_kelompok_id" => "required",
        ]);

        AnggotaTugasKelompok::where('user_id', $request->user_id)->where('tugas_kelompok_id', $request->tugas_kelompok_id)->delete();
        return redirect()->back()->with('success', "berhasil delete data");
    }

    /**
     * Menampilkan halaman Update Tugas.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewUpdateTugas($token, Request $request)
    {
        // token = Tugas Id
        $id = decrypt($token);
        $tugas = Tugas::where('id', $id)->first();  // Dapatkan tugas

        // Dapatkan kelas mapel untuk dibandingkan dengan tugas
        $kelasMapel = KelasMapel::where('id', $tugas->kelas_mapel_id)->first();

        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $kelasMapel['id']) {
                $roles = DashboardController::getRolesName();
                $mapel = Mapel::where('id', $request->mapelId)->first();

                $kelas = Kelas::where('id', $kelasMapel['kelas_id'])->first('id');

                $assignedKelas = DashboardController::getAssignedClass();

                return view('menu.pengajar.tugas.viewUpdateTugas', ['assignedKelas' => $assignedKelas, 'title' => 'Update Tugas', 'tugas' => $tugas, 'roles' => $roles, 'kelasId' => $kelas['id'], 'mapel' => $mapel, 'kelasMapel' => $kelasMapel]);
            }
        }
        abort(404);
    }

    /**
     * Membuat Tugas baru.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTugas(Request $request)
    {

        // dd($request);
        // Lakukan validasi untuk inputan form

        $request->validate([
            'name' => 'required',
            'content' => 'required',
            'due' => 'required',
            'tipe' => 'required',
        ]);

        // return response()->json(['message' => $request->input()], 200);


        $tanggalWaktuIndonesia = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->due);
        // return response()->json(['message' => $tanggalWaktuIndonesia], 200);
        // return response()->json(['message' => now()], 200);

        try {
            // Dekripsi token dan dapatkan KelasMapel
            $token = decrypt($request->kelasId);
            $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $token)->first();
            // return response()->json(['message' => $kelasMapel], 200);

            $isHidden = 1;

            if ($request->opened) {
                $isHidden = 0;
            }

            // Assesment Mandiri
            if ($request->tipe == 1) {
                $temp = [
                    'kelas_mapel_id' => $kelasMapel['id'],
                    'name' => $request->name,
                    'content' => $request->content,
                    'due' => $tanggalWaktuIndonesia,
                    'isHidden' => $isHidden,
                    'tipe' => $request->tipe,
                ];

                // Simpan data Tugas ke database
                Tugas::create($temp);

                // Commit transaksi database
                DB::commit();

                // Berikan respons sukses jika semuanya berjalan lancar
                return response()->json(['message' => 'Tugas berhasil dibuat'], 200);
            } elseif ($request->tipe == 2) {
                $temp = [
                    'kelas_mapel_id' => $kelasMapel['id'],
                    'name' => $request->name,
                    'content' => $request->content,
                    'due' => $tanggalWaktuIndonesia,
                    'isHidden' => $isHidden,
                    'tipe' => $request->tipe,
                ];

                // Simpan data Tugas ke database
                $x = Tugas::create($temp);

                foreach ($request->pertanyaan as $key) {
                    $data = [
                        "tugas_id" => $x['id'],
                        "soal" => $key,
                    ];
                    TugasQuiz::create($data);
                }

                return response()->json(['message' => $request->input()], 200);
            } elseif ($request->tipe == 3) {
                $temp = [
                    'kelas_mapel_id' => $kelasMapel['id'],
                    'name' => $request->name,
                    'content' => $request->content,
                    'due' => $tanggalWaktuIndonesia,
                    'isHidden' => $isHidden,
                    'tipe' => $request->tipe,
                ];
                // Simpan data Tugas ke database
                $x = Tugas::create($temp);

                // Handle Mandiri Questions (Text-based questions)
                if ($request->has('mandiri_questions')) {
                    foreach ($request->mandiri_questions as $question) {
                        if (!empty($question['question'])) {
                            $data = [
                                'tugas_id' => $x['id'],
                                'soal' => $question['question'],
                                'poin' => $question['poin'] ?? 10,
                                'kategori' => $question['kategori'] ?? 'medium',
                            ];
                            TugasQuiz::create($data);
                        }
                    }
                }

                // Handle Multiple Choice Questions (if any)
                if ($request->has('pertanyaan')) {
                    for ($i = 0; $i < count($request->pertanyaan); $i++) {
                        $d = null;
                        $e = null;

                        if ($request->d[$i]) {
                            $d = $request->d[$i];
                        }

                        if ($request->e[$i]) {
                            $e = $request->e[$i];
                        }

                        $data = [
                            'tugas_id' => $x['id'],
                            'soal' => $request->pertanyaan[$i],
                            'a' => $request->a[$i],
                            'b' => $request->b[$i],
                            'c' => $request->c[$i],
                            'd' => $d,
                            'e' => $e,
                            'jawaban' => $request->jawaban[$i],
                        ];

                        if ($request->pertanyaan[$i]) {
                            TugasMultiple::create($data);
                        }
                    }
                }

                // foreach ($request->pertanyaan as $key) {
                //     $data = [
                //         "tugas_id" => $x['id'],
                //         "soal" => $key,
                //     ];
                //     TugasMultiple::create($data);
                // }
            } elseif ($request->tipe == 4) {
                $temp = [
                    'kelas_mapel_id' => $kelasMapel['id'],
                    'name' => $request->name,
                    'content' => $request->content,
                    'due' => $tanggalWaktuIndonesia,
                    'isHidden' => $isHidden,
                    'tipe' => $request->tipe,
                ];
                // Simpan data Tugas ke database
                $x = Tugas::create($temp);

                foreach ($request->kelompok as $key) {
                    $data = [
                        "tugas_id" => $x['id'],
                        "name" => $key,
                    ];
                    TugasKelompok::create($data); //
                }

                return response()->json(['message' => $x], 200);


                // return redirect("setting-kelompok")->with(["tugas" => $x]);
            } elseif ($request->tipe == 5) {
                // return response()->json(['message' => count($request->pertanyaan)], 200);
                $temp = [
                    'kelas_mapel_id' => $kelasMapel['id'],
                    'name' => $request->name,
                    'content' => $request->content,
                    'due' => $tanggalWaktuIndonesia,
                    'isHidden' => $isHidden,
                    'tipe' => $request->tipe,
                ];

                // Simpan data Tugas ke database
                $x = Tugas::create($temp);


                for ($i = 0; $i < count($request->pertanyaan); $i++) {
                    $data = [
                        'tugas_id' => $x['id'],
                        'soal' => $request->pertanyaan[$i],
                        'jawaban' => $request->jawaban[$i],
                    ];
                    TugasKelompokQuiz::create($data);
                }

                foreach ($request->kelompok as $key) {
                    $data = [
                        "tugas_id" => $x['id'],
                        "name" => $key,
                    ];
                    TugasKelompok::create($data); //
                }

                return response()->json(['message' => $x], 200);


                // return redirect("setting-kelompok")->with(["tugas" => $x]);
            } else {
                abort(404);
            }
            // return response()->json(['message' => $request->input()], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error'], 200);
        }
    }

    public function settingKelompok(Request $request)
    {
        // dd($request->input());
        $request->validate([
            "tugas" => "required", // tugas id
            "id_kelompok" => "required",
        ]);

        $tugas = Tugas::find($request->tugas);
        $kelompok = TugasKelompok::find($request->id_kelompok);
        $anggota = AnggotaTugasKelompok::where("tugas_kelompok_id", $request->id_kelompok)->get();

        $roles = DashboardController::getRolesName();
        $mapel = Mapel::where('id', $request->mapelId)->first();

        $assignedKelas = DashboardController::getAssignedClass();

        // Get kelas id
        $kelasMapel = KelasMapel::where('id', $tugas['kelas_mapel_id'])->first();
        $kelas = Kelas::where('id', $kelasMapel['kelas_id'])->first();

        $user = User::where('kelas_id', $kelas['id'])->get();

        // dd($user);

        return view('menu.pengajar.tugas.viewSettingKelompok', ["user" => $user, 'assignedKelas' => $assignedKelas, 'title' => 'Setting Kelompok', 'roles' => $roles, "mapel" => $mapel, "tugas" => $tugas, "kelompok" => $kelompok, "anggota" => $anggota]);
    }

    /**
     * Mengupdate Tugas.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTugas(Request $request)
    {
        // Lakukan validasi untuk inputan form
        $request->validate([
            'name' => 'required',
            'content' => 'required',
            'due' => 'required',
        ]);

        // return response()->json(['message' => $request->due], 200);
        // return response()->json(['message' => $request->input()], 200);

        $tanggalWaktuIndonesia = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->due);

        // return response()->json(['message' => $tanggalWaktuIndonesia], 200);
        // Dekripsi token hasil dari hidden form lalu dapatkan data KelasMapel
        $tugasId = decrypt($request->tugasId);

        try {
            $isHidden = 1;

            if ($request->opened) {
                $isHidden = 0;
            }

            $data = [
                'name' => $request->name,
                'content' => $request->content,
                'due' => $tanggalWaktuIndonesia,
                'isHidden' => $isHidden,
            ];
            // Simpan data Tugas ke database
            Tugas::where('id', $tugasId)->update($data);
            // Commit transaksi database
            DB::commit();

            // Berikan respons sukses jika semuanya berjalan lancar
            return response()->json(['message' => 'Tugas berhasil dibuat'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error'], 200);
        }
    }

    /**
     * Membuat Tugas baru.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadFileTugas(Request $request)
    {
        // Dapatkan Id Tugas dari Inputan Form request
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|file|max:2048', // Batasan ukuran maksimum adalah 2 MB (ganti sesuai kebutuhan Anda)
        ]);

        // return response()->json(['message' => $request->input()]);
        if ($request->action == 'tambah') {
            $latesTugas = Tugas::latest()->first();

            // Proses unggahan file di sini
            $file = $request->file('file');
            $fileName = 'F' . mt_rand(1, 999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/file/tugas'), $fileName); // Simpan file di direktori 'storage/app/uploads'

            TugasFile::create([
                'tugas_id' => $latesTugas['id'],
                'file' => $fileName,
            ]);

            return response()->json(['message' => 'File berhasil diunggah.']); // Respon sukses
        } elseif ($request->action == 'edit') {
            // Proses unggahan file di sini
            $file = $request->file('file');
            $fileName = 'F' . mt_rand(1, 999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/file/tugas'), $fileName); // Simpan file di direktori 'storage/app/uploads'

            TugasFile::create([
                'tugas_id' => $request->idTugas,
                'file' => $fileName,
            ]);

            return response()->json(['message' => 'File berhasil diunggah.']); // Respon sukses
        }

        return response()->json(['message' => 'File Error.']);
    }

    /**
     * Delete file Tugas.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFileTugas(Request $request)
    {
        $idTugas = $request->idTugas;
        $fileName = $request->fileName;

        $dest = '../public_html/file/tugas'; // Destinasi tempat pengguna akan disimpan
        // $dest = 'file/tugas'; // Destinasi untuk Localhost

        if (file_exists(public_path($dest . '/' . $fileName))) {
            unlink(public_path($dest . '/' . $fileName));
        }

        TugasFile::where('tugas_id', $idTugas)->where('file', $fileName)->delete();

        return redirect()->back()->with('success', 'File Deleted');
    }
    public function deleteKelompokFile(Request $request)
    {
        $idTugas = $request->idTugas;
        $fileName = $request->fileName;

        $dest = '../public_html/file/tugas'; // Destinasi tempat pengguna akan disimpan
        // $dest = 'file/tugas'; // Destinasi untuk Localhost

        if (file_exists(public_path($dest . '/' . $fileName))) {
            unlink(public_path($dest . '/' . $fileName));
        }
        // dd($fileName);

        fileKelompok::where('file', $fileName)->delete();

        return redirect()->back()->with('success', 'File Deleted');
    }

    /**
     * Menghapus tugas.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyTugas(Request $request)
    {

        // Dapatkan Id tugas dari Inputan Form request
        $tugasId = $request->hapusId;

        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $request->kelasMapelId) {
                $dest = '../public_html/file/tugas'; // Destinasi tempat pengguna akan disimpan
                // $dest = 'file/tugas'; // Destinasi tempat pengguna akan disimpan (localhost)
                $files = TugasFile::where('tugas_id', $tugasId)->get();
                foreach ($files as $key) {
                    if (file_exists(public_path($dest . '/' . $key->file))) {
                        unlink(public_path($dest . '/' . $key->file));
                    }
                }
                Tugas::where('id', $tugasId)->delete();
                TugasFile::where('tugas_id', $tugasId)->delete();
                UserTugas::where('tugas_id', $tugasId)->delete();

                return redirect()->back()->with('success', 'Tugas Berhasil dihapus');
            }
        }
        abort(404);
    }

    public function submitTugas(Request $request)
    {

        $tugasId = decrypt($request->tugasId);
        $userId = decrypt($request->userId);

        $data = [
            'tugas_id' => $tugasId,
            'user_id' => $userId,
            'status' => 'Selesai',
        ];
        $userTugas = UserTugas::create($data);

        return response()->json(['message' => 'File berhasil diunggah.']); // Respon sukses
    }

    // public function submitKelompok(Request $request)
    // {
    //     $tugasId = decrypt($request->tugasId);
    //     $userId = decrypt($request->userId);

    //     $data = [
    //         'tugas_id' => $tugasId,
    //         'user_id' => $userId,
    //         'status' => 'Selesai',
    //     ];
    //     $userTugas = UserTugas::create($data);

    //     return response()->json(['message' => 'Kelompok berhasil diunggah.']); // Respon sukses
    // }

    public function submitFileTugas(Request $request)
    {

        $request->validate([
            'file' => 'required|file|max:2048', // Batasan ukuran maksimum adalah 2 MB (ganti sesuai kebutuhan Anda)
        ]);

        $tugasId = decrypt($request->tugasId);
        $userId = Auth()->User()->id;

        $tugas = Tugas::where('id', $tugasId)->first();

        $dueDateTime = \Carbon\Carbon::parse($tugas->due); // Mengatur timezone ke Indonesia (ID)
        $localTime = \Carbon\Carbon::parse($tugas->due)->setTimeZone('asia/jakarta'); // Mengatur timezone ke Indonesia (ID)
        $now = \Carbon\Carbon::now(); // Mengatur timezone ke Indonesia (ID)
        $timeUntilDue = $dueDateTime->diff($now); // Perbedaan waktu antara sekarang dan waktu jatuh tempo

        if ($dueDateTime > $now) {
            $exist = UserTugas::where('tugas_id', $tugasId)->where('user_id', $userId)->first();

            if ($exist['status'] == 'Belum Mengerjakan') {
                $data = [
                    'status' => 'Selesai',
                ];
                $exist->update($data);
            }

            if ($exist) {
                // Proses unggahan file di sini
                $file = $request->file('file');
                $fileName = 'F' . mt_rand(1, 999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/file/tugas/user'), $fileName); // Simpan file di direktori 'storage/app/uploads'
                UserTugasFile::create([
                    'user_tugas_id' => $exist['id'],
                    'file' => $fileName,
                ]);

                return response()->json(['success', 'Upload success']);
            } else {
                return response()->json(['failed', 'Upload failed']);
            }
        } else {
            return response()->json(['404', '404']);
        }
    }

    public function submitFileKelompok(Request $request)
    {

        $request->validate([
            'file' => 'required|file|max:2048', // Batasan ukuran maksimum adalah 2 MB (ganti sesuai kebutuhan Anda)
        ]);

        $tugasId = $request->tugasId;
        $userId = Auth()->User()->id;

        $tugas = Tugas::where('id', $tugasId)->first();
        // dd($request);

        $dueDateTime = \Carbon\Carbon::parse($tugas->due); // Mengatur timezone ke Indonesia (ID)
        $localTime = \Carbon\Carbon::parse($tugas->due)->setTimeZone('asia/jakarta'); // Mengatur timezone ke Indonesia (ID)
        $now = \Carbon\Carbon::now(); // Mengatur timezone ke Indonesia (ID)
        $timeUntilDue = $dueDateTime->diff($now); // Perbedaan waktu antara sekarang dan waktu jatuh tempo

        if ($dueDateTime > $now) {
            $exist = TugasKelompok::where('tugas_id', $tugasId)->where('id', $request->kelompokId)->first();

            if ($exist['status'] == 'Belum Mengerjakan') {
                $data = [
                    'status' => 'Selesai',
                ];
                $exist->update($data);
            }

            if ($exist) {
                // Proses unggahan file di sini
                $file = $request->file('file');
                $fileName = 'F' . mt_rand(1, 999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/file/tugas'), $fileName); // Simpan file di direktori 'storage/app/uploads'
                fileKelompok::create([
                    'tugas_kelompok_id' => $exist['id'],
                    'file' => $fileName,
                ]);

                return redirect()->back();
                // return response()->json(['success', 'Upload success']);
            } else {
                return response()->json(['failed', 'Upload failed']);
            }
        } else {
            return response()->json(['404', '404']);
        }
    }

    /**
     * Delete file Tugas.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFileSubmit(Request $request)
    {
        // $userTugasId = $request->userTugasId;
        $fileName = $request->fileName;

        $userTugasFile = UserTugasFile::where('file', $fileName)->get();
        $userTugas = UserTugas::where('id', $userTugasFile[0]->user_tugas_id)->first();

        $dest = '../public_html/file/tugas'; // Destinasi tempat pengguna akan disimpan

        if (file_exists(public_path($dest . '/' . $fileName))) {
            unlink(public_path($dest . '/' . $fileName));
        }

        UserTugasFile::where('user_tugas_id', $userTugas['id'])->where('file', $fileName)->delete();
        $userTugasCount = UserTugasFile::where('user_tugas_id', $userTugas['id'])->count();

        if ($userTugasCount <= 0) {
            $data = [
                'status' => 'Belum Mengerjakan',
            ];

            UserTugas::where('id', $userTugas['id'])->update($data);
        }

        return redirect()->back()->with('success', 'File Deleted');
    }

    // Baru V1.1
    public function viewMenuTugas($token, Request $request)
    {
        // id = Kelas Id
        $id = decrypt($token);
        $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $id)->first();

        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $kelasMapel['id']) {
                $roles = DashboardController::getRolesName();
                $mapel = Mapel::where('id', $request->mapelId)->first();

                $assignedKelas = DashboardController::getAssignedClass();

                return view('menu.pengajar.tugas.viewMenuTugas', ['assignedKelas' => $assignedKelas, 'title' => 'Tambah Tugas', 'roles' => $roles, 'kelasId' => $id, 'mapel' => $mapel, "tipe" => $request->tipe]);
            }
        }
        abort(404);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tugas_id' => 'required|integer',
            'soal' => 'required|string',
        ]);

        TugasQuiz::create($validatedData);

        return redirect()->route('viewTugas');
    }
}
