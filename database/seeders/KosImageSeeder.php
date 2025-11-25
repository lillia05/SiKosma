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
        $kos5 = Kos::where('nama', 'Kos Campur Adam')->first();

        // Kos Putri Sahara - Images
        if ($kos1) {
            // Gambar utama kos (general)
            $this->createKosImage($kos1->id, 'kos-putri-sahara-1.png', 'general');
            $this->createKosImage($kos1->id, 'kos-putri-sahara-2.png', 'general');
            $this->createKosImage($kos1->id, 'kos-putri-sahara-3.png', 'general');
            $this->createKosImage($kos1->id, 'kos-putri-sahara-4.png', 'general');
            
            // Gambar kamar (format: kamar-{nomor_kamar}.png)
            // Kamar 4, 5, 6, 7, 8 (kamar tersedia)
            for ($i = 4; $i <= 8; $i++) {
                $this->createKosImage($kos1->id, "kamar-{$i}.png", 'kamar');
            }
        }

        // Kos Putra Kampung Baru - Images
        if ($kos2) {
            // Gambar utama kos (general)
            $this->createKosImage($kos2->id, 'kos-putra-kampung-baru-1.png', 'general');
            $this->createKosImage($kos2->id, 'kos-putra-kampung-baru-2.png', 'general');
            $this->createKosImage($kos2->id, 'kos-putra-kampung-baru-3.png', 'general');
            $this->createKosImage($kos2->id, 'kos-putra-kampung-baru-4.png', 'general');
            
            // Gambar kamar (format: kamar-{nomor_kamar}.png)
            // Kamar 3, 4, 5, 6, 7, 8 (kamar tersedia)
            for ($i = 3; $i <= 8; $i++) {
                $this->createKosImage($kos2->id, "kamar-{$i}.png", 'kamar');
            }
        }

        // Kos Putri Melati - Images
        if ($kos3) {
            // Gambar utama kos (general)
            $this->createKosImage($kos3->id, 'kos-putri-melati-1.png', 'general');
            $this->createKosImage($kos3->id, 'kos-putri-melati-2.png', 'general');
            $this->createKosImage($kos3->id, 'kos-putri-melati-3.png', 'general');
            $this->createKosImage($kos3->id, 'kos-putri-melati-4.png', 'general');
            
            // Gambar kamar (format: kamar-{nomor_kamar}.png)
            // Kamar 5, 6 (kamar tersedia)
            for ($i = 5; $i <= 6; $i++) {
                $this->createKosImage($kos3->id, "kamar-{$i}.png", 'kamar');
            }
        }

        // Kos Putra Ali - Images
        if ($kos4) {
            // Gambar utama kos (general)
            $this->createKosImage($kos4->id, 'kos-putra-ali-1.png', 'general');
            $this->createKosImage($kos4->id, 'kos-putra-ali-2.png', 'general');
            $this->createKosImage($kos4->id, 'kos-putra-ali-3.png', 'general');
            $this->createKosImage($kos4->id, 'kos-putra-ali-4.png', 'general');
            
            // Gambar kamar (format: kamar-{nomor_kamar}.png)
            // Kamar 4, 5, 6, 7, 8 (kamar tersedia)
            for ($i = 4; $i <= 8; $i++) {
                $this->createKosImage($kos4->id, "kamar-{$i}.png", 'kamar');
            }
        }

        // Kos Campur Adam - Images
        if ($kos5) {
            // Gambar utama kos (general)
            $this->createKosImage($kos5->id, 'kos-campur-adam-1.png', 'general');
            $this->createKosImage($kos5->id, 'kos-campur-adam-2.png', 'general');
            $this->createKosImage($kos5->id, 'kos-campur-adam-3.png', 'general');
            $this->createKosImage($kos5->id, 'kos-campur-adam-4.png', 'general');
            
            // Gambar kamar (format: kamar-{nomor_kamar}.png)
            // Kamar 6, 7, 8, 9, 10 (kamar tersedia)
            for ($i = 6; $i <= 10; $i++) {
                $this->createKosImage($kos5->id, "kamar-{$i}.png", 'kamar');
            }
        }
        
        $this->command->info('ℹ️  Catatan: Gambar kos harus di-upload manual ke storage/app/public/kos-images/');
        $this->command->info('📝 Format nama file:');
        $this->command->info('   - Gambar umum: kos-{nama-kos}-{nomor}.png');
        $this->command->info('   - Gambar kamar: kamar-{nomor_kamar}.png');
        $this->command->info('📋 Lihat STORAGE_GUIDE.md untuk panduan lengkap');
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

