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

        // Kos 1 - Kos Putri Sahara (Kampung Baru)
        $kos1 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $akmal->id,
            'nama' => 'Kos Putri Sahara',
            'deskripsi' => 'Kamar berukuran 6 x 3 meter dengan kamar mandi berada di dalam kamar. Letak koasan strategis dekat dengan pasar dan kampus. Jalan kaki dari koasan ke kampus sekitar 15 menit. Jam malam sampai pukul 22.00 WIB. Tamu boleh menginap maksimal 3x setiap bulannya lebih dari 3x akan dikenakan charge.',
            'nomor_telepon' => '0821234567',
            'tipe' => 'Putri',
            'alamat' => 'Jl. Bumi Manti I Gg.Madinah II No. 62 Kampung Baru',
            'kota' => 'Kampung Baru',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 0,
            'total_ulasan' => 0,
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
            'kota' => 'Kampung Baru',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 0,
            'total_ulasan' => 0,
        ]);

        // Kos 3 - Kos Putri Melati (Gedong Meneng)
        $kos3 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $wisma->id,
            'nama' => 'Kos Putri Melati',
            'deskripsi' => 'Kos putri yang nyaman dengan fasilitas lengkap dan aman.',
            'nomor_telepon' => '0822234567',
            'tipe' => 'Putri',
            'alamat' => 'Jl. Gatot Subroto No. 45 Gedong Meneng',
            'kota' => 'Gedong Meneng',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 0,
            'total_ulasan' => 0,
        ]);

        // Kos 4 - Kos Putra Ali (Gedong Meneng)
        $kos4 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $lia->id,
            'nama' => 'Kos Putra Ali',
            'deskripsi' => 'Kos putra dengan harga terjangkau dan lokasi strategis.',
            'nomor_telepon' => '0823234567',
            'tipe' => 'Putra',
            'alamat' => 'Jl. Ahmad Yani No. 10 Gedong Meneng',
            'kota' => 'Gedong Meneng',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Disetujui',
            'rating' => 0,
            'total_ulasan' => 0,
        ]);

        // Kos 5 - Kos Campur Adam (Kampung Baru)
        $kos5 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $akmal->id,
            'nama' => 'Kos Campur Adam',
            'deskripsi' => 'Kos campur yang nyaman untuk mahasiswa putra dan putri. Lokasi strategis dekat kampus dan pusat perbelanjaan.',
            'nomor_telepon' => '0821234567',
            'tipe' => 'Campur',
            'alamat' => 'Jl. Kampung Baru No. 50',
            'kota' => 'Kampung Baru',
            'tautan_google_maps' => 'https://www.google.com/maps?q=Kampung+Baru,+Bandar+Lampung',
            'status' => 'Disetujui',
            'rating' => 0,
            'total_ulasan' => 0,
        ]);

        // Kos 6 - Kos Putri Aisyah (Status Menunggu - untuk testing verifikasi)
        $kos6 = Kos::create([
            'id' => Str::uuid(),
            'id_pengguna' => $lia->id,
            'nama' => 'Kos Putri Aisyah',
            'deskripsi' => 'Kos putri yang baru dibangun dengan fasilitas modern. Kamar berukuran 4 x 3 meter dengan kamar mandi dalam. Lokasi strategis dekat kampus Unila. Fasilitas lengkap termasuk WiFi, parkir, dapur umum, dan keamanan 24 jam.',
            'nomor_telepon' => '0823234567',
            'tipe' => 'Putri',
            'alamat' => 'Jl. Bumi Manti I Gg.Madinah I No. 111 Kampung Baru',
            'kota' => 'Kampung Baru',
            'tautan_google_maps' => 'https://maps.google.com',
            'status' => 'Menunggu',
            'rating' => 0,
            'total_ulasan' => 0,
        ]);

        // Kos 7 - Kos Putri Bunga (dibuat tahun 2022 untuk testing laporan selesai)
        $budi = User::where('email', 'budi@pemilik.com')->first();
        if ($budi) {
            $kos7 = Kos::create([
                'id' => Str::uuid(),
                'id_pengguna' => $budi->id,
                'nama' => 'Kos Putri Bunga',
                'deskripsi' => 'Kos putri yang nyaman dengan fasilitas lengkap. Lokasi strategis dekat kampus dan pusat perbelanjaan.',
                'nomor_telepon' => '0841234567',
                'tipe' => 'Putri',
                'alamat' => 'Jl. Sudirman No. 100',
                'kota' => 'Bandar Lampung',
                'tautan_google_maps' => 'https://maps.google.com',
                'status' => 'Disetujui',
                'rating' => 0,
                'total_ulasan' => 0,
                'created_at' => '2022-01-15 10:00:00',
                'updated_at' => '2022-01-15 10:00:00',
            ]);
        }
    }
}

