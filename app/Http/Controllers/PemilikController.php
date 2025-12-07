<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\KosImage;
use App\Http\Controllers\ImageController;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
                    if (($notif->tipe === 'booking' || $notif->tipe === 'payment' || $notif->tipe === 'verification') && 
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

    /**
     * Tampilkan halaman manajemen properti kos
     */
    public function properti()
    {
        try {
            $user = Auth::user();
            
            // Ambil semua kos milik pemilik ini dengan jumlah kamar
            $kosList = Kos::where('id_pengguna', $user->id)
                ->withCount('rooms')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($kos) {
                    // Format ID kos (ambil 5 karakter pertama dari UUID)
                    $kosId = strtoupper(substr($kos->id, 0, 5));
                    
                    // Mapping status kos
                    $status = $this->mapKosStatus($kos->status);
                    
                    return [
                        'id' => $kos->id,
                        'kos_id' => $kosId,
                        'nama' => $kos->nama,
                        'tipe' => $kos->tipe,
                        'jumlah_kamar' => $kos->rooms_count,
                        'status' => $status['text'],
                        'status_class' => $status['class'],
                        'status_db' => $kos->status,
                    ];
                });

            return view('pemilik.properti', compact('kosList'));
        } catch (\Exception $e) {
            \Log::error('Pemilik Properti Error: ' . $e->getMessage());
            return redirect()->route('pemilik.dashboard')->with('error', 'Terjadi kesalahan saat memuat data properti. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan halaman laporan keuangan untuk pemilik kos
     */
    public function laporan()
    {
        try {
            $user = Auth::user();
            
            // Ambil semua kos milik pemilik ini
            $kosIds = Kos::where('id_pengguna', $user->id)->pluck('id');
            
            // Hitung periode 6 tahun terakhir (dari tahun sekarang mundur 5 tahun)
            $currentYear = now()->year;
            $startYear = $currentYear - 5; // 6 tahun total (currentYear - 5 sampai currentYear)
            
            // Ambil semua booking untuk kos milik pemilik ini dalam 6 tahun terakhir
            $bookings = Booking::whereIn('id_kos', $kosIds)
                ->whereYear('created_at', '>=', $startYear)
                ->whereYear('created_at', '<=', $currentYear)
                ->with(['payment', 'kos'])
                ->get();
            
            // Ambil semua payment yang verified untuk booking ini (untuk optimasi)
            $bookingIds = $bookings->pluck('id');
            $verifiedPayments = Payment::whereIn('id_pemesanan', $bookingIds)
                ->where('status', 'Verified')
                ->get()
                ->keyBy('id_pemesanan');
            
            // Hitung total pemesanan
            $totalPemesanan = $bookings->count();
            
            // Hitung dikonfirmasi (booking dengan payment Verified dan status CONFIRMED)
            $dikonfirmasi = $bookings->filter(function($booking) use ($verifiedPayments) {
                return isset($verifiedPayments[$booking->id]) && 
                       $booking->status === 'CONFIRMED';
            })->count();
            
            // Hitung selesai (booking yang sudah selesai - tanggal_selesai sudah lewat dan status CONFIRMED)
            $selesai = $bookings->filter(function($booking) {
                return $booking->status === 'CONFIRMED' && 
                       $booking->tanggal_selesai && 
                       $booking->tanggal_selesai->isPast();
            })->count();
            
            // Hitung total pendapatan dari pembayaran yang verified
            $totalPendapatan = $verifiedPayments->sum('jumlah') ?? 0;
            
            // Hitung persentase
            $persentaseKonfirmasi = $totalPemesanan > 0 ? round(($dikonfirmasi / $totalPemesanan) * 100) : 0;
            $persentasePenyelesaian = $totalPemesanan > 0 ? round(($selesai / $totalPemesanan) * 100) : 0;
            
            // Group data per tahun
            $laporanData = [];
            for ($year = $startYear; $year <= $currentYear; $year++) {
                $yearBookings = $bookings->filter(function($booking) use ($year) {
                    return $booking->created_at->year == $year;
                });
                
                $pemesananTahun = $yearBookings->count();
                
                $dikonfirmasiTahun = $yearBookings->filter(function($booking) use ($verifiedPayments) {
                    return isset($verifiedPayments[$booking->id]) && 
                           $booking->status === 'CONFIRMED';
                })->count();
                
                $selesaiTahun = $yearBookings->filter(function($booking) {
                    return $booking->status === 'CONFIRMED' && 
                           $booking->tanggal_selesai && 
                           $booking->tanggal_selesai->isPast();
                })->count();
                
                // Hitung pendapatan tahun ini dari verified payments
                $yearBookingIds = $yearBookings->pluck('id');
                $pendapatanTahun = $verifiedPayments->filter(function($payment) use ($yearBookingIds) {
                    return $yearBookingIds->contains($payment->id_pemesanan);
                })->sum('jumlah') ?? 0;
                
                $laporanData[] = [
                    'tahun' => (string)$year,
                    'pemesanan' => $pemesananTahun,
                    'dikonfirmasi' => $dikonfirmasiTahun,
                    'selesai' => $selesaiTahun,
                    'pendapatan' => $pendapatanTahun,
                ];
            }
            
            return view('pemilik.laporan', compact(
                'totalPemesanan',
                'dikonfirmasi',
                'selesai',
                'totalPendapatan',
                'persentaseKonfirmasi',
                'persentasePenyelesaian',
                'laporanData'
            ));
        } catch (\Exception $e) {
            \Log::error('Pemilik Laporan Error: ' . $e->getMessage());
            return redirect()->route('pemilik.dashboard')->with('error', 'Terjadi kesalahan saat memuat laporan. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan form tambah kos baru
     */
    public function create()
    {
        return view('pemilik.kos-form');
    }

    /**
     * Tampilkan form edit kos
     */
    public function edit($id)
    {
        $user = Auth::user();
        $kos = Kos::where('id_pengguna', $user->id)
            ->with(['rooms', 'images'])
            ->findOrFail($id);

        return view('pemilik.kos-form', compact('kos'));
    }

    /**
     * Simpan kos baru
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi data kos
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'tipe' => 'required|in:Putra,Putri,Campur',
                'nomor_telepon' => 'required|string|max:20',
                'deskripsi' => 'nullable|string',
                'alamat' => 'required|string|max:255',
                'kota' => 'required|string|max:100',
                'tautan_google_maps' => 'nullable|url|max:500',
                'rooms' => 'required|array|min:1',
                'rooms.*.nomor_kamar' => 'required|string|max:50',
                'rooms.*.harga_per_tahun' => 'required|numeric|min:0',
                'rooms.*.ukuran_kamar' => 'required|numeric|min:0',
                'rooms.*.fasilitas' => 'nullable|string',
                'room_images' => 'nullable',
                'general_images' => 'nullable|array',
                'general_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Buat kos baru
            $kos = Kos::create([
                'id' => Str::uuid(),
                'id_pengguna' => $user->id,
                'nama' => $validated['nama'],
                'tipe' => $validated['tipe'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'alamat' => $validated['alamat'],
                'kota' => $validated['kota'],
                'tautan_google_maps' => $validated['tautan_google_maps'] ?? null,
                'status' => 'Menunggu', // Status awal menunggu verifikasi admin
            ]);

            // Simpan kamar
            foreach ($validated['rooms'] as $roomData) {
                $room = Room::create([
                    'id' => Str::uuid(),
                    'id_kos' => $kos->id,
                    'nomor_kamar' => $roomData['nomor_kamar'],
                    'harga_per_tahun' => $roomData['harga_per_tahun'],
                    'ukuran_kamar' => $roomData['ukuran_kamar'],
                    'fasilitas' => $roomData['fasilitas'] ?? null,
                    'status' => 'Tersedia',
                ]);

                // Upload gambar kamar jika ada
                // Cek dengan berbagai cara untuk memastikan file terdeteksi
                $roomImageKey = "room_images.{$roomData['nomor_kamar']}";
                $roomImage = null;
                
                if ($request->hasFile($roomImageKey)) {
                    $roomImage = $request->file($roomImageKey);
                } elseif ($request->has("room_images") && isset($request->file("room_images")[$roomData['nomor_kamar']])) {
                    $roomImage = $request->file("room_images")[$roomData['nomor_kamar']];
                }
                
                if ($roomImage && $roomImage->isValid()) {
                    $filename = ImageController::uploadImage($roomImage, 'kos-images', 'kamar');
                    
                    // Simpan dengan format: kamar-{nomor_kamar}.{ext}
                    $extension = $roomImage->getClientOriginalExtension();
                    $finalFilename = 'kamar-' . $roomData['nomor_kamar'] . '.' . $extension;
                    
                    // Rename file jika perlu
                    if ($filename !== $finalFilename) {
                        Storage::disk('public')->move('kos-images/' . $filename, 'kos-images/' . $finalFilename);
                        $filename = $finalFilename;
                    }
                    
                    KosImage::create([
                        'id' => Str::uuid(),
                        'id_kos' => $kos->id,
                        'url_gambar' => $filename,
                        'tipe_gambar' => 'kamar',
                    ]);
                }
            }

            // Upload gambar general
            if ($request->hasFile('general_images')) {
                foreach ($request->file('general_images') as $image) {
                    $filename = ImageController::uploadImage($image, 'kos-images', 'kos');
                    KosImage::create([
                        'id' => Str::uuid(),
                        'id_kos' => $kos->id,
                        'url_gambar' => $filename,
                        'tipe_gambar' => 'general',
                    ]);
                }
            }

            // Buat notifikasi untuk semua admin bahwa ada kos baru yang menunggu verifikasi
            NotificationHelper::notifyAdmins(
                'Kos Baru Menunggu Verifikasi',
                'Kos "' . $kos->nama . '" dari ' . $user->nama . ' menunggu verifikasi.',
                'verification',
                $kos->id
            );

            return redirect()->route('pemilik.properti')
                ->with('success', 'Kos berhasil ditambahkan! Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            \Log::error('Pemilik Store Kos Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan kos. Silakan coba lagi.');
        }
    }

    /**
     * Update kos yang sudah ada
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $kos = Kos::where('id_pengguna', $user->id)->findOrFail($id);

            // Validasi
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'tipe' => 'required|in:Putra,Putri,Campur',
                'nomor_telepon' => 'required|string|max:20',
                'deskripsi' => 'nullable|string',
                'alamat' => 'required|string|max:255',
                'kota' => 'required|string|max:100',
                'tautan_google_maps' => 'nullable|url|max:500',
                'rooms' => 'required|array|min:1',
                'rooms.*.id' => 'nullable|uuid|exists:kamar,id',
                'rooms.*.nomor_kamar' => 'required|string|max:50',
                'rooms.*.harga_per_tahun' => 'required|numeric|min:0',
                'rooms.*.ukuran_kamar' => 'required|numeric|min:0',
                'rooms.*.fasilitas' => 'nullable|string',
                'room_images' => 'nullable',
                'general_images' => 'nullable|array',
                'general_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'deleted_image_ids' => 'nullable|array',
                'deleted_image_ids.*' => 'uuid|exists:foto_kos,id',
                'deleted_room_ids' => 'nullable|array',
                'deleted_room_ids.*' => 'uuid|exists:kamar,id',
            ]);

            // Update data kos
            $kos->update([
                'nama' => $validated['nama'],
                'tipe' => $validated['tipe'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'alamat' => $validated['alamat'],
                'kota' => $validated['kota'],
                'tautan_google_maps' => $validated['tautan_google_maps'] ?? null,
            ]);

            // Handle penghapusan gambar yang dipilih user
            if ($request->has('deleted_image_ids')) {
                $deletedImageIds = $request->input('deleted_image_ids');
                $imagesToDelete = KosImage::where('id_kos', $kos->id)
                    ->whereIn('id', $deletedImageIds)
                    ->get();
                
                foreach ($imagesToDelete as $image) {
                    ImageController::deleteImage($image->url_gambar, 'kos-images');
                    $image->delete();
                }
            }

            // Handle kamar (update/create/delete)
            $existingRoomIds = collect($validated['rooms'])->pluck('id')->filter();
            
            // Hapus kamar yang dihapus user
            if ($request->has('deleted_room_ids')) {
                $deletedRoomIds = $request->input('deleted_room_ids');
                $roomsToDelete = Room::where('id_kos', $kos->id)
                    ->whereIn('id', $deletedRoomIds)
                    ->get();
                
                foreach ($roomsToDelete as $room) {
                    // Hapus gambar kamar terkait
                    KosImage::where('id_kos', $kos->id)
                        ->where('tipe_gambar', 'kamar')
                        ->where('url_gambar', 'like', 'kamar-' . $room->nomor_kamar . '.%')
                        ->get()
                        ->each(function($img) {
                            ImageController::deleteImage($img->url_gambar, 'kos-images');
                            $img->delete();
                        });
                    
                    $room->delete();
                }
            }
            
            // Hapus kamar yang tidak ada di request (jika ada)
            Room::where('id_kos', $kos->id)
                ->whereNotIn('id', $existingRoomIds)
                ->get()
                ->each(function($room) use ($kos) {
                    // Hapus gambar kamar terkait
                    KosImage::where('id_kos', $kos->id)
                        ->where('tipe_gambar', 'kamar')
                        ->where('url_gambar', 'like', 'kamar-' . $room->nomor_kamar . '.%')
                        ->get()
                        ->each(function($img) {
                            ImageController::deleteImage($img->url_gambar, 'kos-images');
                            $img->delete();
                        });
                    
                    $room->delete();
                });

            foreach ($validated['rooms'] as $roomData) {
                if (isset($roomData['id']) && $roomData['id']) {
                    // Update kamar yang sudah ada
                    $room = Room::where('id_kos', $kos->id)
                        ->where('id', $roomData['id'])
                        ->first();
                    
                    if ($room) {
                        $room->update([
                            'nomor_kamar' => $roomData['nomor_kamar'],
                            'harga_per_tahun' => $roomData['harga_per_tahun'],
                            'ukuran_kamar' => $roomData['ukuran_kamar'],
                            'fasilitas' => $roomData['fasilitas'] ?? null,
                        ]);
                    }
                } else {
                    // Buat kamar baru
                    $room = Room::create([
                        'id' => Str::uuid(),
                        'id_kos' => $kos->id,
                        'nomor_kamar' => $roomData['nomor_kamar'],
                        'harga_per_tahun' => $roomData['harga_per_tahun'],
                        'ukuran_kamar' => $roomData['ukuran_kamar'],
                        'fasilitas' => $roomData['fasilitas'] ?? null,
                        'status' => 'Tersedia',
                    ]);
                }

                // Upload gambar kamar jika ada
                // Cek dengan berbagai cara untuk memastikan file terdeteksi
                $roomImageKey = "room_images.{$roomData['nomor_kamar']}";
                $roomImage = null;
                
                if ($request->hasFile($roomImageKey)) {
                    $roomImage = $request->file($roomImageKey);
                } elseif ($request->has("room_images") && isset($request->file("room_images")[$roomData['nomor_kamar']])) {
                    $roomImage = $request->file("room_images")[$roomData['nomor_kamar']];
                }
                
                if ($roomImage && $roomImage->isValid()) {
                    // Hapus gambar kamar lama jika ada
                    KosImage::where('id_kos', $kos->id)
                        ->where('tipe_gambar', 'kamar')
                        ->where('url_gambar', 'like', 'kamar-' . $roomData['nomor_kamar'] . '.%')
                        ->get()
                        ->each(function($img) {
                            ImageController::deleteImage($img->url_gambar, 'kos-images');
                            $img->delete();
                        });
                    
                    $filename = ImageController::uploadImage($roomImage, 'kos-images', 'kamar');
                    
                    $extension = $roomImage->getClientOriginalExtension();
                    $finalFilename = 'kamar-' . $roomData['nomor_kamar'] . '.' . $extension;
                    
                    if ($filename !== $finalFilename) {
                        \Storage::disk('public')->move('kos-images/' . $filename, 'kos-images/' . $finalFilename);
                        $filename = $finalFilename;
                    }
                    
                    KosImage::create([
                        'id' => Str::uuid(),
                        'id_kos' => $kos->id,
                        'url_gambar' => $filename,
                        'tipe_gambar' => 'kamar',
                    ]);
                }
            }

            // Upload gambar general baru
            if ($request->hasFile('general_images')) {
                foreach ($request->file('general_images') as $image) {
                    $filename = ImageController::uploadImage($image, 'kos-images', 'kos');
                    KosImage::create([
                        'id' => Str::uuid(),
                        'id_kos' => $kos->id,
                        'url_gambar' => $filename,
                        'tipe_gambar' => 'general',
                    ]);
                }
            }

            return redirect()->route('pemilik.properti')
                ->with('success', 'Kos berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Pemilik Update Kos Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui kos. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan halaman daftar pemesanan untuk pemilik kos
     */
    public function pemesanan()
    {
        try {
            $user = Auth::user();
            
            // Ambil semua kos milik pemilik ini
            $kosIds = Kos::where('id_pengguna', $user->id)->pluck('id');
            
            // Query booking untuk kos milik pemilik ini
            $bookings = Booking::whereIn('id_kos', $kosIds)
                ->with([
                    'kos' => function($query) {
                        $query->select('id', 'nama');
                    },
                    'room' => function($query) {
                        $query->select('id', 'nomor_kamar');
                    },
                    'user' => function($query) {
                        $query->select('id', 'nama');
                    },
                    'payment' => function($query) {
                        $query->select('id', 'id_pemesanan', 'status');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('pemilik.pemesanan', compact('bookings'));
        } catch (\Exception $e) {
            \Log::error('Pemilik Pemesanan Error: ' . $e->getMessage());
            return redirect()->route('pemilik.dashboard')->with('error', 'Terjadi kesalahan saat memuat data pemesanan. Silakan coba lagi.');
        }
    }

    /**
     * Mapping status kos dari database ke format UI
     */
    private function mapKosStatus($status)
    {
        switch ($status) {
            case 'Disetujui':
            case 'Aktif':
                return [
                    'text' => 'Aktif',
                    'class' => 'bg-blue-200 text-blue-800'
                ];
            case 'Menunggu':
                return [
                    'text' => 'MENUNGGU',
                    'class' => 'bg-yellow-200 text-yellow-800'
                ];
            case 'Ditolak':
                return [
                    'text' => 'DITOLAK',
                    'class' => 'bg-red-200 text-red-800'
                ];
            default:
                return [
                    'text' => strtoupper($status),
                    'class' => 'bg-gray-200 text-gray-800'
                ];
        }
    }
}

