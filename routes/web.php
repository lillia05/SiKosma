<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PencariController;

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


// Routes autentikasi
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes profile (terautentikasi)
Route::middleware('auth')->group(function () {
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Routes pencari (terautentikasi)
Route::middleware(['auth', 'auth.role:pencari'])->prefix('pencari')->name('pencari.')->group(function () {
    Route::get('/beranda', [PencariController::class, 'beranda'])->name('beranda');
});

// Routes pemilik (terautentikasi) - placeholder
Route::middleware(['auth', 'auth.role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', function() {
        return redirect()->route('beranda')->with('info', 'Dashboard Pemilik akan segera hadir!');
    })->name('dashboard');
});

// Routes admin (terautentikasi) - placeholder
Route::middleware(['auth', 'auth.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() {
        return redirect()->route('beranda')->with('info', 'Dashboard Admin akan segera hadir!');
    })->name('dashboard');
});