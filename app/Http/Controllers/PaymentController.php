<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Kos;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\ImageController;
use App\Helpers\NotificationHelper;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman pembayaran
     */
    public function index(Request $request)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('beranda')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pembayaran.')
                ->with('show_login_modal', true);
        }

        // Ambil data booking dari session
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            return redirect()->route('beranda')
                ->with('error', 'Data penyewaan tidak ditemukan. Silakan lakukan penyewaan terlebih dahulu.');
        }

        // Ambil data kos dan kamar
        $kos = Kos::with('images')->findOrFail($bookingData['id_kos']);
        $room = Room::findOrFail($bookingData['id_kamar']);

        // Cari gambar kamar
        $roomImage = $kos->images->where('tipe_gambar', 'kamar')
            ->filter(function($img) use ($room) {
                return str_contains($img->url_gambar, 'kamar-' . $room->nomor_kamar . '.png');
            })
            ->first();
        $roomImageUrl = $roomImage ? $roomImage->url : 'https://via.placeholder.com/400x300?text=Kamar+' . $room->nomor_kamar;

        // Format tanggal untuk display
        $tanggalMulai = date('d/m/Y', strtotime($bookingData['tanggal_mulai']));
        $tanggalJatuhTempo = date('d/m/Y', strtotime($bookingData['tanggal_jatuh_tempo']));

        return view('payment.index', compact('bookingData', 'kos', 'room', 'roomImageUrl', 'tanggalMulai', 'tanggalJatuhTempo'));
    }

    /**
     * Proses konfirmasi pembayaran
     */
    public function store(Request $request)
    {
        // Ambil data booking dari session
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            return redirect()->route('beranda')
                ->with('error', 'Data penyewaan tidak ditemukan.');
        }

        // Validasi form pembayaran
        $request->validate([
            'metode_pembayaran' => 'required|in:Transfer Bank,E-Wallet',
            'nama_bank_pengirim' => 'required|string|max:255',
            'nomor_rekening_pengirim' => 'required|string|max:50',
            'nama_pengirim' => 'required|string|max:255',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format file harus jpeg, png, jpg, atau gif.',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('beranda', ['modal' => 'login'])
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pembayaran.');
        }

        // Ambil data kos dan kamar
        $kos = Kos::findOrFail($bookingData['id_kos']);
        $room = Room::findOrFail($bookingData['id_kamar']);

        // Buat booking/pemesanan terlebih dahulu
        $bookingId = 'BK' . strtoupper(Str::random(8));
        $booking = Booking::create([
            'id' => Str::uuid(),
            'id_pemesanan' => $bookingId,
            'id_pengguna' => Auth::id(),
            'id_kamar' => $room->id,
            'id_kos' => $kos->id,
            'tanggal_mulai' => $bookingData['tanggal_mulai'],
            'durasi_tahun' => $bookingData['durasi_tahun'],
            'tanggal_selesai' => $bookingData['tanggal_jatuh_tempo'],
            'total_harga' => $bookingData['total_harga'],
            'status' => 'PENDING',
        ]);

        // Upload bukti pembayaran
        if (!$request->hasFile('bukti_pembayaran')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['bukti_pembayaran' => 'File bukti pembayaran tidak ditemukan.']);
        }

        $buktiGambar = $request->file('bukti_pembayaran');
        
        if (!$buktiGambar->isValid()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['bukti_pembayaran' => 'File bukti pembayaran tidak valid atau rusak.']);
        }

        try {
            $filename = ImageController::uploadImage($buktiGambar, 'payment-proofs', 'payment');
            
            if (!$filename) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['bukti_pembayaran' => 'Gagal menyimpan file bukti pembayaran.']);
            }
        } catch (\Exception $e) {
            \Log::error('Payment upload error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['bukti_pembayaran' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()]);
        }

        // Buat record pembayaran
        $payment = Payment::create([
            'id' => Str::uuid(),
            'id_pemesanan' => $booking->id,
            'id_pengguna' => Auth::id(),
            'jumlah' => $bookingData['total_harga'],
            'metode_pembayaran' => $request->metode_pembayaran,
            'nama_bank_pengirim' => $request->nama_bank_pengirim,
            'nomor_rekening_pengirim' => $request->nomor_rekening_pengirim,
            'nama_pengirim' => $request->nama_pengirim,
            'url_bukti_gambar' => $filename,
            'status' => 'Pending',
        ]);

        // Buat notifikasi untuk user
        NotificationHelper::create(
            Auth::id(),
            'Pembayaran Dikirim',
            'Bukti pembayaran Anda telah dikirim. Menunggu verifikasi dari admin.',
            'payment',
            $payment->id
        );

        // Buat notifikasi untuk semua admin
        NotificationHelper::notifyAdmins(
            'Pembayaran Baru Menunggu Verifikasi',
            'Pembayaran dari ' . Auth::user()->nama . ' untuk ' . $kos->nama . ' (Kamar ' . $room->nomor_kamar . ') menunggu verifikasi.',
            'payment',
            $payment->id
        );

        // Hapus session booking data
        session()->forget('booking_data');

        // Redirect ke beranda sesuai role user
        $user = Auth::user();
        if ($user && ($user->role === 'pencari' || $user->peran === 'pencari')) {
            return redirect()->route('pencari.beranda')
                ->with('show_payment_notification', true)
                ->with('payment_message', 'Bukti pembayaran Anda telah dikirim. Menunggu verifikasi dari admin.');
        }

        return redirect()->route('beranda')
            ->with('show_payment_notification', true)
            ->with('payment_message', 'Bukti pembayaran Anda telah dikirim. Menunggu verifikasi dari admin.');
    }
}

