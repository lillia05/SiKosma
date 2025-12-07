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

            // Sync notifikasi lama: mark notifikasi yang terkait dengan payment/kos yang sudah disetujui sebagai sudah dibaca
            // Hanya sync notifikasi yang dibuat SEBELUM payment/kos disetujui (untuk menghindari menandai notifikasi baru)
            $adminIds = User::where('peran', 'admin')->pluck('id');
            
            // Mark notifikasi payment yang terkait dengan payment yang sudah Verified
            // Hanya mark notifikasi yang dibuat sebelum payment di-verify
            $verifiedPayments = Payment::with('booking.kos')
                ->where('status', 'Verified')
                ->get();
            
            if ($verifiedPayments->isNotEmpty()) {
                foreach ($verifiedPayments as $payment) {
                    // Mark notifikasi yang dibuat sebelum payment di-verify
                    $paymentVerifiedAt = $payment->updated_at; // Waktu payment di-verify
                    
                    Notification::whereIn('id_pengguna', $adminIds)
                        ->where('tipe', 'payment')
                        ->where('id_terkait', $payment->id)
                        ->where('sudah_dibaca', false)
                        ->where('created_at', '<=', $paymentVerifiedAt) // Hanya notifikasi yang dibuat sebelum di-verify
                        ->update(['sudah_dibaca' => true]);
                }
            }
            
            // Mark notifikasi verification yang terkait dengan kos yang sudah Disetujui
            // Hanya mark notifikasi yang dibuat sebelum kos disetujui
            $approvedKos = Kos::where('status', 'Disetujui')->get();
            if ($approvedKos->isNotEmpty()) {
                foreach ($approvedKos as $kos) {
                    $kosApprovedAt = $kos->updated_at; // Waktu kos disetujui
                    
                    Notification::whereIn('id_pengguna', $adminIds)
                        ->where('tipe', 'verification')
                        ->where('id_terkait', $kos->id)
                        ->where('sudah_dibaca', false)
                        ->where('created_at', '<=', $kosApprovedAt) // Hanya notifikasi yang dibuat sebelum disetujui
                        ->update(['sudah_dibaca' => true]);
                }
            }
            
            // Mark notifikasi payment yang terkait dengan payment yang sudah Rejected
            // Hanya mark notifikasi yang dibuat sebelum payment ditolak
            $rejectedPayments = Payment::where('status', 'Rejected')->get();
            if ($rejectedPayments->isNotEmpty()) {
                foreach ($rejectedPayments as $payment) {
                    $paymentRejectedAt = $payment->updated_at; // Waktu payment ditolak
                    
                    Notification::whereIn('id_pengguna', $adminIds)
                        ->where('tipe', 'payment')
                        ->where('id_terkait', $payment->id)
                        ->where('sudah_dibaca', false)
                        ->where('created_at', '<=', $paymentRejectedAt) // Hanya notifikasi yang dibuat sebelum ditolak
                        ->update(['sudah_dibaca' => true]);
                }
            }
            
            // Mark notifikasi verification yang terkait dengan kos yang sudah Ditolak
            // Hanya mark notifikasi yang dibuat sebelum kos ditolak
            $rejectedKos = Kos::where('status', 'Ditolak')->get();
            if ($rejectedKos->isNotEmpty()) {
                foreach ($rejectedKos as $kos) {
                    $kosRejectedAt = $kos->updated_at; // Waktu kos ditolak
                    
                    Notification::whereIn('id_pengguna', $adminIds)
                        ->where('tipe', 'verification')
                        ->where('id_terkait', $kos->id)
                        ->where('sudah_dibaca', false)
                        ->where('created_at', '<=', $kosRejectedAt) // Hanya notifikasi yang dibuat sebelum ditolak
                        ->update(['sudah_dibaca' => true]);
                }
            }
            
            // Notifikasi terbaru - ambil 5 notifikasi terbaru untuk admin
            // Prioritas: belum dibaca dulu, lalu sudah dibaca, semua diurutkan berdasarkan waktu terbaru
            // Ambil lebih banyak notifikasi (20) untuk memastikan notifikasi baru tidak terlewat, lalu urutkan dan ambil 5 teratas
            $allNotifications = Notification::whereIn('id_pengguna', $adminIds)
                ->with(['user' => function($query) {
                    $query->select('id', 'nama');
                }])
                ->select('id', 'id_pengguna', 'judul', 'pesan', 'tipe', 'sudah_dibaca', 'created_at')
                ->orderBy('created_at', 'desc') // Ambil berdasarkan waktu terbaru dulu
                ->limit(20) // Ambil 20 terbaru untuk memastikan tidak ada yang terlewat
                ->get();
            
            // Urutkan: belum dibaca dulu, lalu sudah dibaca, semua berdasarkan waktu terbaru
            $notifications = $allNotifications
                ->sort(function($a, $b) {
                    // Prioritas 1: belum dibaca dulu
                    if ($a->sudah_dibaca != $b->sudah_dibaca) {
                        return $a->sudah_dibaca ? 1 : -1; // false (belum dibaca) lebih dulu
                    }
                    // Prioritas 2: waktu terbaru
                    return $b->created_at->timestamp <=> $a->created_at->timestamp;
                })
                ->take(5) // Ambil 5 teratas setelah sorting
                ->values() // Reset keys
                ->map(function($notif) {
                    // Tentukan status berdasarkan tipe dan judul
                    $status = 'MENUNGGU';
                    $statusClass = 'bg-yellow-100 text-yellow-800';
                    
                    if (str_contains(strtolower($notif->judul), 'disetujui') || 
                        str_contains(strtolower($notif->judul), 'diverifikasi')) {
                        $status = 'DISETUJUI';
                        $statusClass = 'bg-green-100 text-green-800';
                    } elseif (str_contains(strtolower($notif->judul), 'ditolak')) {
                        $status = 'DITOLAK';
                        $statusClass = 'bg-red-100 text-red-800';
                    }
                    
                    return [
                        'id' => $notif->id,
                        'judul' => $notif->judul,
                        'pesan' => $notif->pesan,
                        'user' => $notif->user,
                        'created_at' => $notif->created_at,
                        'sudah_dibaca' => $notif->sudah_dibaca,
                        'status' => $status,
                        'status_class' => $statusClass,
                    ];
                });

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

