<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PencariController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes publik
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/tentang', [BerandaController::class, 'tentang'])->name('tentang');

// Routes detail kos dan penyewaan
Route::get('/kos/{id}', [App\Http\Controllers\KosController::class, 'detail'])->name('kos.detail');
Route::get('/kos/{id}/booking', [App\Http\Controllers\KosController::class, 'booking'])->name('kos.booking');
Route::post('/kos/{id}/booking', [App\Http\Controllers\KosController::class, 'storeBooking'])->name('kos.booking.store');

// Routes pembayaran (perlu login)
Route::middleware('auth')->group(function () {
    Route::get('/pembayaran', [App\Http\Controllers\PaymentController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran', [App\Http\Controllers\PaymentController::class, 'store'])->name('pembayaran.store');
});


// Routes autentikasi
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes Google OAuth
Route::get('/auth/google', [App\Http\Controllers\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (Request $request) {
        $user = \App\Models\User::findOrFail($request->route('id'));
        
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403);
        }
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('beranda')->with('info', 'Email sudah diverifikasi.');
        }
        
        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }
        
        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Email berhasil diverifikasi!');
        } elseif ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard')->with('success', 'Email berhasil diverifikasi!');
        } else {
            return redirect()->route('pencari.beranda')->with('success', 'Email berhasil diverifikasi!');
        }
    })->middleware(['signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi telah dikirim ke email Anda!');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Routes profile (terautentikasi)
Route::middleware('auth')->group(function () {
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/riwayat', [App\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat.index');

    // Routes ulasan
    Route::get('/kos/{id}/ulasan/create', [App\Http\Controllers\UlasanController::class, 'create'])->name('ulasan.create');
    Route::post('/kos/{id}/ulasan', [App\Http\Controllers\UlasanController::class, 'store'])->name('ulasan.store');


});


// Routes pencari (terautentikasi)
Route::middleware(['auth', 'auth.role:pencari'])->prefix('pencari')->name('pencari.')->group(function () {
    Route::get('/beranda', [PencariController::class, 'beranda'])->name('beranda');
});

// Routes pemilik (terautentikasi)
Route::middleware(['auth', 'auth.role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PemilikController::class, 'dashboard'])->name('dashboard');
    Route::get('/properti', [App\Http\Controllers\PemilikController::class, 'properti'])->name('properti');
    Route::get('/pemesanan', [App\Http\Controllers\PemilikController::class, 'pemesanan'])->name('pemesanan');
    Route::get('/laporan', [App\Http\Controllers\PemilikController::class, 'laporan'])->name('laporan');
    Route::get('/kos/create', [App\Http\Controllers\PemilikController::class, 'create'])->name('kos.create');
    Route::get('/kos/{id}/edit', [App\Http\Controllers\PemilikController::class, 'edit'])->name('kos.edit');
    Route::post('/kos', [App\Http\Controllers\PemilikController::class, 'store'])->name('kos.store');
    Route::put('/kos/{id}', [App\Http\Controllers\PemilikController::class, 'update'])->name('kos.update');
});

// Routes admin (terautentikasi) - placeholder
Route::middleware(['auth', 'auth.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/verifikasi-kos', [App\Http\Controllers\Admin\AdminVerifikasiKosController::class, 'index'])->name('verifikasi-kos');
    Route::get('/verifikasi-kos/{id}/detail', [App\Http\Controllers\Admin\AdminVerifikasiKosController::class, 'detail'])->name('verifikasi-kos.detail');
    Route::post('/verifikasi-kos/{id}/approve', [App\Http\Controllers\Admin\AdminVerifikasiKosController::class, 'approve'])->name('verifikasi-kos.approve');
    Route::post('/verifikasi-kos/{id}/reject', [App\Http\Controllers\Admin\AdminVerifikasiKosController::class, 'reject'])->name('verifikasi-kos.reject');
    Route::get('/verifikasi-pembayaran', [App\Http\Controllers\Admin\AdminVerifikasiPembayaranController::class, 'index'])->name('verifikasi-pembayaran');
    Route::post('/verifikasi-pembayaran/{id}/approve', [App\Http\Controllers\Admin\AdminVerifikasiPembayaranController::class, 'approve'])->name('verifikasi-pembayaran.approve');
    Route::post('/verifikasi-pembayaran/{id}/reject', [App\Http\Controllers\Admin\AdminVerifikasiPembayaranController::class, 'reject'])->name('verifikasi-pembayaran.reject');
    Route::get('/verifikasi-pembayaran/{id}/detail', [App\Http\Controllers\Admin\AdminVerifikasiPembayaranController::class, 'detail'])->name('verifikasi-pembayaran.detail');
    Route::get('/manajemen-pengguna', [App\Http\Controllers\Admin\AdminManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna');
    Route::put('/manajemen-pengguna/{id}/status', [App\Http\Controllers\Admin\AdminManajemenPenggunaController::class, 'updateStatus'])->name('manajemen-pengguna.update-status');
    Route::delete('/manajemen-pengguna/{id}', [App\Http\Controllers\Admin\AdminManajemenPenggunaController::class, 'destroy'])->name('manajemen-pengguna.destroy');
});


