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
        // Get all kos
        $kos1 = Kos::where('nama', 'Kos Putri Sahara')->first();
        $kos2 = Kos::where('nama', 'Kos Putra Kampung Baru')->first();
        $kos3 = Kos::where('nama', 'Kos Putri Melati')->first();
        $kos4 = Kos::where('nama', 'Kos Putra Ali')->first();

        // Kos Putri Sahara - 3 images
        if ($kos1) {
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos1->id,
                'url_gambar' => 'kos-putri-sahara-1.png',
                'tipe_gambar' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos1->id,
                'url_gambar' => 'kos-putri-sahara-2.png',
                'tipe_gambar' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos1->id,
                'url_gambar' => 'kos-putri-sahara-3.png',
                'tipe_gambar' => 'common_area',
            ]);
        }

        // Kos Putra Kampung Baru - 3 images
        if ($kos2) {
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos2->id,
                'url_gambar' => 'kos-putra-kampung-baru-1.png',
                'tipe_gambar' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos2->id,
                'url_gambar' => 'kos-putra-kampung-baru-2.png',
                'tipe_gambar' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos2->id,
                'url_gambar' => 'kos-putra-kampung-baru-3.png',
                'tipe_gambar' => 'common_area',
            ]);
        }

        // Kos Putri Melati - 3 images
        if ($kos3) {
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos3->id,
                'url_gambar' => 'kos-putri-melati-1.png',
                'tipe_gambar' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos3->id,
                'url_gambar' => 'kos-putri-melati-2.png',
                'tipe_gambar' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos3->id,
                'url_gambar' => 'kos-putri-melati-3.png',
                'tipe_gambar' => 'common_area',
            ]);
        }

        // Kos Putra Ali - 3 images
        if ($kos4) {
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos4->id,
                'url_gambar' => 'kos-putra-ali-1.png',
                'tipe_gambar' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos4->id,
                'url_gambar' => 'kos-putra-ali-2.png',
                'tipe_gambar' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'id_kos' => $kos4->id,
                'url_gambar' => 'kos-putra-ali-3.png',
                'tipe_gambar' => 'common_area',
            ]);
        }
    }
}

