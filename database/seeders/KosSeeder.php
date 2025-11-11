<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kos;
use App\Models\User;
use Illuminate\Support\Str;

class KosSeeder extends Seeder
{
    public function run(): void
    {
        // Get users by email
        $akmal = User::where('email', 'akmal@email.com')->first();
        $wisma = User::where('email', 'wisma@email.com')->first();
        $lia = User::where('email', 'lia@email.com')->first();

        // Kos 1 - Kos Putri Sahara
        $kos1 = Kos::create([
            'id' => Str::uuid(),
            'user_id' => $akmal->id,
            'name' => 'Kos Putri Sahara',
            'description' => 'Kamar berukuran 6 x 3 meter dengan kamar mandi berada di dalam kamar. Letak koasan strategis dekat dengan pasar dan kampus. Jalan kaki dari koasan ke kampus sekitar 15 menit. Jam malam sampai pukul 22.00 WIB. Tamu boleh menginap maksimal 3x setiap bulannya lebih dari 3x akan dikenakan charge.',
            'phone_number' => '0821234567',
            'type' => 'Putri',
            'address' => 'Jl. Bumi Manti I Gg.Madinah II No. 62 Kampung Baru',
            'city' => 'Bandar Lampung',
            'google_maps_link' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.9,
            'total_reviews' => 5,
        ]);

        // Kos 2 - Kos Putra Kampung Baru
        $kos2 = Kos::create([
            'id' => Str::uuid(),
            'user_id' => $akmal->id,
            'name' => 'Kos Putra Kampung Baru',
            'description' => 'Kos nyaman dan strategis untuk pelajar. Dekat dengan beberapa kampus dan pusat perbelanjaan.',
            'phone_number' => '0821234567',
            'type' => 'Putra',
            'address' => 'Jl. Kampung Baru No. 20',
            'city' => 'Bandar Lampung',
            'google_maps_link' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.5,
            'total_reviews' => 3,
        ]);

        // Kos 3 - Kos Putri Melati
        $kos3 = Kos::create([
            'id' => Str::uuid(),
            'user_id' => $wisma->id,
            'name' => 'Kos Putri Melati',
            'description' => 'Kos putri yang nyaman dengan fasilitas lengkap dan aman.',
            'phone_number' => '0822234567',
            'type' => 'Putri',
            'address' => 'Jl. Gatot Subroto No. 45',
            'city' => 'Bandar Lampung',
            'google_maps_link' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.8,
            'total_reviews' => 8,
        ]);

        // Kos 4 - Kos Putra Ali
        $kos4 = Kos::create([
            'id' => Str::uuid(),
            'user_id' => $lia->id,
            'name' => 'Kos Putra Ali',
            'description' => 'Kos putra dengan harga terjangkau dan lokasi strategis.',
            'phone_number' => '0823234567',
            'type' => 'Putra',
            'address' => 'Jl. Ahmad Yani No. 10',
            'city' => 'Bandar Lampung',
            'google_maps_link' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.2,
            'total_reviews' => 2,
        ]);
    }
}

