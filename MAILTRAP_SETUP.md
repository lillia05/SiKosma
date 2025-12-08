# Setup Mailtrap untuk Email Verification di Laravel

## 📧 Apa itu Mailtrap?

Mailtrap adalah layanan email testing yang **AMAN** untuk development. Email yang dikirim aplikasi akan **TIDAK** benar-benar dikirim ke alamat email asli, melainkan ditangkap di Mailtrap inbox. Ini sangat cocok untuk testing karena:

- ✅ **Aman** - Email tidak benar-benar dikirim ke alamat asli
- ✅ **Gratis** - Ada plan gratis untuk testing
- ✅ **Real-time** - Email langsung muncul di dashboard
- ✅ **Cocok untuk seeder** - Email dari seeder tetap aman meskipun bukan email asli

---

## 🚀 Langkah 1: Daftar/Login ke Mailtrap

1. Buka website: **https://mailtrap.io/**
2. Klik **"Sign Up"** untuk daftar akun baru (gratis) atau **"Log In"** jika sudah punya akun
3. Anda bisa daftar dengan:
   - Email
   - Google Account
   - GitHub Account
4. Setelah login, Anda akan masuk ke dashboard Mailtrap

---

## 📬 Langkah 2: Buat atau Pilih Inbox

1. Di dashboard Mailtrap, di sidebar kiri, klik **"Email Testing"**
2. Klik **"Inboxes"** 
3. Anda akan melihat inbox default bernama **"My Inbox"** atau bisa klik **"Add Inbox"** untuk membuat baru
4. Klik pada inbox yang ingin digunakan (misalnya "My Inbox")

---

## 🔑 Langkah 3: Dapatkan Kredensial SMTP

### Cara 1: Melalui Integrations (Recommended)

1. Setelah memilih inbox, klik tab **"SMTP Settings"** di bagian atas
2. Scroll ke bawah, cari bagian **"Integrations"**
3. Pilih **"Laravel"** dari dropdown
4. Anda akan melihat konfigurasi yang sudah siap:

```
Host: smtp.mailtrap.io
Port: 2525
Username: [username Anda - panjang sekitar 20 karakter]
Password: [password Anda - panjang sekitar 20 karakter]
```

5. **Copy username dan password** ini (akan digunakan di file .env)
   - ⚠️ **PENTING**: Copy **SEMUA** karakter, jangan ada yang terpotong
   - Username biasanya panjang sekitar 15-20 karakter
   - Password biasanya panjang sekitar 15-20 karakter

### Cara 2: Melalui Show Credentials

1. Di tab **"SMTP Settings"**, klik tombol **"Show Credentials"** atau **"Credentials"**
2. Anda akan melihat:
   - **Host**: `smtp.mailtrap.io`
   - **Port**: `2525` (untuk Mailtrap, gunakan 2525)
   - **Username**: [username Anda]
   - **Password**: [password Anda]
3. **Copy username dan password** ini
   - ⚠️ **PENTING**: Pastikan copy lengkap tanpa spasi di awal/akhir

---

## ⚙️ Langkah 4: Update File .env

1. Buka file `.env` di root project Anda
2. Cari bagian konfigurasi email (biasanya di bagian bawah)
3. **HAPUS** konfigurasi email lama jika ada
4. Update dengan konfigurasi berikut:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@sikosma.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**⚠️ PENTING**: 
- Untuk **Mailtrap Sandbox** (testing), gunakan host: `sandbox.smtp.mailtrap.io`
- Untuk **Mailtrap Email API/SMTP** (production), gunakan host: `smtp.mailtrap.io`
- Dari gambar Mailtrap Anda, gunakan `sandbox.smtp.mailtrap.io`

**Ganti:**
- `your_mailtrap_username` → Username yang Anda copy dari Mailtrap (tanpa tanda kurung, tanpa spasi)
- `your_mailtrap_password` → Password yang Anda copy dari Mailtrap (tanpa tanda kurung, tanpa spasi)

**Contoh yang BENAR (dari gambar Mailtrap Anda):**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=17e3f91d9a69d3
MAIL_PASSWORD=password_lengkap_dari_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@sikosma.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**⚠️ PENTING**: 
- Copy **SEMUA** karakter password dari Mailtrap (biasanya panjang 15-20 karakter)
- Jangan ada spasi di awal/akhir
- Jangan pakai tanda kutip untuk username/password

**Contoh yang SALAH:**
```env
MAIL_USERNAME="17e3f91d9a69d3"  ❌ Jangan pakai tanda kutip
MAIL_USERNAME= 17e3f91d9a69d3   ❌ Jangan ada spasi di awal
MAIL_PASSWORD=a1b2c3d4e5f6      ❌ Password terpotong
```

