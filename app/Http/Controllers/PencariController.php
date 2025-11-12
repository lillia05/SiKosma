<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class PencariController extends Controller
{
    /**
     * Tampilkan beranda pencari (setelah login)
     */
    public function beranda(Request $request)
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

        // Filter berdasarkan lokasi
        if ($request->has('lokasi') && $request->lokasi) {
            $query->where('kota', 'like', "%{$request->lokasi}%");
        }

        // Filter berdasarkan tipe
        if ($request->has('type') && $request->type) {
            $query->where('tipe', $request->type);
        }

        $kosList = $query->paginate(12);

        return view('pencari.beranda', compact('kosList'));
    }
}

