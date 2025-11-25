<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Tampilkan halaman riwayat pemesanan
     */
    public function index(Request $request)
    {
        // Ambil semua booking user yang login dengan relasi payment, room, kos, dan ulasan
        $bookings = Booking::where('id_pengguna', Auth::id())
            ->with(['payment', 'room', 'kos', 'ulasan'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('riwayat.index', compact('bookings'));
    }
}