5. **Simpan file .env**

---

## 🔧 Langkah 5: Clear Config Cache

**PENTING**: Setelah update `.env`, selalu clear config cache:

```bash
php artisan config:clear
php artisan cache:clear
```

Ini memastikan Laravel membaca konfigurasi terbaru dari `.env`.

---

## 🗄️ Langkah 6: Jalankan Migration

Jalankan migration untuk menambahkan kolom `email_verified_at` dan `remember_token`:

```bash
php artisan migrate
```

Jika sudah pernah menjalankan migration sebelumnya, pastikan migration baru sudah dijalankan:
```bash
php artisan migrate:status
```

---

## ✅ Langkah 7: Test Email Verification

### Test Registrasi User Baru

1. Buka aplikasi di browser
2. Klik **"Masuk"** → **"Daftar di sini"**
3. Isi form registrasi dengan email yang valid (misalnya: `test@example.com`)
4. Submit form
5. Anda akan diarahkan ke halaman **"Verifikasi Email Anda"**

### Cek Email di Mailtrap

1. Buka dashboard Mailtrap di browser lain/tab baru: **https://mailtrap.io/**
2. Klik **"Email Testing"** → **"Inboxes"** → Klik inbox yang Anda gunakan
3. Anda akan melihat email verifikasi muncul di inbox
4. Klik email tersebut untuk melihat isinya
5. Di dalam email, ada **link verifikasi** (tombol atau link)
6. **Klik link verifikasi** tersebut

### Verifikasi Berhasil

Setelah klik link verifikasi:
- Browser akan redirect ke dashboard sesuai role user
- Muncul pesan sukses: "Email berhasil diverifikasi!"
- User sekarang bisa login dan menggunakan semua fitur

---

## 🐛 Troubleshooting: Error Authentication (535)

Jika muncul error seperti:
```
Failed to authenticate on SMTP server with username '...'
Error code: 535 5.7.0 Invalid...
```

### Solusi 1: Cek Username dan Password

1. **Login ke Mailtrap** → **Email Testing** → **Inboxes** → Pilih inbox → **SMTP Settings**
2. Klik **"Show Credentials"** atau lihat di bagian **"Integrations"** → **"Laravel"**
3. **Copy ulang** username dan password
4. Pastikan:
   - ✅ Tidak ada spasi di awal/akhir
   - ✅ Tidak ada tanda kutip
   - ✅ Copy lengkap semua karakter
   - ✅ Username dan password sesuai dengan yang di Mailtrap

### Solusi 2: Update .env dengan Benar

Pastikan di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=17e3f91d9a69d3
MAIL_PASSWORD=password_lengkap_dari_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@sikosma.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**JANGAN:**
- ❌ Pakai host salah: `MAIL_HOST=smtp.mailtrap.io` (harus `sandbox.smtp.mailtrap.io` untuk sandbox)
- ❌ Pakai tanda kutip: `MAIL_USERNAME="17e3f91d9a69d3"`
- ❌ Ada spasi: `MAIL_USERNAME= 17e3f91d9a69d3`
- ❌ Password tidak lengkap: `MAIL_PASSWORD=****0486` (harus copy lengkap dari Mailtrap)
- ❌ Pakai port lain: `MAIL_PORT=587` (untuk Mailtrap gunakan 2525)
- ❌ Pakai encryption lain: `MAIL_ENCRYPTION=ssl` (untuk Mailtrap gunakan tls)

### Solusi 3: Clear Config dan Test Lagi

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

Kemudian test registrasi lagi.

### Solusi 4: Cek Kredensial di Mailtrap

1. Login ke Mailtrap
2. Pastikan Anda menggunakan inbox yang benar
3. Setiap inbox punya username/password berbeda
4. Jika perlu, buat inbox baru dan gunakan kredensial yang baru

### Solusi 5: Test dengan Mailtrap API (Alternatif)

Jika masih error, coba gunakan Mailtrap API:

