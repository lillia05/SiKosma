<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\Factory as Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle(Request $request, Socialite $socialite)
    {
        try {
            // Validasi role yang dipilih
            $request->validate([
                'role' => 'required|in:pencari,pemilik',
            ]);

            // Simpan role di cache dengan key unik berdasarkan session ID
            // Cache lebih reliable daripada session untuk OAuth flow
            $cacheKey = 'google_oauth_role_' . session()->getId();
            Cache::put($cacheKey, $request->role, 600); // Simpan selama 10 menit
            
            // Juga simpan di session sebagai backup
            session(['google_oauth_role' => $request->role]);
            session()->save();
            
            // Log untuk debugging
            \Log::info('Google OAuth Redirect - Role: ' . $request->role . ', Session ID: ' . session()->getId() . ', Cache Key: ' . $cacheKey);

            // Cek apakah Google OAuth credentials sudah di-setup
            if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
                return redirect()->route('beranda', ['modal' => $request->has('register') ? 'register' : 'login', 'role' => $request->role])
                    ->withErrors(['email' => 'Google OAuth belum dikonfigurasi. Silakan hubungi administrator.']);
            }

            // Redirect ke Google OAuth dengan stateless() untuk menghindari masalah session state
            // stateless() akan menghilangkan CSRF state validation, tapi lebih reliable untuk OAuth
            return $socialite->driver('google')
                ->stateless()
                ->scopes(['email', 'profile'])
                ->redirect();
        } catch (\Exception $e) {
            // Jika terjadi error, redirect kembali dengan pesan error
            \Log::error('Google OAuth redirect error: ' . $e->getMessage());
            return redirect()->route('beranda', ['modal' => $request->has('register') ? 'register' : 'login', 'role' => $request->role ?? 'pencari'])
                ->withErrors(['email' => 'Gagal mengarahkan ke Google. Silakan coba lagi atau hubungi administrator.']);
        }
    }

    /**
     * Handle callback dari Google OAuth
     */
    public function handleGoogleCallback(Request $request, Socialite $socialite)
    {
        try {
            // Log semua parameter dari Google untuk debugging
            \Log::info('Google OAuth Callback - Request parameters: ' . json_encode($request->all()));
            \Log::info('Google OAuth Callback - State: ' . ($request->state ?? 'null'));
            \Log::info('Google OAuth Callback - Code: ' . ($request->code ? 'present' : 'null'));
            \Log::info('Google OAuth Callback - Session role: ' . (session('google_oauth_role') ?? 'null'));
            
            // Cek apakah ada error dari Google
            if ($request->has('error')) {
                \Log::error('Google OAuth error: ' . $request->error);
                // Ambil role dari session jika ada
                $role = session('google_oauth_role', 'pencari');
                return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                    ->withErrors(['email' => 'Gagal login dengan Google: ' . ($request->error_description ?? 'Akses ditolak')]);
            }

            // Ambil role dari cache terlebih dahulu (lebih reliable untuk OAuth)
            $cacheKey = 'google_oauth_role_' . session()->getId();
            $role = Cache::get($cacheKey);
            
            // Jika tidak ada di cache, coba ambil dari session (backup)
            if (!$role) {
                $role = session('google_oauth_role');
                \Log::info('Google OAuth: Role dari session (backup): ' . ($role ?? 'null'));
            } else {
                \Log::info('Google OAuth: Role dari cache: ' . $role);
            }
            
            // Jika masih tidak ada role, default ke pencari
            if (!$role || !in_array($role, ['pencari', 'pemilik'])) {
                $role = 'pencari';
                \Log::warning('Google OAuth: Role tidak ditemukan, menggunakan default: pencari');
            }

            // Ambil data user dari Google dengan error handling yang lebih baik
            \Log::info('Google OAuth: Mencoba mengambil user dari Google...');
            \Log::info('Google OAuth: Session ID saat callback: ' . session()->getId());
            \Log::info('Google OAuth: Session data: ' . json_encode(session()->all()));
            
            try {
                // Gunakan stateless() untuk menghindari masalah session state
                $googleUser = $socialite->driver('google')->stateless()->user();
                \Log::info('Google OAuth: Berhasil mengambil user dari Google');
            } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
                \Log::error('Google OAuth InvalidStateException: ' . $e->getMessage());
                \Log::error('Google OAuth InvalidStateException - Full exception: ' . $e->getTraceAsString());
                // Re-throw untuk ditangani di catch block
                throw $e;
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $body = $response ? $response->getBody()->getContents() : 'Unknown error';
                \Log::error('Google OAuth Guzzle ClientException: ' . $e->getMessage());
                \Log::error('Response body: ' . $body);
                throw $e;
            } catch (\Exception $e) {
                \Log::error('Error saat mengambil user dari Google: ' . $e->getMessage());
                \Log::error('Error class: ' . get_class($e));
                \Log::error('Error code: ' . ($e->getCode() ?? 'N/A'));
                throw $e;
            }
            
            // Log untuk debugging
            \Log::info('Google OAuth Callback - Email: ' . $googleUser->getEmail() . ', Google ID: ' . $googleUser->getId() . ', Role: ' . $role);
            
            // Hapus role dari cache dan session setelah digunakan
            $cacheKey = 'google_oauth_role_' . session()->getId();
            Cache::forget($cacheKey);
            session()->forget('google_oauth_role');

            // Cek jika sudah ada user yang login dengan role berbeda di session ini
            // Logout dulu sebelum proses login baru untuk mencegah login bersamaan dengan role berbeda
            if (Auth::check()) {
                $currentActiveRole = session('active_role');
                // Jika ada active_role yang berbeda dari role yang akan di-login, logout dulu
                if ($currentActiveRole && $currentActiveRole !== $role) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }
            }

            // Cek apakah user sudah terdaftar berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Pastikan tidak ada user lain yang login dengan role berbeda
                if (Auth::check()) {
                    $currentActiveRole = session('active_role');
                    if ($currentActiveRole && $currentActiveRole !== $user->peran) {
                        // Logout dulu jika role berbeda
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                    }
                }

                // User sudah terdaftar, lakukan login
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                
                // Cek apakah role sesuai
                if ($user->peran !== $role && $user->peran !== 'admin') {
                    return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                        ->withErrors(['email' => 'Email ini sudah terdaftar sebagai ' . ($user->peran === 'pencari' ? 'Pencari Kos' : 'Pemilik Kos') . '.']);
                }

                // Cek status akun
                if ($user->status !== 'Aktif') {
                    return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                        ->withErrors(['email' => 'Akun Anda tidak aktif.']);
                }

                // Update foto profil jika ada dari Google
                if ($googleUser->getAvatar() && !$user->foto_profil) {
                    $user->update(['foto_profil' => $googleUser->getAvatar()]);
                }

                // Login user
                Auth::login($user, true);
                
                // Simpan active_role di session untuk mencegah login dengan role berbeda
                session(['active_role' => $user->peran]);

                // Redirect berdasarkan role
                if ($user->peran === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Berhasil login dengan Google!');
                } elseif ($user->peran === 'pemilik') {
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
                    'foto_profil' => $googleUser->getAvatar(),
                    'google_id' => $googleUser->getId(), // Simpan Google ID
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
                    if ($currentActiveRole && $currentActiveRole !== $user->peran) {
                        // Logout dulu jika role berbeda
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                    }
                }

                // Login user
                Auth::login($user, true);
                
                // Simpan active_role di session untuk mencegah login dengan role berbeda
                session(['active_role' => $user->peran]);

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
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Error karena state tidak valid (biasanya karena session expired)
            \Log::error('Google OAuth InvalidStateException: ' . $e->getMessage());
            \Log::error('Google OAuth InvalidStateException - Session ID: ' . session()->getId());
            
            // Coba ambil role dari cache atau session
            $cacheKey = 'google_oauth_role_' . session()->getId();
            $role = Cache::get($cacheKey) ?? session('google_oauth_role', 'pencari');
            
            // Hapus cache setelah digunakan
            Cache::forget($cacheKey);
            session()->forget('google_oauth_role');
            
            return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                ->withErrors(['email' => 'Session expired. Silakan coba login dengan Google lagi.']);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Error dari HTTP request ke Google
            $response = $e->getResponse();
            $body = $response ? $response->getBody()->getContents() : 'Unknown error';
            \Log::error('Google OAuth HTTP error: ' . $e->getMessage());
            \Log::error('Response body: ' . $body);
            // Ambil role dari session jika masih ada
            $role = session('google_oauth_role', 'pencari');
            $errorMsg = 'Gagal mengambil data dari Google. ';
            if (str_contains($body, 'redirect_uri_mismatch') || str_contains($e->getMessage(), 'redirect_uri_mismatch')) {
                $errorMsg .= 'Redirect URI tidak sesuai. Pastikan redirect URI di Google Cloud Console adalah: ' . config('services.google.redirect', 'http://localhost:8000/auth/google/callback');
            } else {
                $errorMsg .= 'Silakan coba lagi atau hubungi administrator.';
            }
            return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                ->withErrors(['email' => $errorMsg]);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Google OAuth callback error: ' . $e->getMessage());
            \Log::error('Error class: ' . get_class($e));
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Ambil role dari session jika masih ada
            $role = session('google_oauth_role', 'pencari');
            
            // Jika terjadi error, redirect kembali ke halaman login dengan pesan yang lebih jelas
            $errorMessage = 'Gagal login dengan Google. ';
            $errorMsg = $e->getMessage() ?: '';
            if (str_contains($errorMsg, 'redirect_uri_mismatch') || str_contains($errorMsg, 'redirect_uri')) {
                $errorMessage .= 'Redirect URI tidak sesuai. Pastikan redirect URI di Google Cloud Console adalah: ' . config('services.google.redirect', 'http://localhost:8000/auth/google/callback');
            } elseif (str_contains($errorMsg, 'invalid_grant') || str_contains($errorMsg, 'code')) {
                $errorMessage .= 'Kode autentikasi tidak valid atau sudah kedaluwarsa. Silakan coba lagi.';
            } else {
                $errorMessage .= $errorMsg ?: 'Silakan coba lagi atau hubungi administrator.';
            }
            
            return redirect()->route('beranda', ['modal' => 'login', 'role' => $role])
                ->withErrors(['email' => $errorMessage]);
        }
    }
}
