<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\DataSiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRegistController extends Controller
{
    /**
     * Menampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function viewLogin()
    {
        // Periksa ketersediaan akun admin (role_id = 2)
        $adminAvailability = User::where('roles_id', 2)->first();

        // Jika ada admin, set variabel $checker ke 1, jika tidak, ke 0
        if ($adminAvailability != null) {
            $checker = 1;
        } else {
            $checker = 0;
        }

        // Tampilkan halaman home dengan variabel hasAdmin yang mengindikasikan ketersediaan admin
        return view('home', ['title' => 'Home', 'hasAdmin' => $checker]);
    }

    /**
     * Menampilkan halaman registrasi.
     *
     * @return \Illuminate\View\View
     */
    public function viewRegister()
    {
        return view('loginRegist/register/register', ['title' => 'Register']);
    }

    /**
     * Menangani proses registrasi pengguna baru.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validasi data yang dikirimkan oleh form registrasi
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm-password' => 'required|min:8|same:password',
            'nis' => 'required',
        ]);

        // Ambil data siswa berdasarkan NIS
        $dataSiswa = DataSiswa::get('nis');

        foreach ($dataSiswa as $key) {
            // Jika NIS cocok dengan yang diinputkan
            if ($key['nis'] == $request->nis) {
                $dataSiswa2 = DataSiswa::where('nis', $request->nis)->first();

                // Jika siswa belum memiliki akun
                if ($dataSiswa2['punya_akun'] == 0) {
                    // Ambil kelas siswa
                    $kelasSiswa = $dataSiswa2['kelas_id'];

                    // Buat data untuk user baru
                    $data = [
                        'name' => $dataSiswa2['name'],
                        'roles_id' => 4, // 4 = Siswa (Student)
                        'kelas_id' => $kelasSiswa,

                        'gambar' => null,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ];

                    // Simpan data user baru
                    User::create($data);

                    // Ambil ID user yang baru dibuat
                    $user_id = User::where('email', $request->email)->first();

                    // Update status punya akun siswa
                    $data = [
                        'user_id' => $user_id->id,
                        'punya_akun' => 1,
                    ];
                    DataSiswa::where('nis', $request->nis)->update($data);

                    // Buat data kontak untuk user
                    Contact::create(['user_id' => $user_id->id, 'no_telp' => $request->noTelp]);

                    // Redirect ke halaman login dengan pesan sukses
                    return redirect('/login')->with('register-success', 'Registrasi Berhasil');
                } else {
                    // Jika siswa sudah memiliki akun
                    return back()->with('nis-error', 'NIS (Nomor Induk Siswa) Sudah digunakan.');
                }
            }
        }

        // Jika NIS tidak ditemukan
        return back()->with('nis-error', 'NIS (Nomor Induk Siswa) Tidak ditemukan');
    }

    /**
     * Menangani proses otentikasi pengguna dengan role-based system.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        try {
            // Debug CSRF token
            \Log::info('CSRF Token from request: ' . $request->input('_token'));
            \Log::info('CSRF Token from session: ' . csrf_token());
            
            // Validasi email dan password yang dikirimkan oleh form login
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Coba untuk melakukan otentikasi dengan email dan password
            if (Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ], $request->filled('remember'))) {
                $user = Auth::user();
                $request->session()->regenerate();

                \Log::info('Login successful for user: ' . $user->email);
                
                // Redirect berdasarkan role yang sudah ada di database
                return $this->redirectBasedOnRole($user, $request);
            } else {
                \Log::warning('Login failed for email: ' . $credentials['email']);
                // Jika otentikasi gagal, kirim pesan error
                return back()->with('login-error', 'Email atau Kata Sandi salah!');
            }
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return back()->with('login-error', 'Terjadi kesalahan saat login. Silakan coba lagi.');
        }
    }

    /**
     * Konversi role string ke role ID
     *
     * @param string $role
     * @return int
     */
    private function getRoleId($role)
    {
        $roleMap = [
            'superadmin' => 1,
            'admin' => 2,
            'teacher' => 3,
            'pengajar' => 3,
            'student' => 4,
            'siswa' => 4,
        ];
        
        return $roleMap[$role] ?? 4; // Default ke student jika tidak ditemukan
    }

    /**
     * Konversi role ID ke role name
     *
     * @param int $roleId
     * @return string
     */
    private function getRoleName($roleId)
    {
        $roleMap = [
            1 => 'superadmin',
            2 => 'admin',
            3 => 'teacher',
            4 => 'student',
        ];
        
        return $roleMap[$roleId] ?? 'student'; // Default ke student jika tidak ditemukan
    }

    /**
     * Redirect user berdasarkan role mereka.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectBasedOnRole($user, $request)
    {
        // Ambil role dari database berdasarkan roles_id
        $roleId = $user->roles_id;
        $roleName = $this->getRoleName($roleId);
        
        // Log login activity
        $this->logLoginActivity($user, $roleName);
        
        // Redirect berdasarkan role ID dari database
        switch ($roleId) {
            case 1: // Super Admin
                return redirect()->route('superadmin.dashboard');
            case 2: // Admin
                return redirect()->route('admin.dashboard');
            case 3: // Teacher/Pengajar
                return redirect()->route('teacher.dashboard');
            case 4: // Student/Siswa
            default:
                return redirect()->route('student.dashboard');
        }
    }

    /**
     * Log login activity untuk audit trail.
     *
     * @param User $user
     * @param string $role
     * @return void
     */
    private function logLoginActivity($user, $role)
    {
        // Log login activity (bisa disimpan ke database atau log file)
        \Log::info('User login', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $role,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
    }

    /**
     * Menangani proses keluar (logout) pengguna.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        if (auth()) {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            // Redirect ke halaman login dengan pesan logout sukses
            return redirect('/')->with('logout-success', 'Berhasil keluar!');
        } else {
            // Jika tidak ada pengguna yang terautentikasi, redirect ke halaman login
            return redirect('/');
        }
    }

    /**
     * Menampilkan halaman lupa kata sandi.
     *
     * @return \Illuminate\View\View
     */
    public function viewForgotPassword()
    {
        return view('loginRegist/forgot-password/forgotPassword', ['title' => 'Forgot Password']);
    }

    /**
     * Menangani permintaan lupa kata sandi (Simulasi).
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar dalam sistem kami.');
        }

        // Simulasi pengiriman email reset password
        // Logika pengiriman email yang sebenarnya akan diletakkan di sini
        
        return back()->with('success', 'Instruksi reset password telah dikirim ke email Anda (Simulasi).');
    }
}
