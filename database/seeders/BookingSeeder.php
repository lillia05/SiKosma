<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use App\Models\Kos;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Get users by email
        $lekok = User::where('email', 'lekokindahlila@gmail.com')->first();
        $lifia = User::where('email', 'lifiananasywa@gmail.com')->first();
        $muhammad = User::where('email', 'muhammadakmal@gmail.com')->first();

        // Get kos
        $kos1 = Kos::where('nama', 'Kos Putri Sahara')->first();
        $kos2 = Kos::where('nama', 'Kos Putra Kampung Baru')->first();
        $kos4 = Kos::where('nama', 'Kos Putra Ali')->first();

        // Get rooms
        $room1 = Room::where('id_kos', $kos1->id)->where('nomor_kamar', '3')->first();
        $room2 = Room::where('id_kos', $kos2->id)->where('nomor_kamar', '5')->first();
        $room3 = Room::where('id_kos', $kos1->id)->where('nomor_kamar', '7')->first();
        $room4 = Room::where('id_kos', $kos4->id)->where('nomor_kamar', '2')->first();

        // Booking 1
        Booking::create([
            'id' => Str::uuid(),
            'id_pemesanan' => 'BK001',
            'id_pengguna' => $lekok->id,
            'id_kamar' => $room1->id,
            'id_kos' => $kos1->id,
            'tanggal_mulai' => '2025-01-15',
            'durasi_tahun' => 1,
            'tanggal_selesai' => '2026-01-15',
            'total_harga' => 7000000,
            'status' => 'CONFIRMED',
        ]);

        // Booking 2
        Booking::create([
            'id' => Str::uuid(),
            'id_pemesanan' => 'BK002',
            'id_pengguna' => $lifia->id,
            'id_kamar' => $room2->id,
            'id_kos' => $kos2->id,
            'tanggal_mulai' => '2025-02-01',
            'durasi_tahun' => 2,
            'tanggal_selesai' => '2027-02-01',
            'total_harga' => 14000000,
            'status' => 'PENDING',
        ]);

        // Booking 3
        Booking::create([
            'id' => Str::uuid(),
            'id_pemesanan' => 'BK003',
            'id_pengguna' => $muhammad->id,
            'id_kamar' => $room3->id,
            'id_kos' => $kos1->id,
            'tanggal_mulai' => '2024-12-20',
            'durasi_tahun' => 1,
            'tanggal_selesai' => '2025-12-20',
            'total_harga' => 7000000,
            'status' => 'COMPLETED',
        ]);

        // Booking 4
        Booking::create([
            'id' => Str::uuid(),
            'id_pemesanan' => 'BK004',
            'id_pengguna' => $lekok->id,
            'id_kamar' => $room4->id,
            'id_kos' => $kos4->id,
            'tanggal_mulai' => '2025-03-10',
            'durasi_tahun' => 1,
            'tanggal_selesai' => '2026-03-10',
            'total_harga' => 6500000,
            'status' => 'CONFIRMED',
        ]);
    }
}

