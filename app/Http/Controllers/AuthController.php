<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:pencari,pemilik',
        ]);

        // Cek jika sudah ada user yang login dengan role berbeda di session ini
        // Logout dulu sebelum proses login baru untuk mencegah login bersamaan dengan role berbeda
        if (Auth::check()) {
            $currentActiveRole = session('active_role');
            $currentUser = Auth::user();
            
            // Jika ada active_role yang berbeda dari role yang akan di-login, logout dulu
            if ($currentActiveRole && $currentActiveRole !== $request->role) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->kata_sandi)) {
            return redirect()->route('beranda', ['modal' => 'login', 'role' => $request->role])
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email', 'role'));
        }

        // Cek apakah role user sesuai dengan role yang dipilih
        if ($user->role !== $request->role && $user->role !== 'admin') {
            return redirect()->route('beranda', ['modal' => 'login', 'role' => $request->role])
                ->withErrors(['email' => 'Email ini tidak terdaftar sebagai ' . ($request->role === 'pencari' ? 'Pencari Kos' : 'Pemilik Kos') . '.'])
                ->withInput($request->only('email', 'role'));
        }

        // Pastikan tidak ada user lain yang login dengan role berbeda di session ini
        if (Auth::check()) {
            $currentActiveRole = session('active_role');
            if ($currentActiveRole && $currentActiveRole !== $user->role) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        if ($user->status !== 'Aktif') {
            return redirect()->route('beranda', ['modal' => 'login', 'role' => $request->role])
                ->withErrors(['email' => 'Akun Anda tidak aktif.'])
                ->withInput($request->only('email', 'role'));
        }

        // Cek email verification
        if (!$user->hasVerifiedEmail()) {
            Auth::login($user, $request->remember ?? false);
            // Simpan active_role untuk verifikasi email
            session(['active_role' => $user->role]);
            return redirect()->route('verification.notice')
                ->with('warning', 'Silakan verifikasi email Anda terlebih dahulu untuk melanjutkan.');
        }

        Auth::login($user, $request->remember ?? false);
        
        // Simpan active_role di session untuk mencegah login dengan role berbeda
        session(['active_role' => $user->role]);

        if (in_array($user->role, ['pencari', 'pemilik'])) {
            session()->flash('show_welcome_message', true);
        }

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        } else {
            return redirect()->route('pencari.beranda');
        }
    }

    /**
     * Tampilkan form register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        try {
            // Validasi dasar - sama seperti login
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:pencari,pemilik',
            ]);

            // Validasi tambahan untuk pemilik
            if ($request->role === 'pemilik') {
                $request->validate([
                    'bank_name' => 'required|string|max:255',
                    'account_number' => 'required|string|max:255',
                ]);
            }
        } catch (ValidationException $e) {
            // Jika validasi gagal, redirect dengan query string
            return redirect()->route('beranda', ['modal' => 'register', 'role' => $request->role])
                ->withErrors($e->errors())
                ->withInput($request->all());
        }

        // Buat data user
        $userData = [
            'nama' => $request->name,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->password),
            'peran' => $request->role,
            'status' => 'Aktif',
        ];

        // Tambahkan field bank untuk pemilik
        if ($request->role === 'pemilik') {
            $userData['nama_bank'] = $request->bank_name;
            $userData['nomor_rekening'] = $request->account_number;
        }

        // Cek jika sudah ada role berbeda yang login di session ini
        if (Auth::check()) {
            $currentActiveRole = session('active_role');
            if ($currentActiveRole && $currentActiveRole !== $request->role) {
                // Logout dulu jika role berbeda
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        // Buat user
        $user = User::create($userData);
        
        // Kirim email verification
        $user->sendEmailVerificationNotification();
        
        // Login user (tapi akan dicek email verification saat akses fitur tertentu)
        Auth::login($user);
        
        // Simpan active_role di session untuk mencegah login dengan role berbeda
        session(['active_role' => $user->role]);

        // Buat notifikasi untuk admin jika user mendaftar sebagai pemilik kos
        if ($request->role === 'pemilik') {
            \App\Helpers\NotificationHelper::notifyAdmins(
                'Pemilik Kos Baru Mendaftar',
                'Pemilik kos baru "' . $user->nama . '" (' . $user->email . ') telah mendaftar.',
                'system',
                $user->id
            );
        }

        // Redirect ke halaman verifikasi email
        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        try {
            // Clear active_role dari session sebelum logout
            $request->session()->forget('active_role');
            
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

            return redirect()->route('beranda')
                ->with('success', 'Anda telah berhasil logout.');
        } catch (\Exception $e) {
            // Jika terjadi error saat logout (misalnya session sudah invalid),
            // tetap redirect ke beranda
            try {
                Auth::logout();
            } catch (\Exception $e2) {
                // Ignore jika sudah logout
            }
            
            return redirect()->route('beranda')
                ->with('info', 'Anda telah logout.');
        }
    }

    /**
     * Redirect ke Google OAuth untuk login/register
     */
    public function redirectToGoogle(Request $request, Socialite $socialite)
    {
        try {
            // Validasi role yang dipilih
            $request->validate([
                'role' => 'required|in:pencari,pemilik',
            ]);

            // Simpan role di session untuk digunakan saat callback
            session(['google_oauth_role' => $request->role]);

            // Cek apakah Google OAuth credentials sudah di-setup
            if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
                return redirect()->route('beranda', ['modal' => $request->has('register') ? 'register' : 'login', 'role' => $request->role])
                    ->withErrors(['email' => 'Google OAuth belum dikonfigurasi. Silakan hubungi administrator.']);
            }

            // Redirect ke Google OAuth
            return $socialite->driver('google')
                ->scopes(['email', 'profile'])
                ->redirect();
        } catch (\Exception $e) {
            // Jika terjadi error, redirect kembali dengan pesan error
            return redirect()->route('beranda', ['modal' => $request->has('register') ? 'register' : 'login', 'role' => $request->role ?? 'pencari'])
                ->withErrors(['email' => 'Gagal mengarahkan ke Google. Silakan coba lagi atau hubungi administrator.']);
        }
    }

    /**
     * Handle callback dari Google OAuth
     */
    public function handleGoogleCallback(Socialite $socialite)
    {
        try {
            // Ambil data user dari Google
            $googleUser = $socialite->driver('google')->user();

            // Ambil role dari session
            $role = session('google_oauth_role', 'pencari');
            session()->forget('google_oauth_role');

            // Cek apakah user sudah terdaftar
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Cek jika sudah ada role berbeda yang login di session ini
                if (Auth::check()) {
                    $currentActiveRole = session('active_role');
                    if ($currentActiveRole && $currentActiveRole !== $user->role) {
                        // Logout dulu jika role berbeda
                        Auth::logout();
                        request()->session()->invalidate();
                        request()->session()->regenerateToken();
                    }
                }

                // User sudah terdaftar, lakukan login
                // Cek apakah role sesuai
                if ($user->role !== $role && $user->role !== 'admin') {
                    return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                        ->withErrors(['email' => 'Email ini sudah terdaftar sebagai ' . ($user->role === 'pencari' ? 'Pencari Kos' : 'Pemilik Kos') . '.']);
                }

                // Cek status akun
                if ($user->status !== 'Aktif') {
                    return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                        ->withErrors(['email' => 'Akun Anda tidak aktif.']);
                }

                // Update foto profil jika ada dari Google
                if ($googleUser->getAvatar() && !$user->profile_photo_url) {
                    $user->update(['profile_photo_url' => $googleUser->getAvatar()]);
                }

                // Login user
                Auth::login($user, true);
                
                // Simpan active_role di session untuk mencegah login dengan role berbeda
                session(['active_role' => $user->role]);

                // Redirect berdasarkan role
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Berhasil login dengan Google!');
                } elseif ($user->role === 'pemilik') {
                    return redirect()->route('pemilik.dashboard')
                        ->with('success', 'Berhasil login dengan Google!');
                } else {
                    return redirect()->route('pencari.beranda')
                        ->with('success', 'Berhasil login dengan Google!');
                }
            } else {
                // User belum terdaftar, buat akun baru
                $userData = [
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'kata_sandi' => Hash::make(uniqid('google_', true)), // Password random karena login via Google
                    'peran' => $role,
                    'status' => 'Aktif',
                    'email_verified_at' => now(), // Email dari Google sudah verified
                    'profile_photo_url' => $googleUser->getAvatar(),
                ];

                // Tambahkan field bank untuk pemilik (kosongkan dulu, bisa diisi nanti)
                if ($role === 'pemilik') {
                    $userData['nama_bank'] = null;
                    $userData['nomor_rekening'] = null;
                }

                // Buat user baru
                $user = User::create($userData);

                // Cek jika sudah ada role berbeda yang login di session ini
                if (Auth::check()) {
                    $currentActiveRole = session('active_role');
                    if ($currentActiveRole && $currentActiveRole !== $user->role) {
                        // Logout dulu jika role berbeda
                        Auth::logout();
                        request()->session()->invalidate();
                        request()->session()->regenerateToken();
                    }
                }

                // Login user
                Auth::login($user, true);
                
                // Simpan active_role di session untuk mencegah login dengan role berbeda
                session(['active_role' => $user->role]);

                // Buat notifikasi untuk admin jika user mendaftar sebagai pemilik kos
                if ($role === 'pemilik') {
                    \App\Helpers\NotificationHelper::notifyAdmins(
                        'Pemilik Kos Baru Mendaftar',
                        'Pemilik kos baru "' . $user->nama . '" (' . $user->email . ') telah mendaftar via Google.',
                        'system',
                        $user->id
                    );
                }

                // Redirect berdasarkan role
                if ($role === 'pemilik') {
                    return redirect()->route('pemilik.dashboard')
                        ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->nama . '!');
                } else {
                    return redirect()->route('pencari.beranda')
                        ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->nama . '!');
                }
            }
        } catch (\Exception $e) {
            // Jika terjadi error, redirect kembali ke halaman login
            return redirect()->route('beranda', ['modal' => 'login'])
                ->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }
    }
}

