<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        if ($user->status !== 'Aktif') {
            return redirect()->route('beranda', ['modal' => 'login', 'role' => $request->role])
                ->withErrors(['email' => 'Akun Anda tidak aktif.'])
                ->withInput($request->only('email', 'role'));
        }

        Auth::login($user, $request->remember ?? false);

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

        // Buat user dan login - sama seperti method login
        $user = User::create($userData);
        
        Auth::login($user);

        // Buat notifikasi untuk admin jika user mendaftar sebagai pemilik kos
        if ($request->role === 'pemilik') {
            \App\Helpers\NotificationHelper::notifyAdmins(
                'Pemilik Kos Baru Mendaftar',
                'Pemilik kos baru "' . $user->nama . '" (' . $user->email . ') telah mendaftar.',
                'system',
                $user->id
            );
        }

        if (in_array($request->role, ['pencari', 'pemilik'])) {
            session()->flash('show_welcome_message', true);
        }

        // Redirect berdasarkan role - sama seperti login
        if ($request->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard')->with('success', 'Registrasi berhasil!');
        } else {
            return redirect()->route('pencari.beranda')->with('success', 'Registrasi berhasil!');
        }
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }
}

