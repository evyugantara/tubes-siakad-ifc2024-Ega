# Sistem Informasi Akademik Sederhana (SIAKAD)
### Tugas Besar Mata Kuliah Web II IF53413

Aplikasi ini adalah Sistem Informasi Akademik (SIAKAD) sederhana berbasis framework **Laravel 12** dan database **MySQL**. Aplikasi ini dirancang untuk mensimulasikan pengelolaan data akademik di lingkungan perguruan tinggi (Universitas Suryakencana) sesuai dengan relasi ERD yang ditentukan.

---

## 1. Deskripsi Singkat Aplikasi

Aplikasi SIAKAD ini memungkinkan pengelolaan terpadu untuk data Dosen, Mahasiswa, Mata Kuliah, Jadwal Perkuliahan, dan Kartu Rencana Studi (KRS). Aplikasi ini dilengkapi dengan **Authentication & Authorization** (sistem login/logout) yang membagi hak akses pengguna menjadi dua peran utama:
1. **Admin**: Memiliki kontrol penuh (CRUD) terhadap semua data di sistem.
2. **Mahasiswa**: Dapat melihat jadwal perkuliahan, mengisi Kartu Rencana Studi (KRS), menghapus mata kuliah pilihan (drop), mencetak KRS dalam format PDF resmi, dan mengekspor KRS ke Excel (CSV).

Aplikasi ini didesain menggunakan **Vanilla CSS** modern bertema *Navy Blue & White* premium, lengkap dengan efek bersih, mikro-animasi pada hover, dan layout responsif, serta optimasi stylesheet cetak (`@media print`) khusus untuk halaman cetak KRS resmi.

---

## 2. Penjelasan Fungsi Masing-Masing Halaman

1. **Halaman Login (`/login`)**
   - Form masuk sistem menggunakan kombinasi **Username/NPM** dan **Kata Sandi**.
   - Dilengkapi validasi form lengkap (error ditampilkan langsung secara inline di bawah input).
   - Menyediakan opsi "Ingat saya" (remember token) untuk menjaga sesi login.

2. **Halaman Dashboard (`/dashboard`)**
   - **Tampilan Admin**: Menampilkan statistik ringkas (total dosen, mahasiswa, mata kuliah, dan jadwal kuliah) untuk kemudahan monitoring data.
   - **Tampilan Mahasiswa**: Menampilkan informasi Dosen Wali Akademik (Nama & NIDN), ringkasan SKS yang telah diambil, dan **Jadwal Kuliah Pribadi** (hanya menampilkan jadwal perkuliahan dari mata kuliah yang didaftarkan di KRS mahasiswa tersebut secara terurut hari dan jamnya).

3. **Halaman Data Dosen (`/dosen`)** *(Hanya Admin)*
   - Menampilkan daftar dosen pengajar yang dilengkapi kolom pencarian (search NIDN & Nama) serta pagination.
   - Menyediakan form inline dinamis di bagian atas untuk menambahkan dosen baru atau mengubah data dosen (ketika tombol "Ubah" diklik).
   - Menghapus dosen akan menghapus jadwal terkait secara otomatis (cascade).

4. **Halaman Data Mahasiswa (`/mahasiswa`)** *(Hanya Admin)*
   - Menampilkan daftar mahasiswa lengkap dengan Dosen Wali-nya masing-masing.
   - Menyediakan filter berdasarkan Dosen Wali dan pencarian teks berdasarkan NPM/Nama.
   - Form penambahan mahasiswa secara otomatis akan membuat **akun login mahasiswa** di tabel `users` dengan NPM sebagai *username* sekaligus *password* default.
   - Menghapus mahasiswa otomatis menghapus akun login dan KRS miliknya (cascade).

5. **Halaman Data Mata Kuliah (`/matakuliah`)** *(Hanya Admin)*
   - Mengelola data mata kuliah beserta bobot SKS-nya (1 - 6 SKS).
   - Mendukung pencarian kode atau nama mata kuliah.

6. **Halaman Jadwal Kuliah (`/jadwal`)**
   - **Tampilan Admin**: Menampilkan daftar seluruh jadwal kuliah di kampus dan menyediakan form CRUD lengkap untuk menentukan mata kuliah, dosen pengajar, hari, jam perkuliahan, dan ruang kelas.
   - **Tampilan Mahasiswa**: Menampilkan seluruh jadwal perkuliahan di program studi secara *read-only* (hanya baca) dengan fitur pencarian dan filter hari.

