<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * Upload gambar dan kembalikan nama file
     */
    public static function uploadImage($file, $folder = 'kos-images', $prefix = 'kos')
    {
        // Generate nama file unik
        $extension = $file->getClientOriginalExtension();
        $filename = $prefix . '-' . Str::random(10) . '-' . time() . '.' . $extension;
        
        // Simpan file di storage/app/public/{folder}
        $path = $file->storeAs($folder, $filename, 'public');
        
        // Kembalikan hanya nama file (bukan full path)
        return $filename;
    }

    /**
     * Hapus gambar dari storage
     */
    public static function deleteImage($filename, $folder = 'kos-images')
    {
        if ($filename && Storage::disk('public')->exists($folder . '/' . $filename)) {
            Storage::disk('public')->delete($folder . '/' . $filename);
            return true;
        }
        return false;
    }

    /**
     * Ambil URL gambar
     */
    public static function getImageUrl($filename, $folder = 'kos-images')
    {
        if (!$filename) {
            return null;
        }

        // Jika sudah berupa URL lengkap, kembalikan seperti semula
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }

        // Cek apakah file ada di storage
        if (Storage::disk('public')->exists($folder . '/' . $filename)) {
            return Storage::disk('public')->url($folder . '/' . $filename);
        }

        return null;
    }
}

