<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LogoSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure logos directory exists
        if (!Storage::disk('public')->exists('logos')) {
            Storage::disk('public')->makeDirectory('logos');
        }

        $logoFilename = 'sikosma-logo.png';
        $logoPath = 'logos/' . $logoFilename;
        
        // Cek apakah logo sudah ada di storage
        if (!Storage::disk('public')->exists($logoPath)) {
            // Cek apakah ada logo di public/images (yang di-push ke git)
            $publicLogoPath = public_path('images/' . $logoFilename);
            
            if (File::exists($publicLogoPath)) {
                // Copy dari public/images ke storage
                $logoContent = File::get($publicLogoPath);
                Storage::disk('public')->put($logoPath, $logoContent);
                $this->command->info('✅ Logo berhasil di-copy dari public/images ke storage');
            } else {
                $this->command->warn('⚠️  Logo tidak ditemukan di public/images/sikosma-logo.png');
                $this->command->info('💡 Tip: Letakkan logo di public/images/sikosma-logo.png dan push ke git agar otomatis muncul saat pull');
            }
        } else {
            $this->command->info('✅ Logo file sudah ada di: storage/app/public/' . $logoPath);
        }
    }
}

