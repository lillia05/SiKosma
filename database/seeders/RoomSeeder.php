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
        $kos1 = Kos::where('name', 'Kos Putri Sahara')->first();
        $kos2 = Kos::where('name', 'Kos Putra Kampung Baru')->first();
        $kos3 = Kos::where('name', 'Kos Putri Melati')->first();
        $kos4 = Kos::where('name', 'Kos Putra Ali')->first();

        // Rooms for Kos Putri Sahara
        for ($i = 1; $i <= 8; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'kos_id' => $kos1->id,
                'room_number' => (string)$i,
                'price_per_year' => 7000000,
                'room_size' => 18, // 6 x 3 meters
                'facilities' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Dapur Umum, Kamar Mandi Dalam, Kipas Angin, Lemari',
                'status' => $i <= 3 ? 'Terisi' : 'Tersedia',
            ]);
        }

        // Rooms for Kos Putra Kampung Baru
        for ($i = 1; $i <= 8; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'kos_id' => $kos2->id,
                'room_number' => (string)$i,
                'price_per_year' => 7000000,
                'room_size' => 16,
                'facilities' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Parkir, Dapur Bersama, AC, Lemari',
                'status' => $i <= 2 ? 'Terisi' : 'Tersedia',
            ]);
        }

        // Rooms for Kos Putri Melati
        for ($i = 1; $i <= 6; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'kos_id' => $kos3->id,
                'room_number' => (string)$i,
                'price_per_year' => 7000000,
                'room_size' => 15,
                'facilities' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Lemari, Kamar Mandi Dalam, Dapur Bersama',
                'status' => $i <= 4 ? 'Terisi' : 'Tersedia',
            ]);
        }

        // Rooms for Kos Putra Ali
        for ($i = 1; $i <= 8; $i++) {
            Room::create([
                'id' => Str::uuid(),
                'kos_id' => $kos4->id,
                'room_number' => (string)$i,
                'price_per_year' => 6500000,
                'room_size' => 14,
                'facilities' => 'Ranjang, Kasur, Meja Belajar, Kursi, WiFi, Lemari',
                'status' => $i <= 3 ? 'Terisi' : 'Tersedia',
            ]);
        }
    }
}

