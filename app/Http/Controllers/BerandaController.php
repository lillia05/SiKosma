<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    /**
     * Tampilkan halaman beranda
     */
    public function index(Request $request)
    {
        // Jika user sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            $activeRole = session('active_role') ?? Auth::user()->role;
            
            if ($activeRole === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($activeRole === 'pemilik') {
                return redirect()->route('pemilik.dashboard');
            } elseif ($activeRole === 'pencari') {
                return redirect()->route('pencari.beranda');
            }
        }

        $query = Kos::where('status', 'Disetujui')
            ->with(['rooms' => function($q) {
                // Hanya tampilkan kamar yang tersedia (tidak ada booking aktif)
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
            }, 'images']);

        // Pencarian (case-insensitive dan partial match)
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(alamat) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(kota) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Filter berdasarkan lokasi (kota/daerah) - case-insensitive
        if ($request->has('lokasi') && $request->lokasi) {
            $query->whereRaw('LOWER(kota) = ?', [strtolower($request->lokasi)]);
        }

        // Filter berdasarkan tipe - case-insensitive
        if ($request->has('type') && $request->type) {
            $query->whereRaw('LOWER(tipe) = ?', [strtolower($request->type)]);
        }

        $kosList = $query->paginate(12);

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.kos-list', compact('kosList'))->render(),
                'pagination' => view('partials.pagination', compact('kosList'))->render(),
            ]);
        }

        return view('beranda', compact('kosList'));
    }

    /**
     * Tampilkan halaman tentang
     */
    public function tentang()
    {
        // Data anggota tim
        $teamMembers = [
            [
                'name' => 'Muhammad Akmal Fadhurohman',
                'role' => 'Project Lead & Full Stack Developer',
            ],
            [
                'name' => 'Muhammad Alvin',
                'role' => 'Backend Developer & Database Designer',
            ],
            [
                'name' => 'Lekok Indah Lia',
                'role' => 'UI/UX Designer & Frontend Developer',
            ],
            [
                'name' => 'Lifia',
                'role' => 'QA Tester & Documentation',
            ],
        ];

        return view('tentang', compact('teamMembers'));
    }
}

