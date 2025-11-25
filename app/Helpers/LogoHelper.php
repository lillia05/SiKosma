<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LogoHelper
{
    /**
     * Ambil URL logo
     */
    public static function getLogoUrl()
    {
        $logoFilename = 'sikosma-logo.png';
        
        // Prioritas 1: Cek di storage (untuk logo yang di-upload)
        if (Storage::disk('public')->exists('logos/' . $logoFilename)) {
            return Storage::disk('public')->url('logos/' . $logoFilename);
        }
        
        // Prioritas 2: Cek di public/images (bisa di-commit ke git)
        $publicLogoPath = public_path('images/' . $logoFilename);
        if (File::exists($publicLogoPath)) {
            return asset('images/' . $logoFilename);
        }
        
        // Kembalikan null jika logo tidak ada (akan menggunakan SVG fallback)
        return null;
    }

    /**
     * Ambil URL logo untuk admin (bisa berbeda dari logo umum)
     */
    public static function getAdminLogoUrl()
    {
        $adminLogoFilename = 'sikosma-logo-admin.png';
        
        // Prioritas 1: Cek logo admin di public/images (untuk commit ke git)
        $publicAdminLogoPath = public_path('images/' . $adminLogoFilename);
        if (File::exists($publicAdminLogoPath)) {
            return asset('images/' . $adminLogoFilename);
        }
        
        // Prioritas 2: Cek logo admin di storage
        if (Storage::disk('public')->exists('logos/' . $adminLogoFilename)) {
            return Storage::disk('public')->url('logos/' . $adminLogoFilename);
        }
        
        // Prioritas 3: Gunakan logo umum jika logo admin tidak ada
        return self::getLogoUrl();
    }
}