1. Di Mailtrap, klik **"Email Testing"** → **"Inboxes"** → Pilih inbox
2. Klik tab **"API"**
3. Copy **API Token**
4. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=api
MAIL_PASSWORD=your_api_token
MAIL_ENCRYPTION=tls
```

---

## 📝 Catatan Penting

### ✅ User dari Seeder
- **Sudah verified** - Semua user dari seeder sudah memiliki `email_verified_at = now()`
- **Bisa langsung login** - Tidak perlu verifikasi email
- **Aman** - Email dari seeder (seperti `akmal@email.com`) tidak akan benar-benar dikirim

### ⚠️ User dari Registrasi
- **Perlu verifikasi** - User yang register via form perlu verifikasi email
- **Email muncul di Mailtrap** - Email verifikasi akan muncul di Mailtrap inbox, bukan email asli
- **Link verifikasi** - User harus klik link di email Mailtrap untuk verifikasi

### 🔒 Keamanan
- **Mailtrap hanya untuk development** - Email tidak benar-benar dikirim ke alamat asli
- **Production** - Untuk production, gunakan SMTP server yang benar (Gmail, SendGrid, dll)
- **Seeder tetap aman** - Email dari seeder tidak akan dikirim karena Mailtrap hanya menangkap email yang dikirim aplikasi

---

## 🐛 Troubleshooting Lainnya

### Email tidak muncul di Mailtrap

1. **Cek konfigurasi .env**
   - Pastikan `MAIL_USERNAME` dan `MAIL_PASSWORD` sudah benar
   - Pastikan tidak ada spasi atau karakter tambahan
   - Pastikan menggunakan tanda kutip jika ada karakter khusus

2. **Cek kredensial Mailtrap**
   - Login ke Mailtrap dashboard
   - Pastikan username dan password yang digunakan sesuai dengan yang di .env
   - Coba generate ulang password di Mailtrap jika perlu

3. **Cek log Laravel**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   Cari error terkait email atau SMTP

4. **Clear config cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

5. **Cek folder Spam**
   - Di Mailtrap inbox, cek juga folder "Spam" atau "Junk"

### Link verifikasi tidak bekerja

1. **Pastikan APP_URL benar di .env**
   ```env
   APP_URL=http://localhost:8000
   ```
   Atau sesuai dengan URL aplikasi Anda

2. **Cek signed URL**
   - Link verifikasi menggunakan signed URL
   - Pastikan `APP_KEY` di .env sudah di-generate:
   ```bash
   php artisan key:generate
   ```

3. **Cek middleware signed**
   - Pastikan route menggunakan middleware `signed`
   - Sudah ada di `routes/web.php`

### User dari seeder tidak bisa login

1. **Pastikan migration sudah dijalankan**
   ```bash
   php artisan migrate
   ```

2. **Pastikan seeder sudah dijalankan ulang**
   ```bash
   php artisan db:seed --class=UserSeeder
   ```
   Atau:
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Cek database**
   - Pastikan kolom `email_verified_at` sudah ada di tabel `users`
   - Pastikan user dari seeder memiliki nilai `email_verified_at` yang tidak null

---

## ❓ FAQ: Apakah Perlu Membuat Mailable?

**TIDAK PERLU!** Untuk email verification, Laravel sudah punya built-in notification.

### Perbedaan:

**❌ Dokumentasi Umum (WelcomeEmail):**
- Membuat Mailable: `php artisan make:mail WelcomeEmail`
- Untuk email custom seperti welcome email, invoice, dll
- Contoh: https://techsolutionstuff.com/post/how-to-send-email-using-mailtrap-in-laravel-10

**✅ Email Verification (Yang Kita Gunakan):**
- **TIDAK perlu** membuat Mailable
- Laravel sudah punya `sendEmailVerificationNotification()`
- Sudah otomatis terintegrasi dengan `MustVerifyEmail` interface
- Template email sudah ada di Laravel (bisa di-custom jika perlu)

### Kode yang Sudah Benar:

```php
// Di AuthController.php - SUDAH BENAR ✅
$user->sendEmailVerificationNotification();
```

Tidak perlu membuat `WelcomeEmail` atau Mailable lain untuk verification!

---

## 📚 Referensi

- **Dokumentasi Mailtrap**: https://mailtrap.io/docs/
- **Dokumentasi Laravel Email Verification**: https://laravel.com/docs/verification
- **Mailtrap Dashboard**: https://mailtrap.io/

---

## 🎯 Quick Start Checklist

- [ ] Daftar/Login ke Mailtrap
- [ ] Pilih atau buat inbox
- [ ] Copy username dan password dari Mailtrap (lengkap, tanpa spasi)
- [ ] Update file `.env` dengan kredensial Mailtrap (tanpa tanda kutip)
- [ ] Jalankan `php artisan config:clear`
- [ ] Jalankan `php artisan migrate`
- [ ] Test registrasi user baru
- [ ] Cek email di Mailtrap inbox
- [ ] Klik link verifikasi di email Mailtrap
- [ ] Verifikasi berhasil dan user bisa login

---

**Selamat! Email verification dengan Mailtrap sudah siap digunakan! 🎉**
