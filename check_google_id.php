<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

if (Schema::hasColumn('users', 'google_id')) {
    echo "✓ Kolom 'google_id' sudah ada di tabel 'users'\n";
} else {
    echo "✗ Kolom 'google_id' BELUM ada di tabel 'users'\n";
    echo "Jalankan: php artisan migrate\n";
}
