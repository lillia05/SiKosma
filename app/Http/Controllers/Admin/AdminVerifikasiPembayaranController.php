<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;

class AdminVerifikasiPembayaranController extends Controller
{
    /**
     * Tampilkan halaman verifikasi pembayaran
     */
    public function index(Request $request)
    {
        $query = Payment::with(['booking.kos', 'booking.room', 'user'])
            ->orderBy('created_at', 'desc');

        // Pencarian berdasarkan nama kos
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            $query->whereHas('booking.kos', function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $payments = $query->get();

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.verifikasi-pembayaran-table', compact('payments'))->render(),
            ]);
        }

        return view('admin.verifikasi-pembayaran', compact('payments'));
    }

    /**
     * Setujui pembayaran
     */
    public function approve($id)
    {
        $payment = Payment::with(['booking', 'user'])->findOrFail($id);
        
        // Update status pembayaran
        $payment->status = 'Verified';
        $payment->save();

        // Update status booking menjadi CONFIRMED
        if ($payment->booking) {
            $payment->booking->status = 'CONFIRMED';
            $payment->booking->save();
            
            // Update status kamar menjadi Terisi
            if ($payment->booking->room) {
                $payment->booking->room->status = 'Terisi';
                $payment->booking->room->save();
            }
        }

        // Buat notifikasi untuk user (pencari)
        NotificationHelper::create(
            $payment->id_pengguna,
            'Pembayaran Disetujui',
            'Pembayaran Anda untuk ' . ($payment->booking->kos->nama ?? 'kos') . ' telah disetujui.',
            'payment',
            $payment->id
        );

        // Buat notifikasi untuk pemilik kos
        if ($payment->booking && $payment->booking->kos && $payment->booking->kos->id_pengguna) {
            NotificationHelper::create(
                $payment->booking->kos->id_pengguna,
                'Pembayaran Disetujui untuk ' . $payment->booking->kos->nama,
                'Pembayaran dari ' . ($payment->user->nama ?? 'penyewa') . ' untuk ' . $payment->booking->kos->nama . ' telah disetujui.',
                'payment',
                $payment->id
            );
        }

        // Mark notifikasi admin terkait pembayaran ini sebagai sudah dibaca
        // Cari berdasarkan id_terkait yang sama dengan payment id
        $adminIds = User::where('peran', 'admin')->pluck('id');
        Notification::whereIn('id_pengguna', $adminIds)
            ->where('tipe', 'payment')
            ->where('id_terkait', $payment->id)
            ->update(['sudah_dibaca' => true]);

        return redirect()->route('admin.verifikasi-pembayaran')
            ->with('success', 'Pembayaran berhasil disetujui!');
    }

    /**
     * Tolak pembayaran
     */
    public function reject($id)
    {
        $payment = Payment::with(['booking.kos', 'booking.room', 'user'])->findOrFail($id);
        
        // Update status pembayaran
        $payment->status = 'Rejected';
        $payment->save();

        // Update status booking menjadi CANCELLED
        if ($payment->booking) {
            $payment->booking->status = 'CANCELLED';
            $payment->booking->save();
        }

        // Buat notifikasi untuk user (pencari)
        NotificationHelper::create(
            $payment->id_pengguna,
            'Pembayaran Ditolak',
            'Pembayaran Anda untuk ' . ($payment->booking->kos->nama ?? 'kos') . ' telah ditolak. Silakan hubungi admin untuk informasi lebih lanjut.',
            'payment',
            $payment->id
        );

        // Buat notifikasi untuk pemilik kos
        if ($payment->booking && $payment->booking->kos && $payment->booking->kos->id_pengguna) {
            NotificationHelper::create(
                $payment->booking->kos->id_pengguna,
                'Pembayaran Ditolak untuk ' . $payment->booking->kos->nama,
                'Pembayaran dari ' . ($payment->user->nama ?? 'penyewa') . ' untuk ' . $payment->booking->kos->nama . ' telah ditolak oleh admin.',
                'payment',
                $payment->id
            );
        }

        // Mark notifikasi admin terkait pembayaran ini sebagai sudah dibaca
        // Cari berdasarkan id_terkait yang sama dengan payment id
        $adminIds = User::where('peran', 'admin')->pluck('id');
        Notification::whereIn('id_pengguna', $adminIds)
            ->where('tipe', 'payment')
            ->where('id_terkait', $payment->id)
            ->update(['sudah_dibaca' => true]);

        return redirect()->route('admin.verifikasi-pembayaran')
            ->with('success', 'Pembayaran berhasil ditolak!');
    }

    /**
     * Lihat detail pembayaran
     */
    public function detail($id)
    {
        $payment = Payment::with(['booking.kos', 'booking.room', 'user'])->findOrFail($id);
        
        return view('admin.verifikasi-pembayaran-detail', compact('payment'));
    }
}

