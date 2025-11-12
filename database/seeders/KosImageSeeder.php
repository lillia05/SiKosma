<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KosImage;
use App\Models\Kos;
use Illuminate\Support\Str;

class KosImageSeeder extends Seeder
{
    public function run(): void
    {
        // Catatan: Kos images akan di-input oleh pemilik kos melalui form upload
        // Seeder ini hanya untuk data sample/demo jika diperlukan
        
        // Get all kos
        $kos1 = Kos::where('nama', 'Kos Putri Sahara')->first();
        $kos2 = Kos::where('nama', 'Kos Putra Kampung Baru')->first();
        $kos3 = Kos::where('nama', 'Kos Putri Melati')->first();
        $kos4 = Kos::where('nama', 'Kos Putra Ali')->first();

        // Kos Putri Sahara - 3 images (hanya untuk demo, gambar harus di-upload manual)
        if ($kos1) {
            $this->createKosImage($kos1->id, 'kos-putri-sahara-1.png', 'general');
            $this->createKosImage($kos1->id, 'kos-putri-sahara-2.png', 'room');
            $this->createKosImage($kos1->id, 'kos-putri-sahara-3.png', 'common_area');
        }

        // Kos Putra Kampung Baru - 3 images
        if ($kos2) {
            $this->createKosImage($kos2->id, 'kos-putra-kampung-baru-1.png', 'general');
            $this->createKosImage($kos2->id, 'kos-putra-kampung-baru-2.png', 'room');
            $this->createKosImage($kos2->id, 'kos-putra-kampung-baru-3.png', 'common_area');
        }

        // Kos Putri Melati - 3 images
        if ($kos3) {
            $this->createKosImage($kos3->id, 'kos-putri-melati-1.png', 'general');
            $this->createKosImage($kos3->id, 'kos-putri-melati-2.png', 'room');
            $this->createKosImage($kos3->id, 'kos-putri-melati-3.png', 'common_area');
        }

        // Kos Putra Ali - 3 images
        if ($kos4) {
            $this->createKosImage($kos4->id, 'kos-putra-ali-1.png', 'general');
            $this->createKosImage($kos4->id, 'kos-putra-ali-2.png', 'room');
            $this->createKosImage($kos4->id, 'kos-putra-ali-3.png', 'common_area');
        }
        
        $this->command->info('ℹ️  Catatan: Gambar kos harus di-upload oleh pemilik kos melalui form upload.');
    }

    /**
     * Buat record kos image di database (gambar harus di-upload manual)
     */
    private function createKosImage($kosId, $filename, $tipe)
    {
        // Cek apakah image sudah ada di database
        $existingImage = KosImage::where('id_kos', $kosId)
            ->where('url_gambar', $filename)
            ->first();

        if (!$existingImage) {
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kosId,
                'url_gambar' => $filename,
                'tipe_gambar' => $tipe,
            ]);
        }
    }
}

