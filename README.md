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

5.  **Jalankan Server**

    ```bash
    php artisan serve
    ```

    Buka `http://127.0.0.1:8000` di browser Anda.
