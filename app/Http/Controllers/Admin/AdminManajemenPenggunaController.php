<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminManajemenPenggunaController extends Controller
{
    /**
     * Tampilkan halaman manajemen pengguna
     */
    public function index(Request $request)
    {
        $query = User::where('peran', '!=', 'admin') // Jangan tampilkan admin
            ->orderBy('created_at', 'desc');

        // Pencarian berdasarkan nama
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Filter role
        if ($request->has('role') && $request->role && $request->role !== 'semua') {
            if ($request->role === 'pengguna') {
                $query->where('peran', 'pencari');
            } elseif ($request->role === 'pemilik kos') {
                $query->where('peran', 'pemilik');
            }
        }

        $users = $query->get();

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.manajemen-pengguna-table', compact('users'))->render(),
            ]);
        }

        return view('admin.manajemen-pengguna', compact('users'));
    }

    /**
     * Update status user
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Validasi
        $request->validate([
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Jangan izinkan edit admin
        if ($user->peran === 'admin') {
            return redirect()->route('admin.manajemen-pengguna')
                ->with('error', 'Tidak dapat mengubah status admin.');
        }

        $user->status = $request->status;
        $user->save();

        return redirect()->route('admin.manajemen-pengguna')
            ->with('success', 'Status pengguna berhasil diperbarui!');
    }

    /**
     * Hapus user (soft delete)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Jangan izinkan hapus admin
        if ($user->peran === 'admin') {
            return redirect()->route('admin.manajemen-pengguna')
                ->with('error', 'Tidak dapat menghapus admin.');
        }

        // Jangan izinkan hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.manajemen-pengguna')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.manajemen-pengguna')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}

