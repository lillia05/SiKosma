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
            'id_pengguna' => $akmal->id,
            'nama' => 'Kos Putri Sahara',
            'deskripsi' => 'Kamar berukuran 6 x 3 meter dengan kamar mandi berada di dalam kamar. Letak koasan strategis dekat dengan pasar dan kampus. Jalan kaki dari koasan ke kampus sekitar 15 menit. Jam malam sampai pukul 22.00 WIB. Tamu boleh menginap maksimal 3x setiap bulannya lebih dari 3x akan dikenakan charge.',
            'nomor_telepon' => '0821234567',
            'tipe' => 'Putri',
            'alamat' => 'Jl. Bumi Manti I Gg.Madinah II No. 62 Kampung Baru',
            'kota' => 'Bandar Lampung',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.9,
            'total_ulasan' => 5,
        ]);

        // Kos 2 - Kos Putra Kampung Baru
        $kos2 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $akmal->id,
            'nama' => 'Kos Putra Kampung Baru',
            'deskripsi' => 'Kos nyaman dan strategis untuk pelajar. Dekat dengan beberapa kampus dan pusat perbelanjaan.',
            'nomor_telepon' => '0821234567',
            'tipe' => 'Putra',
            'alamat' => 'Jl. Kampung Baru No. 20',
            'kota' => 'Bandar Lampung',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.5,
            'total_ulasan' => 3,
        ]);

        // Kos 3 - Kos Putri Melati
        $kos3 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $wisma->id,
            'nama' => 'Kos Putri Melati',
            'deskripsi' => 'Kos putri yang nyaman dengan fasilitas lengkap dan aman.',
            'nomor_telepon' => '0822234567',
            'tipe' => 'Putri',
            'alamat' => 'Jl. Gatot Subroto No. 45',
            'kota' => 'Bandar Lampung',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.8,
            'total_ulasan' => 8,
        ]);

        // Kos 4 - Kos Putra Ali
        $kos4 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $lia->id,
            'nama' => 'Kos Putra Ali',
            'deskripsi' => 'Kos putra dengan harga terjangkau dan lokasi strategis.',
            'nomor_telepon' => '0823234567',
            'tipe' => 'Putra',
            'alamat' => 'Jl. Ahmad Yani No. 10',
            'kota' => 'Bandar Lampung',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 4.2,
            'total_ulasan' => 2,
        ]);
    }
}

