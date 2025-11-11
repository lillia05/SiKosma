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
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan lokasi
        if ($request->has('lokasi') && $request->lokasi) {
            $query->where('city', 'like', "%{$request->lokasi}%");
        }

        // Filter berdasarkan tipe
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $kosList = $query->paginate(12);

        return view('pencari.beranda', compact('kosList'));
    }
}

