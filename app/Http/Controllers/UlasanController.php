<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Kos;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /**
     * Tampilkan form untuk membuat ulasan baru
     */
    public function create($kosId)
    {
        $kos = Kos::findOrFail($kosId);
        $user = Auth::user();

        // Cek apakah user punya booking dengan payment verified
        $booking = Booking::where('id_kos', $kosId)
            ->where('id_pengguna', $user->id)
            ->whereHas('payment', function($q) {
                $q->where('status', 'Verified');
            })
            ->first();

        if (!$booking) {
            return redirect()->route('kos.detail', $kosId)
                ->with('error', 'Anda harus sudah melakukan pembayaran yang terverifikasi untuk memberikan ulasan');
        }

        // Cek apakah sudah ada ulasan untuk booking ini
        $existingUlasan = Ulasan::where('id_pemesanan', $booking->id)->first();
        if ($existingUlasan) {
            return redirect()->route('kos.detail', $kosId)
                ->with('error', 'Anda sudah memberikan ulasan untuk pemesanan ini');
        }

        return view('ulasan.create', compact('kos', 'booking'));
    }

    /**
     * Simpan ulasan baru
     */
    public function store(Request $request, $kosId)
    {
        $user = Auth::user();

        // Validasi
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
            'id_pemesanan' => 'required|uuid|exists:pemesanan,id',
        ]);

        // Validasi: user harus punya booking dengan payment verified
        $booking = Booking::where('id', $request->id_pemesanan)
            ->where('id_kos', $kosId)
            ->where('id_pengguna', $user->id)
            ->whereHas('payment', function($q) {
                $q->where('status', 'Verified');
            })
            ->first();

        if (!$booking) {
            return redirect()->route('kos.detail', $kosId)
                ->with('error', 'Anda harus sudah melakukan pembayaran yang terverifikasi untuk memberikan ulasan');
        }

        // Cek apakah sudah ada ulasan untuk booking ini
        $existingUlasan = Ulasan::where('id_pemesanan', $booking->id)->first();
        if ($existingUlasan) {
            return redirect()->route('kos.detail', $kosId)
                ->with('error', 'Anda sudah memberikan ulasan untuk pemesanan ini');
        }

        // Buat ulasan
        $ulasan = Ulasan::create([
            'id_kos' => $kosId,
            'id_pengguna' => $user->id,
            'id_pemesanan' => $booking->id,
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        // Update rating kos
        $kos = Kos::findOrFail($kosId);
        $kos->updateRating();

        return redirect()->route('kos.detail', $kosId)
            ->with('success', 'Ulasan berhasil ditambahkan! Terima kasih atas ulasan Anda.');
    }
}
