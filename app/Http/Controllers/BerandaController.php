<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Tampilkan halaman beranda
     */
    public function index(Request $request)
    {
        $query = Kos::where('status', 'Disetujui')
            ->with(['rooms' => function($q) {
                $q->where('status', 'Tersedia');
            }, 'images']);

        // Pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan lokasi (kota/daerah)
        if ($request->has('lokasi') && $request->lokasi) {
            $query->where('kota', 'like', "%{$request->lokasi}%");
        }

        // Filter berdasarkan tipe
        if ($request->has('type') && $request->type) {
            $query->where('tipe', $request->type);
        }

        $kosList = $query->paginate(12);

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

