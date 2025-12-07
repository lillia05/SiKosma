<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Get bookings by id_pemesanan
        $booking1 = Booking::where('id_pemesanan', 'BK001')->first();
        $booking2 = Booking::where('id_pemesanan', 'BK002')->first();
        $booking3 = Booking::where('id_pemesanan', 'BK003')->first();
        $booking4 = Booking::where('id_pemesanan', 'BK004')->first();

        // Get users
        $lekok = User::where('email', 'lekokindahlila@gmail.com')->first();
        $lifia = User::where('email', 'lifiananasywa@gmail.com')->first();
        $muhammad = User::where('email', 'muhammadakmal@gmail.com')->first();

        // Payment 1
        Payment::create([
            'id' => Str::uuid(),
            'id_pemesanan' => $booking1->id,
            'id_pengguna' => $lekok->id,
            'jumlah' => 7000000,
            'metode_pembayaran' => 'Transfer Bank',
            'nama_bank_pengirim' => 'Bank Negara Indonesia (BNI)',
            'nomor_rekening_pengirim' => '9876543210',
            'nama_pengirim' => 'Lekok Indah Lia',
            'status' => 'Verified',
        ]);

        // Payment 2
        Payment::create([
            'id' => Str::uuid(),
            'id_pemesanan' => $booking2->id,
            'id_pengguna' => $lifia->id,
            'jumlah' => 14000000,
            'metode_pembayaran' => 'Transfer Bank',
            'nama_bank_pengirim' => 'Bank Mandiri',
            'nomor_rekening_pengirim' => '1122334455',
            'nama_pengirim' => 'Lifia Anasywa',
            'status' => 'Pending',
        ]);

        // Payment 3
        Payment::create([
            'id' => Str::uuid(),
            'id_pemesanan' => $booking3->id,
            'id_pengguna' => $muhammad->id,
            'jumlah' => 7000000,
            'metode_pembayaran' => 'Transfer Bank',
            'nama_bank_pengirim' => 'Bank BCA',
            'nomor_rekening_pengirim' => '5566778899',
            'nama_pengirim' => 'Muhammad Akmal',
            'status' => 'Verified',
        ]);

        // Payment 4
        Payment::create([
            'id' => Str::uuid(),
            'id_pemesanan' => $booking4->id,
            'id_pengguna' => $lekok->id,
            'jumlah' => 6500000,
            'metode_pembayaran' => 'Transfer Bank',
            'nama_bank_pengirim' => 'Bank Negara Indonesia (BNI)',
            'nomor_rekening_pengirim' => '9876543210',
            'nama_pengirim' => 'Lekok Indah Lia',
            'status' => 'Verified',
        ]);

        // Payment 5 - untuk booking selesai tahun 2023 (untuk testing laporan selesai)
        $booking5 = Booking::where('id_pemesanan', 'BK005')->first();
        $sari = User::where('email', 'sari@pencari.com')->first();
        
        if ($booking5 && $sari) {
            Payment::create([
                'id' => Str::uuid(),
                'id_pemesanan' => $booking5->id,
                'id_pengguna' => $sari->id,
                'jumlah' => 6800000,
                'metode_pembayaran' => 'Transfer Bank',
                'nama_bank_pengirim' => 'Bank Mandiri',
                'nomor_rekening_pengirim' => '9988776655',
                'nama_pengirim' => 'Sari Dewi',
                'status' => 'Verified',
                'created_at' => '2022-01-25 10:00:00',
                'updated_at' => '2022-01-25 10:00:00',
            ]);
        }
    }
}

