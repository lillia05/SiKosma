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
            'name' => 'Muhammad Alvin',
            'email' => 'admin@sikosma.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '0812345678',
            'address' => 'Jl. Admin No. 1',
            'city' => 'Bandar Lampung',
            'gender' => 'Putra',
            'status' => 'Aktif',
        ]);

        // Kos owners
        $akmal = User::create([
            'id' => Str::uuid(),
            'name' => 'Akmal Hidayat',
            'email' => 'akmal@email.com',
            'password' => Hash::make('password123'),
            'role' => 'pemilik',
            'phone' => '0821234567',
            'address' => 'Jl. Bumi Manti I Gg.Madinah II No. 62 Kampung Baru',
            'city' => 'Bandar Lampung',
            'gender' => 'Putra',
            'bank_name' => 'BRI',
            'account_number' => '0123456789',
            'status' => 'Aktif',
        ]);

        $wisma = User::create([
            'id' => Str::uuid(),
            'name' => 'Wisma Putri',
            'email' => 'wisma@email.com',
            'password' => Hash::make('password123'),
            'role' => 'pemilik',
            'phone' => '0822234567',
            'address' => 'Jl. Gatot Subroto No. 45',
            'city' => 'Bandar Lampung',
            'gender' => 'Putri',
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'status' => 'Aktif',
        ]);

        $lia = User::create([
            'id' => Str::uuid(),
            'name' => 'Lia Wijaya',
            'email' => 'lia@email.com',
            'password' => Hash::make('password123'),
            'role' => 'pemilik',
            'phone' => '0823234567',
            'address' => 'Jl. Ahmad Yani No. 10',
            'city' => 'Bandar Lampung',
            'gender' => 'Putri',
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'status' => 'Aktif',
        ]);

        // Regular users (Pencari Kos)
        $lekok = User::create([
            'id' => Str::uuid(),
            'name' => 'Lekok Indah Lia',
            'email' => 'lekokindahlila@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pencari',
            'phone' => '0831234567',
            'address' => 'Jl. Pattimura No. 5',
            'city' => 'Bandar Lampung',
            'gender' => 'Putri',
            'status' => 'Aktif',
        ]);

        $lifia = User::create([
            'id' => Str::uuid(),
            'name' => 'Lifia Anasywa',
            'email' => 'lifiananasywa@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pencari',
            'phone' => '0832234567',
            'address' => 'Jl. Teuku Umar No. 20',
            'city' => 'Bandar Lampung',
            'gender' => 'Putri',
            'status' => 'Aktif',
        ]);

        $muhammad = User::create([
            'id' => Str::uuid(),
            'name' => 'Muhammad Akmal',
            'email' => 'muhammadakmal@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pencari',
            'phone' => '0833234567',
            'address' => 'Jl. Merdeka No. 15',
            'city' => 'Bandar Lampung',
            'gender' => 'Putra',
            'status' => 'Aktif',
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
    }
}

