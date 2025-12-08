# Setup Google OAuth untuk Login dengan Google

## 1. Setup di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project Anda atau buat project baru
3. Buka **APIs & Services** > **Credentials**
4. Klik **Create Credentials** > **OAuth client ID**
5. Pilih **Web application**
6. Isi:
   - **Name**: SiKosma (atau nama yang Anda inginkan)
   - **Authorized JavaScript origins**: 
     - `http://localhost:8000` (untuk development)
     - `https://yourdomain.com` (untuk production)
   - **Authorized redirect URIs**: 
     - `http://localhost:8000/auth/google/callback` (untuk development)
     - `https://yourdomain.com/auth/google/callback` (untuk production)
7. Klik **Create**
8. Copy **Client ID** dan **Client Secret**

## 2. Setup di `.env`

Tambahkan konfigurasi berikut di file `.env`:

```env
GOOGLE_CLIENT_ID=your_client_id_dari_google_cloud_console
GOOGLE_CLIENT_SECRET=your_client_secret_dari_google_cloud_console
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**PENTING**: 
- Untuk development, gunakan `http://localhost:8000/auth/google/callback`
- Untuk production, gunakan `https://yourdomain.com/auth/google/callback`
- Pastikan redirect URI di `.env` **SAMA PERSIS** dengan yang ada di Google Cloud Console

## 3. Clear Cache

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

## 4. Troubleshooting

### Error: "redirect_uri_mismatch"
- **Penyebab**: Redirect URI di Google Cloud Console tidak sesuai dengan yang di aplikasi
- **Solusi**: 
  1. Pastikan redirect URI di Google Cloud Console adalah: `http://localhost:8000/auth/google/callback`
  2. Pastikan `GOOGLE_REDIRECT_URI` di `.env` sama persis
  3. Clear cache: `php artisan config:clear`

### Error: "invalid_grant" atau "code expired"
- **Penyebab**: Kode autentikasi dari Google sudah kedaluwarsa
- **Solusi**: Coba login dengan Google lagi

### Error: "Class not found"
- **Penyebab**: Package Socialite belum terinstall
- **Solusi**: 
  ```bash
  composer require laravel/socialite
  composer dump-autoload
  php artisan optimize:clear
  ```

### Kembali ke halaman pilih role setelah login Google
- **Penyebab**: Ada error di callback yang tidak ter-handle dengan baik
- **Solusi**: 
  1. Cek log di `storage/logs/laravel.log`
  2. Pastikan redirect URI sudah benar
  3. Pastikan `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` sudah benar di `.env`

## 5. Verifikasi Setup

1. Pastikan redirect URI di Google Cloud Console adalah: `http://localhost:8000/auth/google/callback`
2. Pastikan `.env` sudah diisi dengan benar
3. Pastikan sudah run `php artisan config:clear`
4. Coba login dengan Google
5. Cek log di `storage/logs/laravel.log` jika masih error

## 6. Catatan Penting

- **Redirect URI HARUS SAMA PERSIS** antara Google Cloud Console dan `.env`
- Jangan lupa clear cache setelah mengubah `.env`
- Untuk production, pastikan menggunakan HTTPS
- Redirect URI tidak boleh ada trailing slash (`/`)
