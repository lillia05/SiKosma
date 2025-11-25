# Folder Images

Folder ini digunakan untuk menyimpan logo yang bisa di-commit ke git.

## Logo

Letakkan file `sikosma-logo.png` di folder ini agar logo otomatis muncul saat pull dari GitHub.

## Cara Menggunakan

1. **Letakkan logo asli** dengan nama `sikosma-logo.png` dan/atau `sikosma-logo-admin.png` di folder ini.
2. **Commit/push** file tersebut agar tim lain mendapat logonya saat pull.
3. **Setelah pull**, jalankan `php artisan storage:link` agar `public/storage/` mengarah ke `storage/app/public` sehingga helper logo bisa mengakses file.
4. **Pastikan `.env` memiliki `APP_URL` yang sesuai** (misal `http://localhost:8000`) agar `Storage::url(...)` memproduksi URL dengan port yang benar.
5. **Opsional**: jalankan `php artisan db:seed --class=LogoSeeder` agar logo dari folder ini otomatis dicopy ke `storage/app/public/logos/`.

## Catatan

- Logo akan otomatis di-copy ke storage saat seeder dijalankan.
- Jika logo tidak ada, aplikasi menggunakan SVG fallback yang sudah ada di view.
- Jangan lupa jalankan `npm run dev`/`npm run build` dan `php artisan serve --port=8000` (atau port lain yang sama dengan `APP_URL`) saat menjalankan aplikasi lokal.

