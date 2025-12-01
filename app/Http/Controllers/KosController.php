<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KosController extends Controller
{
    /**
     * Tampilkan detail kos
     */
    public function detail($id)
    {
        $kos = Kos::with(['rooms' => function($q) {
            // Hanya tampilkan kamar yang tersedia
            // Kamar tersedia jika status = 'Tersedia' DAN tidak ada booking aktif
            $q->where('status', 'Tersedia')
              ->whereDoesntHave('bookings', function($query) {
                  // Tidak ada booking CONFIRMED dengan payment Verified yang masih aktif
                  $query->where('status', 'CONFIRMED')
                        ->whereHas('payment', function($paymentQuery) {
                            $paymentQuery->where('status', 'Verified');
                        })
                        ->where('tanggal_mulai', '<=', now())
                        ->where('tanggal_selesai', '>=', now());
              });
        }, 'images', 'user', 'ulasan.user'])
        ->where('status', 'Disetujui')
        ->findOrFail($id);

        // Cek apakah user login dan bisa memberikan ulasan
        $canReview = false;
        $userBooking = null;
        
        if (Auth::check()) {
            $user = Auth::user();
            $userBooking = \App\Models\Booking::where('id_kos', $kos->id)
                ->where('id_pengguna', $user->id)
                ->whereHas('payment', function($q) {
                    $q->where('status', 'Verified');
                })
                ->first();
            
            if ($userBooking) {
                $existingUlasan = \App\Models\Ulasan::where('id_pemesanan', $userBooking->id)->first();
                $canReview = !$existingUlasan;
            }
        }

        return view('kos.detail', compact('kos', 'canReview', 'userBooking'));
    }

    /**
     * Tampilkan form penyewaan
     */
    public function booking($id, Request $request)
    {
        $kos = Kos::with(['rooms' => function($q) {
            // Hanya tampilkan kamar yang tersedia
            // Kamar tersedia jika status = 'Tersedia' DAN tidak ada booking aktif
            $q->where('status', 'Tersedia')
              ->whereDoesntHave('bookings', function($query) {
                  // Tidak ada booking CONFIRMED dengan payment Verified yang masih aktif
                  $query->where('status', 'CONFIRMED')
                        ->whereHas('payment', function($paymentQuery) {
                            $paymentQuery->where('status', 'Verified');
                        })
                        ->where('tanggal_mulai', '<=', now())
                        ->where('tanggal_selesai', '>=', now());
              });
        }, 'images'])
        ->where('status', 'Disetujui')
        ->findOrFail($id);

        // Ambil kamar yang dipilih atau kamar pertama yang tersedia
        $selectedRoomId = $request->get('kamar');
        $selectedRoom = null;

        if ($selectedRoomId) {
            $selectedRoom = $kos->rooms->where('id', $selectedRoomId)->first();
        }

        if (!$selectedRoom && $kos->rooms->count() > 0) {
            $selectedRoom = $kos->rooms->first();
        }

        return view('kos.booking', compact('kos', 'selectedRoom'));
    }

    /**
     * Proses penyewaan (submit form)
     */
    public function storeBooking(Request $request, $id)
    {
        $request->validate([
            'id_kamar' => 'required|exists:kamar,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi_tahun' => 'required|integer|min:1|max:10',
        ]);

        $kos = Kos::where('status', 'Disetujui')->findOrFail($id);
        
        // Validasi kamar tersedia dan tidak ada booking aktif
        $room = Room::where('id_kos', $kos->id)
            ->where('id', $request->id_kamar)
            ->where('status', 'Tersedia')
            ->whereDoesntHave('bookings', function($query) {
                // Tidak ada booking CONFIRMED dengan payment Verified yang masih aktif
                $query->where('status', 'CONFIRMED')
                      ->whereHas('payment', function($paymentQuery) {
                          $paymentQuery->where('status', 'Verified');
                      })
                      ->where('tanggal_mulai', '<=', now())
                      ->where('tanggal_selesai', '>=', now());
            })
            ->firstOrFail();

        // Hitung total harga
        $totalHarga = $room->harga_per_tahun * $request->durasi_tahun;

        // Hitung tanggal jatuh tempo
        $tanggalJatuhTempo = date('Y-m-d', strtotime($request->tanggal_mulai . ' + ' . $request->durasi_tahun . ' years'));

        // Simpan ke session untuk halaman pembayaran (nanti)
        session([
            'booking_data' => [
                'id_kos' => $kos->id,
                'id_kamar' => $room->id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'durasi_tahun' => $request->durasi_tahun,
                'total_harga' => $totalHarga,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            ]
        ]);

        // Redirect ke halaman pembayaran
        return redirect()->route('pembayaran.index')
            ->with('success', 'Data penyewaan berhasil disimpan. Silakan lanjutkan ke pembayaran.');
    }
}

