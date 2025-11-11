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
        $kos1 = Kos::where('name', 'Kos Putri Sahara')->first();
        $kos2 = Kos::where('name', 'Kos Putra Kampung Baru')->first();
        $kos3 = Kos::where('name', 'Kos Putri Melati')->first();
        $kos4 = Kos::where('name', 'Kos Putra Ali')->first();

        // Kos Putri Sahara - 3 images
        if ($kos1) {
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos1->id,
                'image_url' => 'kos-putri-sahara-1.png',
                'image_type' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos1->id,
                'image_url' => 'kos-putri-sahara-2.png',
                'image_type' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos1->id,
                'image_url' => 'kos-putri-sahara-3.png',
                'image_type' => 'common_area',
            ]);
        }

        // Kos Putra Kampung Baru - 3 images
        if ($kos2) {
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos2->id,
                'image_url' => 'kos-putra-kampung-baru-1.png',
                'image_type' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos2->id,
                'image_url' => 'kos-putra-kampung-baru-2.png',
                'image_type' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos2->id,
                'image_url' => 'kos-putra-kampung-baru-3.png',
                'image_type' => 'common_area',
            ]);
        }

        // Kos Putri Melati - 3 images
        if ($kos3) {
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos3->id,
                'image_url' => 'kos-putri-melati-1.png',
                'image_type' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos3->id,
                'image_url' => 'kos-putri-melati-2.png',
                'image_type' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos3->id,
                'image_url' => 'kos-putri-melati-3.png',
                'image_type' => 'common_area',
            ]);
        }

        // Kos Putra Ali - 3 images
        if ($kos4) {
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos4->id,
                'image_url' => 'kos-putra-ali-1.png',
                'image_type' => 'general',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos4->id,
                'image_url' => 'kos-putra-ali-2.png',
                'image_type' => 'room',
            ]);
            KosImage::create([
                'id' => Str::uuid(),
                'kos_id' => $kos4->id,
                'image_url' => 'kos-putra-ali-3.png',
                'image_type' => 'common_area',
            ]);
        }
    }
}

