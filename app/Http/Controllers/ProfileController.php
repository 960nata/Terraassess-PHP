<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Contact;
use App\Models\KelasMapel;
use App\Models\EditorAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil pengajar berdasarkan token.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewProfilePengajar($token)
    {
        try {
            $id = Crypt::decrypt($token);
            $roles = DashboardController::getRolesName();
            $profile = User::findOrFail($id);
            $editorAccess = EditorAccess::where('user_id', $id)->get();

            $mapelKelas = [];

            foreach ($editorAccess as $key) {
                $kelasMapel = KelasMapel::where('id', $key->kelas_mapel_id)->first();

                if ($kelasMapel) {
                    $mapelID = $kelasMapel->mapel_id;
                    $kelasID = $kelasMapel->kelas_id;

                    // Pemeriksaan mapel
                    $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                    if ($mapelKey !== false) {
                        // Tambahkan ke Array
                        $mapelKelas[$mapelKey]['kelas'][] = Kelas::where('id', $kelasID)->first();
                    } else {
                        // Temukan Mapel
                        $mapelKelas[] = [
                            'mapel_id' => $mapelID,
                            'mapel' => Mapel::where('id', $mapelID)->first(),
                            'kelas' => [Kelas::where('id', $kelasID)->first()],
                        ];
                    }
                }
            }

            $assignedKelas = DashboardController::getAssignedClass();

            return view('menu.profile.profilePengajar', ['assignedKelas' => $assignedKelas, 'user' => $profile, 'mapelKelas' => $mapelKelas,  'roles' => $roles, 'title' => 'Profil']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Menampilkan profil siswa berdasarkan token.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewProfileSiswa($token)
    {
        try {
            $id = Crypt::decrypt($token);

            $roles = DashboardController::getRolesName();
            $profile = User::findOrFail($id);

            $kelas = Kelas::where('id', $profile->kelas_id)->first();

            $kelasMapel = KelasMapel::where('kelas_id', $kelas['id'])->get();
            $mapelCollection = [];

            foreach ($kelasMapel as $key) {
                $mapel = Mapel::where('id', $key->mapel_id)->first();
                $editorAccess = EditorAccess::where('kelas_mapel_id', $key->id)->first();

                if ($editorAccess) {
                    $editorAccess = $editorAccess['user_id'];
                    $pengajar = User::where('id', $editorAccess)->first(['id', 'name']);
                    $pengajarNama = $pengajar['name'];
                    $pengajarId = $pengajar['id'];
                } else {
                    $pengajarNama = '-';
                    $pengajarId = null;
                }

                $mapelCollection[] = [
                    'mapel_name' => $mapel['name'],
                    'mapel_id' => $mapel['id'],
                    'deskripsi' => $mapel['deskripsi'],
                    'gambar' => $mapel['gambar'],
                    'pengajar_id' => $pengajarId,
                    'pengajar_name' => $pengajarNama,
                ];
            }

            $assignedKelas = DashboardController::getAssignedClass();

            return view('menu.profile.profileSiswa', ['assignedKelas' => $assignedKelas, 'user' => $profile, 'kelas' => $kelas, 'mapelKelas' => $mapelCollection, 'roles' => $roles, 'title' => 'Profil']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Mengelola pengunggahan gambar profil pengguna.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function cropImageUser(Request $request)
    {
        $request->validate([
            'file' => 'file|image|max:4000',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $newImageName = 'UIMG' . date('YmdHis') . uniqid() . '.jpg'; // Nama baru

            // Simpan file ke dalam penyimpanan
            $path = $file->storeAs('user-images', $newImageName, 'public');

            if (!$path) {
                return response()->json(['status' => 0, 'msg' => 'Upload Gagal']);
            }

            // Hapus file gambar lama dari penyimpanan
            $userInfo = User::find($request->id);
            $userPhoto = $userInfo->gambar;

            if ($userPhoto != null) {
                Storage::disk('public')->delete('user-images/' . $userPhoto);
            }

            // Perbarui gambar
            $userInfo->update(['gambar' => $newImageName]);

            return response()->json(['status' => 1, 'msg' => 'Upload berhasil', 'name' => $newImageName]);
        }

        return response()->json(['status' => 0, 'msg' => 'Tidak ada file yang diunggah']);
    }


    /**
     * Menampilkan profil pengguna sendiri.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function myProfile($token)
    {
        try {
            $id = Crypt::decrypt($token);

            $roles = DashboardController::getRolesName();
            $profile = User::findOrFail($id);

            $kelas = Kelas::where('id', $profile->kelas_id)->first();

            $kelasMapel = KelasMapel::where('kelas_id', $kelas['id'])->get();
            $mapelCollection = [];

            foreach ($kelasMapel as $key) {
                $mapel = Mapel::where('id', $key->mapel_id)->first();
                $editorAccess = EditorAccess::where('kelas_mapel_id', $key->id)->first();

                if ($editorAccess) {
                    $editorAccess = $editorAccess['user_id'];
                    $pengajar = User::where('id', $editorAccess)->first(['id', 'name']);
                    $pengajarNama = $pengajar['name'];
                    $pengajarId = $pengajar['id'];
                } else {
                    $pengajarNama = '-';
                    $pengajarId = null;
                }

                $mapelCollection[] = [
                    'mapel_name' => $mapel['name'],
                    'mapel_id' => $mapel['id'],
                    'deskripsi' => $mapel['deskripsi'],
                    'gambar' => $mapel['gambar'],
                    'pengajar_id' => $pengajarId,
                    'pengajar_name' => $pengajarNama,
                ];
            }

            $assignedKelas = DashboardController::getAssignedClass();

            return view('menu.profile.profileSiswa', ['assignedKelas' => $assignedKelas, 'user' => $profile, 'kelas' => $kelas['name'], 'mapelKelas' => $mapelCollection, 'roles' => $roles, 'title' => 'Profil']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Menampilkan halaman pengaturan profil pengguna.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewProfileSetting($token)
    {
        try {
            $id = Crypt::decrypt($token);
            $roles = DashboardController::getRolesName();

            if ($id == Auth()->User()->id) {
                $user = User::where('id', $id)->first();
                $contact = Contact::where('user_id', $id)->first();
                $kelas = Kelas::where('id', Auth()->User()->kelas_id)->first();
                $assignedKelas = DashboardController::getAssignedClass();

                return view('menu.profile.setting.settingUser', ['assignedKelas' => $assignedKelas, 'kelas' => $kelas, 'user' => $user, 'contact' => $contact, 'title' => 'Profil Setting', 'roles' => $roles]);
            } else {
                abort(404);
            }
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Menampilkan halaman profil pengguna yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function viewProfile()
    {
        $user = auth()->user();
        $roles = DashboardController::getRolesName();
        $assignedKelas = DashboardController::getAssignedClass();

        // Tentukan view berdasarkan role
        if ($user->roles_id == 1) { // Admin
            return $this->viewProfilePengajar(Crypt::encrypt($user->id));
        } elseif ($user->roles_id == 2) { // Pengajar
            return $this->viewProfilePengajar(Crypt::encrypt($user->id));
        } else { // Siswa
            return $this->viewProfileSiswa(Crypt::encrypt($user->id));
        }
    }

    /**
     * Menampilkan halaman pengaturan pengguna yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function viewSettings()
    {
        $user = auth()->user();
        return $this->viewProfileSetting(Crypt::encrypt($user->id));
    }

    /**
     * Upload foto profile pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = auth()->user();

        // Hapus foto lama jika ada
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Simpan foto baru
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        
        // Update database
        $user->update(['profile_photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profile berhasil diupload',
            'photo_url' => asset('storage/' . $path)
        ]);
    }

    /**
     * Hapus foto profile pengguna.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProfilePhoto()
    {
        $user = auth()->user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->update(['profile_photo' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Foto profile berhasil dihapus'
        ]);
    }
}
