<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LogoSeeder::class,
            UserSeeder::class,
            KosSeeder::class,
            RoomSeeder::class,
            KosImageSeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
