<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'id' => Str::uuid(),
            'nama' => 'Muhammad Alvin',
            'email' => 'admin@sikosma.com',
            'kata_sandi' => Hash::make('admin123'),
            'peran' => 'admin',
            'telepon' => '0812345678',
            'alamat' => 'Jl. Admin No. 1',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putra',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        // Kos owners
        $akmal = User::create([
            'id' => Str::uuid(),
            'nama' => 'Akmal Hidayat',
            'email' => 'akmal@email.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pemilik',
            'telepon' => '0821234567',
            'alamat' => 'Jl. Bumi Manti I Gg.Madinah II No. 62 Kampung Baru',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putra',
            'nama_bank' => 'BRI',
            'nomor_rekening' => '0123456789',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        $wisma = User::create([
            'id' => Str::uuid(),
            'nama' => 'Wisma Putri',
            'email' => 'wisma@email.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pemilik',
            'telepon' => '0822234567',
            'alamat' => 'Jl. Gatot Subroto No. 45',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putri',
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        $lia = User::create([
            'id' => Str::uuid(),
            'nama' => 'Lia Wijaya',
            'email' => 'lia@email.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pemilik',
            'telepon' => '0823234567',
            'alamat' => 'Jl. Ahmad Yani No. 10',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putri',
            'nama_bank' => 'Mandiri',
            'nomor_rekening' => '0987654321',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        // Regular users (Pencari Kos)
        $lekok = User::create([
            'id' => Str::uuid(),
            'nama' => 'Lekok Indah Lia',
            'email' => 'lekokindahlila@gmail.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pencari',
            'telepon' => '0831234567',
            'alamat' => 'Jl. Pattimura No. 5',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putri',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        $lifia = User::create([
            'id' => Str::uuid(),
            'nama' => 'Lifia Anasywa',
            'email' => 'lifiananasywa@gmail.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pencari',
            'telepon' => '0832234567',
            'alamat' => 'Jl. Teuku Umar No. 20',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putri',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        $muhammad = User::create([
            'id' => Str::uuid(),
            'nama' => 'Muhammad Akmal',
            'email' => 'muhammadakmal@gmail.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pencari',
            'telepon' => '0833234567',
            'alamat' => 'Jl. Merdeka No. 15',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putra',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        // Pemilik kos baru untuk testing laporan selesai (kos dibuat tahun 2022)
        $budi = User::create([
            'id' => Str::uuid(),
            'nama' => 'Budi Santoso',
            'email' => 'budi@pemilik.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pemilik',
            'telepon' => '0841234567',
            'alamat' => 'Jl. Sudirman No. 100',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putra',
            'nama_bank' => 'BRI',
            'nomor_rekening' => '1111222233',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        // Pencari kos baru untuk testing laporan selesai (booking tahun 2022)
        $sari = User::create([
            'id' => Str::uuid(),
            'nama' => 'Sari Dewi',
            'email' => 'sari@pencari.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pencari',
            'telepon' => '0842234567',
            'alamat' => 'Jl. Diponegoro No. 25',
            'kota' => 'Bandar Lampung',
            'jenis_kelamin' => 'Putri',
            'status' => 'Aktif',
            'email_verified_at' => now(),
        ]);

        // Store user IDs for use in other seeders
        $this->command->info('User IDs created:');
        $this->command->info('Admin: ' . $admin->id);
        $this->command->info('Akmal: ' . $akmal->id);
        $this->command->info('Wisma: ' . $wisma->id);
        $this->command->info('Lia: ' . $lia->id);
        $this->command->info('Lekok: ' . $lekok->id);
        $this->command->info('Lifia: ' . $lifia->id);
        $this->command->info('Muhammad: ' . $muhammad->id);
        $this->command->info('Budi: ' . $budi->id);
        $this->command->info('Sari: ' . $sari->id);
    }
}

