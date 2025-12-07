<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\Notification;
use App\Helpers\NotificationHelper;
use App\Models\User;
use Illuminate\Http\Request;

class AdminVerifikasiKosController extends Controller
{
    /**
     * Tampilkan halaman verifikasi kos
     */
    public function index(Request $request)
    {
        $query = Kos::with('user')
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Ditolak'])
            ->orderBy('created_at', 'desc');

        // Pencarian
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(alamat) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Filter status
        if ($request->has('status') && $request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $kosList = $query->get();

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.verifikasi-kos-table', compact('kosList'))->render(),
            ]);
        }

        return view('admin.verifikasi-kos', compact('kosList'));
    }

    /**
     * Setujui kos
     */
    public function approve($id)
    {
        $kos = Kos::with('user')->findOrFail($id);
        $kos->status = 'Disetujui';
        $kos->save();

        // Buat notifikasi untuk pemilik kos
        if ($kos->id_pengguna) {
            NotificationHelper::create(
                $kos->id_pengguna,
                'Kos Disetujui',
                'Kos Anda "' . $kos->nama . '" telah disetujui oleh admin dan sekarang dapat dilihat oleh pencari kos.',
                'verification',
                $kos->id
            );
        }

        // Mark notifikasi admin terkait kos ini sebagai sudah dibaca
        // Cari berdasarkan id_terkait yang sama dengan kos id
        $adminIds = User::where('peran', 'admin')->pluck('id');
        Notification::whereIn('id_pengguna', $adminIds)
            ->where('tipe', 'verification')
            ->where('id_terkait', $kos->id)
            ->update(['sudah_dibaca' => true]);

        return redirect()->route('admin.verifikasi-kos')
            ->with('success', 'Kos berhasil disetujui!');
    }

    /**
     * Tolak kos
     */
    public function reject($id)
    {
        $kos = Kos::with('user')->findOrFail($id);
        $kos->status = 'Ditolak';
        $kos->save();

        // Buat notifikasi untuk pemilik kos
        if ($kos->id_pengguna) {
            NotificationHelper::create(
                $kos->id_pengguna,
                'Kos Ditolak',
                'Kos Anda "' . $kos->nama . '" telah ditolak oleh admin. Silakan periksa informasi yang diberikan.',
                'verification',
                $kos->id
            );
        }

        // Mark notifikasi admin terkait kos ini sebagai sudah dibaca
        // Cari berdasarkan id_terkait yang sama dengan kos id
        $adminIds = User::where('peran', 'admin')->pluck('id');
        Notification::whereIn('id_pengguna', $adminIds)
            ->where('tipe', 'verification')
            ->where('id_terkait', $kos->id)
            ->update(['sudah_dibaca' => true]);

        return redirect()->route('admin.verifikasi-kos')
            ->with('success', 'Kos berhasil ditolak!');
    }

    /**
     * Tampilkan detail kos untuk admin
     */
    public function detail($id)
    {
        $kos = Kos::with(['user', 'images', 'rooms', 'ulasan.user'])
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Ditolak'])
            ->findOrFail($id);

        return view('admin.verifikasi-kos-detail', compact('kos'));
    }
}

