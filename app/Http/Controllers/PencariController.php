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

        // Pencarian (case-insensitive dan partial match)
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(alamat) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(kota) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Filter berdasarkan lokasi - case-insensitive
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

        return view('pencari.beranda', compact('kosList'));
    }
}

