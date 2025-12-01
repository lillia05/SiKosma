<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PemilikController extends Controller
{
    /**
     * Tampilkan dashboard pemilik kos
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            // Ambil semua kos aktif milik pemilik ini (digunakan untuk beberapa query)
            // Kos yang sudah disetujui admin dianggap aktif untuk pemilik
            $kosIds = Kos::where('id_pengguna', $user->id)
                ->whereIn('status', ['Aktif', 'Disetujui'])
                ->pluck('id');
            
            // Total Kos Aktif milik pemilik ini
            $totalKosAktif = $kosIds->count();

            // Total kamar dan kamar terisi
            $allRooms = Room::whereIn('id_kos', $kosIds)->get();
            $totalRooms = $allRooms->count();
            
            // Hitung kamar terisi dari booking yang sudah CONFIRMED dengan payment Verified
            // atau booking yang masih aktif (tanggal mulai sudah lewat dan belum selesai)
            $occupiedRooms = Booking::whereIn('id_kos', $kosIds)
                ->whereHas('payment', function($query) {
                    $query->where('status', 'Verified');
                })
                ->where('status', 'CONFIRMED')
                ->where(function($query) {
                    // Booking yang masih aktif (tanggal mulai sudah lewat dan belum selesai)
                    $query->where('tanggal_mulai', '<=', now())
                          ->where('tanggal_selesai', '>=', now());
                })
                ->distinct('id_kamar')
                ->count('id_kamar');
            
            $occupancyPercentage = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

            // Pendapatan bulan ini (dari pembayaran yang sudah verified)
            // Ambil booking yang terkait dengan kos milik pemilik ini
            
            // Ambil booking yang terkait dengan kos milik pemilik ini
            $bookingIds = Booking::whereIn('id_kos', $kosIds)->pluck('id');
            
            // Hitung pendapatan dari pembayaran yang verified bulan ini
            // Gunakan updated_at karena itu menunjukkan kapan pembayaran di-verify oleh admin
            $pendapatanBulanIni = Payment::whereIn('id_pemesanan', $bookingIds)
                ->where('status', 'Verified')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->sum('jumlah') ?? 0;

            // Notifikasi terbaru untuk pemilik ini
            $notifications = Notification::where('id_pengguna', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get()
                ->map(function($notif) {
                    // Format notifikasi sesuai dengan UI
                    $text = $notif->judul;
                    if ($notif->pesan) {
                        $text .= ': ' . $notif->pesan;
                    }
                    
                    // Tentukan status berdasarkan tipe dan judul
                    $status = null;
                    if (($notif->tipe === 'booking' || $notif->tipe === 'payment') && 
                        (str_contains(strtolower($notif->judul), 'disetujui') || 
                         str_contains(strtolower($notif->judul), 'verifikasi'))) {
                        $status = 'TERVERIFIKASI';
                    }
                    
                    return [
                        'id' => $notif->id,
                        'text' => $text,
                        'date' => $notif->created_at->format('Y-m-d'),
                        'status' => $status,
                        'type' => $notif->tipe,
                    ];
                });

            // Grafik ketersediaan kamar per tahun (2022-2025)
            // Menampilkan total kamar yang dimiliki pemilik per tahun
            $years = [2022, 2023, 2024, 2025];
            $chartData = [];
            
            foreach ($years as $year) {
                // Hitung total kamar yang sudah dibuat sampai tahun tersebut
                // Untuk tahun ini dan masa depan, gunakan data saat ini
                if ($year <= now()->year) {
                    // Hitung kamar yang dibuat sampai tahun tersebut
                    $availableRooms = Room::whereIn('id_kos', $kosIds)
                        ->whereYear('created_at', '<=', $year)
                        ->count();
                } else {
                    // Untuk tahun depan, gunakan data saat ini (estimasi)
                    $availableRooms = Room::whereIn('id_kos', $kosIds)
                        ->count();
                }
                
                $chartData[] = [
                    'month' => (string)$year,
                    'kamar' => $availableRooms,
                ];
            }

            // Daftar pemesanan terbaru untuk kos milik pemilik ini
            $recentBookings = Booking::whereIn('id_kos', $kosIds)
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
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function($booking) {
                    // Format status sesuai dengan UI
                    $status = 'MENUNGGU';
                    if ($booking->payment) {
                        if ($booking->payment->status === 'Verified') {
                            $status = 'TERKONFIRMASI';
                        } elseif ($booking->payment->status === 'Rejected') {
                            $status = 'DITOLAK';
                        }
                    }
                    
                    // Jika booking sudah selesai (tanggal selesai sudah lewat)
                    if ($booking->tanggal_selesai && $booking->tanggal_selesai->isPast()) {
                        $status = 'SELESAI';
                    }
                    
                    // Format ID pesanan
                    $bookingId = $booking->id_pemesanan ?? 'PES-' . str_pad($booking->id ? substr($booking->id, 0, 3) : '001', 3, '0', STR_PAD_LEFT);
                    
                    return [
                        'id' => $bookingId,
                        'kosName' => $booking->kos->nama ?? '-',
                        'room' => 'Kamar ' . ($booking->room->nomor_kamar ?? '-'),
                        'startDate' => $booking->tanggal_mulai ? $booking->tanggal_mulai->format('Y-m-d') : '-',
                        'status' => $status,
                    ];
                });

            return view('pemilik.dashboard', compact(
                'totalKosAktif',
                'totalRooms',
                'occupiedRooms',
                'occupancyPercentage',
                'pendapatanBulanIni',
                'notifications',
                'chartData',
                'recentBookings'
            ));
        } catch (\Exception $e) {
            \Log::error('Pemilik Dashboard Error: ' . $e->getMessage());
            return redirect()->route('beranda')->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi.');
        }
    }
}

