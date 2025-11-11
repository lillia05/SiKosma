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
        // Get bookings by booking_id
        $booking1 = Booking::where('booking_id', 'BK001')->first();
        $booking2 = Booking::where('booking_id', 'BK002')->first();
        $booking3 = Booking::where('booking_id', 'BK003')->first();
        $booking4 = Booking::where('booking_id', 'BK004')->first();

        // Get users
        $lekok = User::where('email', 'lekokindahlila@gmail.com')->first();
        $lifia = User::where('email', 'lifiananasywa@gmail.com')->first();
        $muhammad = User::where('email', 'muhammadakmal@gmail.com')->first();

        // Payment 1
        Payment::create([
            'id' => Str::uuid(),
            'booking_id' => $booking1->id,
            'user_id' => $lekok->id,
            'amount' => 7000000,
            'payment_method' => 'Transfer Bank',
            'sender_bank_name' => 'Bank Negara Indonesia (BNI)',
            'sender_account_number' => '9876543210',
            'sender_name' => 'Lekok Indah Lia',
            'status' => 'Verified',
        ]);

        // Payment 2
        Payment::create([
            'id' => Str::uuid(),
            'booking_id' => $booking2->id,
            'user_id' => $lifia->id,
            'amount' => 14000000,
            'payment_method' => 'Transfer Bank',
            'sender_bank_name' => 'Bank Mandiri',
            'sender_account_number' => '1122334455',
            'sender_name' => 'Lifia Anasywa',
            'status' => 'Pending',
        ]);

        // Payment 3
        Payment::create([
            'id' => Str::uuid(),
            'booking_id' => $booking3->id,
            'user_id' => $muhammad->id,
            'amount' => 7000000,
            'payment_method' => 'Transfer Bank',
            'sender_bank_name' => 'Bank BCA',
            'sender_account_number' => '5566778899',
            'sender_name' => 'Muhammad Akmal',
            'status' => 'Verified',
        ]);

        // Payment 4
        Payment::create([
            'id' => Str::uuid(),
            'booking_id' => $booking4->id,
            'user_id' => $lekok->id,
            'amount' => 6500000,
            'payment_method' => 'Transfer Bank',
            'sender_bank_name' => 'Bank Negara Indonesia (BNI)',
            'sender_account_number' => '9876543210',
            'sender_name' => 'Lekok Indah Lia',
            'status' => 'Verified',
        ]);
    }
}