7. **Halaman Kartu Rencana Studi (`/krs`)**
   - **Tampilan Admin**: Memonitoring seluruh data KRS yang diambil mahasiswa. Admin dapat memfilter berdasarkan mahasiswa tertentu untuk melihat mata kuliah apa saja yang dipilih mereka dan membatalkan (drop) mata kuliah yang diambil mahasiswa tersebut.
   - **Tampilan Mahasiswa**: Digunakan untuk mengambil mata kuliah baru dari daftar penawaran (form pengisian KRS).
     - Sistem dilengkapi validasi **Batas SKS Maksimal (24 SKS)**. Mahasiswa tidak bisa mengambil lebih dari 24 SKS.
     - Sistem mencegah duplikasi pengambilan mata kuliah yang sama.
     - Mahasiswa dapat membatalkan (drop) mata kuliah.
     - Menyediakan tombol cepat untuk **Ekspor ke Excel (CSV)** dan **Cetak KRS (PDF)**.

8. **Halaman Cetak KRS (`/krs/cetak`)** *(Hanya Mahasiswa)*
   - Halaman cetak resmi dengan desain hitam-putih standar akademik Universitas Suryakencana (Times New Roman).
   - Menampilkan data diri mahasiswa, dosen wali, dan tabel mata kuliah yang diambil beserta kolom tanda tangan pengesahan dosen wali dan mahasiswa.
   - Memiliki script *auto-print* yang langsung memicu jendela cetak browser ketika halaman dimuat.

---

## 3. Konfigurasi Teknis dan Database

### Prasyarat:
- PHP >= 8.2
- MySQL (XAMPP / Laragon)
- Composer

### Langkah Instalasi:
1. Pastikan Apache dan MySQL di XAMPP Anda aktif.
2. Buat database baru bernama `tubes_siakad` via phpMyAdmin atau konsol MySQL:
   ```sql
   CREATE DATABASE tubes_siakad;
   ```
3. Konfigurasi file `.env` telah disesuaikan dengan koneksi database MySQL lokal:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tubes_siakad
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Jalankan migrasi dan database seeder untuk membuat tabel dan mengisi data awal:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Jalankan server lokal Laravel:
   ```bash
   php artisan serve
   ```
6. Buka browser dan akses aplikasi melalui: `http://127.0.0.1:8000`

---

## 4. Akun Pengguna untuk Pengujian (Seeded Users)

Gunakan akun berikut untuk menguji fitur aplikasi:

### A. Akun Administrator (Akses Penuh)
- **Username / Login**: `admin`
- **Password**: `admin123`

### B. Akun Mahasiswa (Akses Terbatas: KRS & Jadwal)
1. **Akun Mahasiswa 1**
   - **Username / NPM**: `223040001`
   - **Password**: `223040001`
   - *Detail*: Atas nama **Ahmad Fauzi**, Dosen Wali: *Sandhika Galih, S.T., M.T.* (Sudah memiliki KRS awal: Pemrograman Web II & Pemrograman Mobile).
2. **Akun Mahasiswa 2**
   - **Username / NPM**: `223040002`
   - **Password**: `223040002`
   - *Detail*: Atas nama **Siti Aminah**, Dosen Wali: *Priyatna, S.T., M.T.* (Sudah memiliki KRS awal: Rekayasa Perangkat Lunak & Kecerdasan Buatan).
3. **Akun Mahasiswa 3**
   - **Username / NPM**: `223040003`
   - **Password**: `223040003`
   - *Detail*: Atas nama **Rizky Pratama**, Dosen Wali: *Rani Susanto, S.Kom., M.Kom.*

---

## 5. Direktori Screenshots Aplikasi

Sesuai ketentuan tugas, buatlah folder bernama `screenshots` di direktori root project ini dan simpan tangkapan layar (screenshots) dari halaman-halaman berikut:
```text
tubes_pwl/
│
├── screenshots/
│   ├── login.png        <- Tangkapan layar Halaman Login
│   ├── dashboard.png    <- Tangkapan layar Halaman Dashboard (Admin / Mahasiswa)
│   ├── dosen.png        <- Tangkapan layar CRUD Data Dosen
│   ├── mahasiswa.png    <- Tangkapan layar CRUD Data Mahasiswa
│   ├── matakuliah.png   <- Tangkapan layar CRUD Data Mata Kuliah
│   ├── jadwal.png       <- Tangkapan layar Jadwal Kuliah
│   ├── krs.png          <- Tangkapan layar Kelola KRS (Mahasiswa)
│   └── krs-cetak.png    <- Tangkapan layar Halaman Cetak KRS (Tampilan Cetak PDF)
```
*(Catatan: Silakan lakukan tangkapan layar sendiri setelah aplikasi dideploy/dijalankan secara lokal).*
