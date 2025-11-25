# Folder Images

Folder ini digunakan untuk menyimpan logo yang bisa di-commit ke git.

## Logo

Letakkan file `sikosma-logo.png` di folder ini agar logo otomatis muncul saat pull dari GitHub.

## Cara Menggunakan

1. **Letakkan logo asli** dengan nama `sikosma-logo.png` di folder ini
2. **Commit dan push** file tersebut ke git
3. **Saat pull**, logo akan otomatis tersedia
4. **Saat menjalankan seeder** (`php artisan db:seed`), logo akan otomatis di-copy dari `public/images/` ke `storage/app/public/logos/`

## Catatan

- Logo akan otomatis di-copy ke storage saat seeder dijalankan
- Jika logo tidak ada, aplikasi akan menggunakan SVG fallback yang sudah ada di view
- Pastikan file logo di-commit ke git agar semua team member mendapat logo yang sama

