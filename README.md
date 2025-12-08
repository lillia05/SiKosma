# SiKosma (Sistem Informasi Kos Mahasiswa Unila)

**SiKosma** adalah platform berbasis web yang dirancang untuk mempermudah mahasiswa Universitas Lampung (Unila) dalam mencari tempat tinggal (kos) di sekitar kampus, khususnya wilayah Kampung Baru dan Gedong Meneng. Sistem ini juga membantu pemilik kos untuk mempromosikan properti mereka secara efektif dan terpusat[cite: 685, 686, 690].

Proyek ini dikembangkan sebagai **Project Akhir Mata Kuliah Pemrograman Web Lanjut** di Jurusan Ilmu Komputer, FMIPA, Universitas Lampung.

🔗 **Link Repository:** [https://github.com/lillia05/SiKosma.git](https://github.com/lillia05/SiKosma.git) 

-----

## Latar Belakang

Mahasiswa sering mengalami kesulitan mencari informasi kos yang terpusat dan valid, sementara pemilik kos kesulitan menjangkau pasar mahasiswa baru. **SiKosma** hadir sebagai solusi untuk:

  * Menyediakan informasi kos secara *real-time* dan terpusat.
  * Menjamin keamanan transaksi dengan verifikasi sistem.
  * Mempermudah manajemen properti bagi pemilik kos.

-----

## Fitur Utama

.Sistem ini membagi hak akses menjadi tiga peran utama.

### 1\. Pengguna (Pencari Kos)

  * **Pencarian & Filter:** Cari kos berdasarkan lokasi (Kampung Baru/Gedong Meneng), tipe (Putra/Putri/Campur), dan harga.
  * **Detail Kos:** Melihat foto, fasilitas, harga, dan ketersediaan kamar.
  * **Pemesanan (Booking):** Melakukan sewa kos secara langsung melalui aplikasi.
  * **Pembayaran:** Upload bukti transfer/e-wallet untuk diverifikasi admin/pemilik.
  * **Riwayat:** Memantau status pemesanan dan riwayat transaksi.

### 2\. Pemilik Kos

  * **Manajemen Properti:** Tambah, edit, dan hapus data kos serta kamar.
  * **Manajemen Booking:** Menerima dan melihat daftar penyewa yang masuk.
  * **Laporan Keuangan:** Melihat ringkasan pendapatan bulanan dan statistik okupansi.
  * **Dashboard Statistik:** Memantau jumlah kamar terisi dan total pendapatan.

### 3\. Admin

  * **Verifikasi Kos:** Memvalidasi data kos baru sebelum ditampilkan ke publik (Disetujui/Ditolak).
  * **Verifikasi Pembayaran:** Memverifikasi bukti pembayaran dari penyewa.
  * **Manajemen Pengguna:** Mengelola akun pengguna dan pemilik kos.
  * **Monitoring Sistem:** Memantau seluruh aktivitas transaksi dalam platform.

-----

## Arsitektur & Database

SiKosma menggunakan struktur database relasional yang mencakup tabel-tabel utama berikut:

  * `Pengguna` (Menyimpan data Admin, Pemilik, dan Pencari Kos).
  * `Kos` (Data properti kos).
  * `Kamar` (Detail kamar, harga, dan fasilitas).
  * `Gambar_Kos` (Galeri foto properti).
  * `Pemesanan` (Mencatat transaksi sewa).
  * `Pembayaran` (Mencatat bukti dan status pembayaran).

-----
## Implementasi Teknis & Keamanan
Bagian ini menjelaskan detail teknis implementasi di back-end sistem:

### 1\. Autentikasi & Verifikasi

  * **Proses Login:** Sistem mengamankan password pengguna menggunakan algoritma hashing Bcrypt (standar Laravel). Password tidak disimpan sebagai plain text di database.
  * **Verifikasi Email:** Verifikasi email diterapkan saat registrasi. Akun baru ditandai dengan kolom email_verified_at di tabel Pengguna, memastikan validitas pengguna.

### 2\. Validasi

  * **Validasi Sisi View (Frontend):** Dilakukan untuk memberikan umpan balik instan kepada pengguna (misalnya, input wajib diisi) sebelum data dikirim ke server.
  * **Validasi Sisi Controller (Server-side):** Merupakan garis pertahanan utama. Validasi ketat diterapkan pada setiap input form (misalnya: format file gambar, batasan panjang teks, input harga harus numerik) dan proses status transaksi (contoh: status transaksi diubah dari Menunggu menjadi Terverifikasi hanya oleh Admin).

### 3\. Session & Otorisasi

  * **Pengelolaan Session:** Sesi dikelola secara otomatis oleh framework Laravel untuk melacak status login pengguna setelah autentikasi berhasil.
  * **Otorisasi:** Otorisasi akses menggunakan sistem Middleware dan pengecekan role (peran). Setiap route (jalur akses) yang bersifat sensitif hanya dapat diakses jika peran pengguna (Admin, Pemilik Kos, atau Penyewa) sesuai.

### 4\. Data Master
Data master adalah data referensi statis yang digunakan sistem untuk menjaga konsistensi:
  * **Jenis Pengguna:** Admin, Pemilik Kos, Pencari Kos (disimpan di kolom role tabel Pengguna).
  * **Status Verifikasi/Transaksi:** Menunggu Verifikasi, Diverifikasi, Ditolak (untuk kos), Pending, Confirmed, Active, Cancelled (untuk pemesanan).
  * **Kategori Lokasi:** Kampung Baru, Gedong Meneng.

----

## Tim Pengembang (Kelompok 4)

Proyek ini dikembangkan oleh tim mahasiswa Ilmu Komputer Unila angkatan 2023:

| NPM | Nama |
| :--- | :--- |
| **2357051018** | **Muhammad Akmal Fadhurohman** |
| **2317051040** | **Muhammad Alvin** |
| **2317051097** | **Lekok Indah Lia** |
| **2317051022** | **Lifia Anasywa** |

-----

## Instalasi & Menjalankan Project

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di mesin lokal Anda:

1.  **Clone Repository**

    ```bash
    git clone https://github.com/lillia05/SiKosma.git
    cd SiKosma
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**

      * Duplikasi file `.env.example` menjadi `.env`.
      * Sesuaikan konfigurasi database (DB\_DATABASE, DB\_USERNAME, dll).

4.  **Generate Key & Migrate Database**

    ```bash
    php artisan key:generate
    php artisan migrate:fresh --seed
    ```

    **Catatan:** 
    - Seeder akan otomatis membuat folder `storage/app/public/kos-images/` jika belum ada
    - Seeder akan otomatis copy logo dari `public/images/` ke `storage/app/public/logos/` jika ada
    - Setelah seeder selesai, data kos sudah ada di database, namun **gambar kos perlu diminta ke developer lain** (lihat langkah 6b)

5.  **Setup Storage Link (PENTING untuk Logo & Upload Gambar)**

    Untuk membuat logo dan upload gambar profile bisa langsung berfungsi, jalankan perintah berikut:

    ```bash
    php artisan storage:link
    ```

    Perintah ini akan membuat symbolic link dari `public/storage` ke `storage/app/public`, sehingga:
    - Logo aplikasi bisa langsung terlihat
    - Upload gambar profile user bisa langsung berfungsi
    - Upload bukti pembayaran bisa langsung berfungsi

    **Catatan:** Jika logo masih tidak muncul setelah menjalankan `storage:link`, pastikan file logo sudah ada di folder `public/images/` atau `resources/images/`. Lihat dokumentasi di `resources/images/README.md` untuk detail lebih lanjut.

6.  **Setup File Tambahan (Minta ke Developer Lain)**

    Agar aplikasi bisa berjalan dengan lengkap, Anda perlu meminta beberapa file berikut ke developer lain:

    ### a. File Konfigurasi Environment (`.env`)
    
    Minta file `.env` yang sudah dikonfigurasi dari developer lain, atau salin dari `.env.example` dan sesuaikan konfigurasi berikut:
    
    - Konfigurasi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
    - Konfigurasi email untuk verifikasi (MAIL_MAILER, MAIL_HOST, dll)
    - Konfigurasi Google OAuth jika menggunakan login dengan Google (GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET)
    - APP_URL sesuai dengan URL aplikasi Anda
    
    **⚠️ PENTING:** Jangan commit file `.env` ke repository karena berisi informasi sensitif.

    ### b. File Gambar Kos untuk Seeder
    
    Setelah menjalankan seeder, data kos akan terbuat di database, namun **gambar kos belum tersedia**. Untuk menampilkan gambar kos di aplikasi, Anda perlu:
    
    1. **Minta folder gambar kos** dari developer lain yang berisi:
       - Gambar umum kos (format: `kos-{nama-kos}-{nomor}.png`)
       - Gambar kamar (format: `kamar-{nomor_kamar}.png`)
    
    2. **Letakkan semua file gambar** ke folder:
       ```
       storage/app/public/kos-images/
       ```
    
       **Catatan:** Folder `kos-images` akan otomatis dibuat saat menjalankan seeder (`php artisan migrate:fresh --seed`), jadi Anda tidak perlu membuat folder secara manual.
    
    3. **Pastikan storage link sudah dibuat** (langkah 5 di atas)
    
    **Daftar file gambar yang diperlukan:**
    - `kos-putri-sahara-1.png` sampai `kos-putri-sahara-4.png` (gambar umum)
    - `kos-putra-kampung-baru-1.png` sampai `kos-putra-kampung-baru-4.png` (gambar umum)
    - `kos-putri-melati-1.png` sampai `kos-putri-melati-4.png` (gambar umum)
    - `kos-putra-ali-1.png` sampai `kos-putra-ali-4.png` (gambar umum)
    - `kos-campur-adam-1.png` sampai `kos-campur-adam-4.png` (gambar umum)
    - `kos-putri-bunga` menggunakan gambar yang sama dengan `kos-putri-sahara` (gambar umum)
    - `kamar-1.png`, `kamar-2.png`, `kamar-3.png`, dst (gambar kamar)
    
    **Tips:** Setelah meletakkan file gambar, refresh halaman detail kos untuk melihat gambar yang sudah di-upload.

7.  **Jalankan Server**

    ```bash
    php artisan serve
    ```

    Buka `http://127.0.0.1:8000` di browser Anda.

-----

## Checklist Setup Awal

Setelah mengikuti langkah instalasi di atas, pastikan checklist berikut sudah selesai:

- [ ] Repository sudah di-clone
- [ ] Dependencies sudah di-install (`composer install` dan `npm install`)
- [ ] File `.env` sudah dikonfigurasi (minta ke developer lain jika perlu)
- [ ] `php artisan key:generate` sudah dijalankan
- [ ] `php artisan migrate:fresh --seed` sudah dijalankan (folder kos-images otomatis dibuat)
- [ ] `php artisan storage:link` sudah dijalankan (untuk logo dan upload gambar)
- [ ] File gambar kos sudah diletakkan di `storage/app/public/kos-images/` (minta ke developer lain)
- [ ] File `.env` sudah dikonfigurasi dengan benar (minta ke developer lain jika perlu)
- [ ] Server sudah berjalan (`php artisan serve`)
- [ ] Logo aplikasi sudah terlihat di halaman
- [ ] Upload gambar profile sudah bisa berfungsi
- [ ] Gambar kos sudah terlihat di halaman detail kos
