<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LogoSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure logos directory exists
        if (!Storage::disk('public')->exists('logos')) {
            Storage::disk('public')->makeDirectory('logos');
        }

        // Check if logo file exists
        $logoFilename = 'sikosma-logo.png';
        
        if (!Storage::disk('public')->exists('logos/' . $logoFilename)) {
            $this->command->info('');
            $this->command->warn('⚠️  Logo file belum ditemukan!');
            $this->command->info('Silakan copy file logo ke: storage/app/public/logos/sikosma-logo.png');
            $this->command->info('Format yang didukung: PNG, JPG, JPEG');
            $this->command->info('');
        } else {
            $this->command->info('✅ Logo file ditemukan di: storage/app/public/logos/sikosma-logo.png');
        }
    }
}

