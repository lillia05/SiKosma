<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile (sebagai modal)
     */
    public function index()
    {
        // Redirect ke beranda dengan modal profile
        return redirect()->route('beranda', ['modal' => 'profile']);
    }

    /**
     * Update profile user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bank_name' => $user->peran === 'pemilik' ? 'required|string|max:255' : 'nullable',
            'account_number' => $user->peran === 'pemilik' ? 'required|string|max:255' : 'nullable',
        ]);

        // Update data user
        $user->nama = $request->name;
        $user->email = $request->email;
        $user->telepon = $request->phone;
        $user->alamat = $request->address;
        $user->kota = $request->city;

        // Update bank info untuk pemilik
        if ($user->peran === 'pemilik') {
            $user->nama_bank = $request->bank_name;
            $user->nomor_rekening = $request->account_number;
        }

        // Handle upload foto profile
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                ImageController::deleteImage($user->foto_profil, 'profile-photos');
            }

            // Upload foto baru
            $filename = ImageController::uploadImage($request->file('profile_photo'), 'profile-photos', 'profile');
            $user->foto_profil = $filename;
        }

        $user->save();

        return redirect()->route('beranda', ['modal' => 'profile'])->with('success', 'Profile berhasil diperbarui!');
    }
}
