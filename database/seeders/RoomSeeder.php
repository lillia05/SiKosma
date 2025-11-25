<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Kos;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Get kos by name
        $kos1 = Kos::where('nama', 'Kos Putri Sahara')->first();
        $kos2 = Kos::where('nama', 'Kos Putra Kampung Baru')->first();
        $kos3 = Kos::where('nama', 'Kos Putri Melati')->first();
        $kos4 = Kos::where('nama', 'Kos Putra Ali')->first();
        $kos6 = Kos::where('nama', 'Kos Putri Aisyah')->first();

        // Rooms for Kos Putri Sahara
        for ($i = 1; $i <= 8; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'id_kos' => $kos1->id,
                'nomor_kamar' => (string)$i,
                'harga_per_tahun' => 7000000,
                'ukuran_kamar' => 18, // 6 x 3 meters
                'fasilitas' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Dapur Umum, Kamar Mandi Dalam, Kipas Angin, Lemari',
                'status' => $i <= 3 ? 'Terisi' : 'Tersedia',
            ]);
        }

        // Rooms for Kos Putra Kampung Baru
        for ($i = 1; $i <= 8; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'id_kos' => $kos2->id,
                'nomor_kamar' => (string)$i,
                'harga_per_tahun' => 7000000,
                'ukuran_kamar' => 16,
                'fasilitas' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Parkir, Dapur Bersama, AC, Lemari',
                'status' => $i <= 2 ? 'Terisi' : 'Tersedia',
            ]);
        }

        // Rooms for Kos Putri Melati
        for ($i = 1; $i <= 6; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'id_kos' => $kos3->id,
                'nomor_kamar' => (string)$i,
                'harga_per_tahun' => 7000000,
                'ukuran_kamar' => 15,
                'fasilitas' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Lemari, Kamar Mandi Dalam, Dapur Bersama',
                'status' => $i <= 4 ? 'Terisi' : 'Tersedia',
            ]);
        }

        // Rooms for Kos Putra Ali
        for ($i = 1; $i <= 8; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'id_kos' => $kos4->id,
                'nomor_kamar' => (string)$i,
                'harga_per_tahun' => 6500000,
                'ukuran_kamar' => 14,
                'fasilitas' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Lemari',
                'status' => $i <= 3 ? 'Terisi' : 'Tersedia',
            ]);
        }

        // Rooms for Kos Campur Adam
        $kos5 = Kos::where('nama', 'Kos Campur Adam')->first();
        if ($kos5) {
            for ($i = 1; $i <= 10; $i++) {
                Room::create([
                    'id' => Str::uuid(),
                    'id_kos' => $kos5->id,
                    'nomor_kamar' => (string)$i,
                    'harga_per_tahun' => 7500000,
                    'ukuran_kamar' => 20,
                    'fasilitas' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, AC, Kamar Mandi Dalam, Dapur Bersama, Parkir, Lemari',
                    'status' => $i <= 5 ? 'Terisi' : 'Tersedia',
                ]);
            }
        }

        // Rooms for Kos Putri Aisyah (Status Menunggu - untuk testing)
        if ($kos6) {
            for ($i = 1; $i <= 6; $i++) {
                Room::create([
                    'id' => Str::uuid(),
                    'id_kos' => $kos6->id,
                    'nomor_kamar' => (string)$i,
                    'harga_per_tahun' => 7200000,
                    'ukuran_kamar' => 12, // 4 x 3 meters
                    'fasilitas' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Kamar Mandi Dalam, Dapur Umum, Parkir, Lemari, Kipas Angin',
                    'status' => 'Tersedia', // Semua kamar tersedia karena kos baru
                ]);
            }
        }
    }
}

