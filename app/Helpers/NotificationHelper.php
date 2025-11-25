<?php

namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Str;

class NotificationHelper
{
    /**
     * Buat notifikasi untuk user
     */
    public static function create($userId, $judul, $pesan, $tipe = 'system', $idTerkait = null)
    {
        return Notification::create([
            'id' => Str::uuid(),
            'id_pengguna' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'id_terkait' => $idTerkait,
            'sudah_dibaca' => false,
        ]);
    }

    /**
     * Buat notifikasi untuk semua admin
     */
    public static function notifyAdmins($judul, $pesan, $tipe = 'system', $idTerkait = null)
    {
        $admins = \App\Models\User::where('peran', 'admin')->get();
        
        foreach ($admins as $admin) {
            self::create($admin->id, $judul, $pesan, $tipe, $idTerkait);
        }
    }
}

