<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard admin
     */
    public function index()
    {
        try {
            // Statistik - menggunakan cache untuk performa lebih baik
            $totalKos = cache()->remember('admin_total_kos', 300, function() {
                return Kos::count();
            });
            
            $totalUsers = cache()->remember('admin_total_users', 300, function() {
                return User::count();
            });
            
            // Total transaksi bulan ini (pembayaran yang sudah verified)
            $totalTransaksiBulanIni = Payment::where('status', 'Verified')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('jumlah') ?? 0;

            // Notifikasi terbaru (belum dibaca) - optimasi dengan select specific columns
            $notifications = Notification::where('sudah_dibaca', false)
                ->with(['user' => function($query) {
                    $query->select('id', 'nama');
                }])
                ->select('id', 'id_pengguna', 'judul', 'pesan', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Grafik pemesanan per tahun (2022-2025) - optimasi dengan single query
            // PostgreSQL compatible: menggunakan EXTRACT(YEAR FROM created_at)
            $years = [2022, 2023, 2024, 2025];
            $bookingsByYear = Booking::selectRaw('EXTRACT(YEAR FROM created_at)::integer as year, COUNT(*) as count')
                ->whereRaw('EXTRACT(YEAR FROM created_at) >= ?', [2022])
                ->whereRaw('EXTRACT(YEAR FROM created_at) <= ?', [2025])
                ->groupBy(DB::raw('EXTRACT(YEAR FROM created_at)'))
                ->pluck('count', 'year')
                ->toArray();
            
            $chartData = [];
            foreach ($years as $year) {
                $chartData[] = [
                    'year' => (string)$year,
                    'orders' => isset($bookingsByYear[$year]) ? (int)$bookingsByYear[$year] : 0
                ];
            }

            // Daftar aktivitas sistem (booking yang punya payment) - optimasi dengan select specific columns
            // Hanya tampilkan booking yang sudah punya payment record
            $activities = Booking::whereHas('payment')
                ->with([
                    'kos' => function($query) {
                        $query->select('id', 'nama');
                    },
                    'room' => function($query) {
                        $query->select('id', 'nomor_kamar');
                    },
                    'payment' => function($query) {
                        $query->select('id', 'id_pemesanan', 'status');
                    }
                ])
                ->select('id', 'id_pemesanan', 'id_kos', 'id_kamar', 'tanggal_mulai', 'status')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($booking) {
                    return [
                        'id' => $booking->id_pemesanan,
                        'kos_name' => $booking->kos->nama ?? '-',
                        'room' => 'Kamar ' . ($booking->room->nomor_kamar ?? '-'),
                        'date' => $booking->tanggal_mulai ? $booking->tanggal_mulai->format('Y-m-d') : '-',
                        'status' => $this->getBookingStatus($booking),
                    ];
                });

            return view('admin.dashboard', compact(
                'totalKos',
                'totalUsers',
                'totalTransaksiBulanIni',
                'notifications',
                'chartData',
                'activities'
            ));
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: ' . $e->getMessage());
            return redirect()->route('beranda')->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi.');
        }
    }

    /**
     * Get booking status untuk display
     * Hanya mengembalikan: MENUNGGU, DISETUJUI, atau DITOLAK
     */
    private function getBookingStatus($booking)
    {
        // Pastikan payment ter-load dengan fresh data
        if (!$booking->relationLoaded('payment')) {
            $booking->load('payment');
        } else {
            // Refresh relasi untuk memastikan data terbaru
            $booking->load('payment');
        }
        
        $payment = $booking->payment;
        
        // Jika tidak ada payment, status = MENUNGGU
        if (!$payment) {
            return 'MENUNGGU';
        }
        
        $paymentStatus = $payment->status;
        
        // Jika payment ditolak, status = DITOLAK
        if ($paymentStatus === 'Rejected') {
            return 'DITOLAK';
        }
        
        // Jika payment verified, status = DISETUJUI
        // (Booking status sudah di-update menjadi CONFIRMED saat approve payment)
        if ($paymentStatus === 'Verified') {
            return 'DISETUJUI';
        }
        
        // Jika payment pending, status = MENUNGGU
        if ($paymentStatus === 'Pending') {
            return 'MENUNGGU';
        }
        
        // Default: MENUNGGU
        return 'MENUNGGU';
    }
}

