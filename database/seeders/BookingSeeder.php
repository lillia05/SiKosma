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
        $kos1 = Kos::where('name', 'Kos Putri Sahara')->first();
        $kos2 = Kos::where('name', 'Kos Putra Kampung Baru')->first();
        $kos4 = Kos::where('name', 'Kos Putra Ali')->first();

        // Get rooms
        $room1 = Room::where('kos_id', $kos1->id)->where('room_number', '3')->first();
        $room2 = Room::where('kos_id', $kos2->id)->where('room_number', '5')->first();
        $room3 = Room::where('kos_id', $kos1->id)->where('room_number', '7')->first();
        $room4 = Room::where('kos_id', $kos4->id)->where('room_number', '2')->first();

        // Booking 1
        Booking::create([
            'id' => Str::uuid(),
            'booking_id' => 'BK001',
            'user_id' => $lekok->id,
            'room_id' => $room1->id,
            'kos_id' => $kos1->id,
            'start_date' => '2025-01-15',
            'duration_years' => 1,
            'end_date' => '2026-01-15',
            'total_price' => 7000000,
            'status' => 'CONFIRMED',
        ]);

        // Booking 2
        Booking::create([
            'id' => Str::uuid(),
            'booking_id' => 'BK002',
            'user_id' => $lifia->id,
            'room_id' => $room2->id,
            'kos_id' => $kos2->id,
            'start_date' => '2025-02-01',
            'duration_years' => 2,
            'end_date' => '2027-02-01',
            'total_price' => 14000000,
            'status' => 'PENDING',
        ]);

        // Booking 3
        Booking::create([
            'id' => Str::uuid(),
            'booking_id' => 'BK003',
            'user_id' => $muhammad->id,
            'room_id' => $room3->id,
            'kos_id' => $kos1->id,
            'start_date' => '2024-12-20',
            'duration_years' => 1,
            'end_date' => '2025-12-20',
            'total_price' => 7000000,
            'status' => 'COMPLETED',
        ]);

        // Booking 4
        Booking::create([
            'id' => Str::uuid(),
            'booking_id' => 'BK004',
            'user_id' => $lekok->id,
            'room_id' => $room4->id,
            'kos_id' => $kos4->id,
            'start_date' => '2025-03-10',
            'duration_years' => 1,
            'end_date' => '2026-03-10',
            'total_price' => 6500000,
            'status' => 'CONFIRMED',
        ]);
    }
}

