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
}

