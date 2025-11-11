<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class LogoHelper
{
    /**
     * Ambil URL logo
     */
    public static function getLogoUrl()
    {
        $logoFilename = 'sikosma-logo.png';
        
        // Cek apakah logo ada di storage
        if (Storage::disk('public')->exists('logos/' . $logoFilename)) {
            return Storage::disk('public')->url('logos/' . $logoFilename);
        }
        
        // Kembalikan null jika logo tidak ada (akan menggunakan SVG fallback)
        return null;
    }
}

