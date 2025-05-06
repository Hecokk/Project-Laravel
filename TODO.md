# Rencana Pengembangan Backend Laravel BookTrackr

Ini adalah daftar tugas untuk membangun backend Laravel untuk proyek BookTrackr.

## Tahap 1: Struktur Dasar & Autentikasi

-   [✓] Inisialisasi Proyek Laravel baru.
-   [✓] Siapkan Database.
-   [✓] **Model:**
    -   [✓] Buat Model `Book`.
    -   [✓] Buat Model `Author`.
    -   [✓] Buat Model `Review`.
    -   [✓] (Opsional) Buat Model `Genre`.
-   [✓] **Migration:**
    -   [✓] Buat Migration untuk tabel `authors` (`name`, `bio?`).
    -   [✓] Buat Migration untuk tabel `genres` (`name`, `slug`). (Jika digunakan)
    -   [✓] Buat Migration untuk tabel `books` (`title`, `author_id`, `genre_id?`, `description`, `cover_image_path?`, `publication_year?`, dll.). Tambahkan foreign key constraints.
    -   [✓] Buat Migration untuk tabel `reviews` (`user_id`, `book_id`, `rating`, `content` (TEXT/LONGTEXT), timestamps). Tambahkan foreign key constraints.
    -   [✓] Jalankan `php artisan migrate`.
-   [✓] **Relationship:**
    -   [✓] Definisikan relasi `hasMany`/`belongsTo` antara `User`, `Book`, `Author`, `Review`, `Genre` di dalam Model.
-   [✓] **Authentication:**
    -   [✓] Install & setup Laravel Breeze atau Jetstream.
    -   [✓] Sesuaikan view autentikasi (`login.blade.php`, `register.blade.php`) agar cocok dengan desain HTML statis.
    -   [✓] Atur lupa password, verifikasi email
-   [✓] **Controller Awal:**
    -   [✓] Buat `BookController`.
    -   [✓] Buat `ReviewController`.
    -   [✓] Buat `AuthorController` (jika perlu halaman khusus penulis).
    -   [✓] Buat `HomeController`.

## Tahap 2: Fitur Inti

-   [✓] **Routing:**
    -   [✓] Definisikan route di `web.php` untuk halaman utama, detail buku, daftar ulasan, proses login/register/logout, dan proses tambah ulasan.
-   [✓] **Integrasi Frontend Awal:**
    -   [✓] Pindahkan file HTML statis ke `resources/views/` dan ubah nama menjadi `.blade.php`.
    -   [✓] Buat layout Blade (`layouts/app.blade.php`) untuk header (navbar) dan footer.
    -   [✓] Gunakan `@extends` dan `@section` di view lain.
    -   [✓] Pindahkan `style.css` ke `public/css/` atau kelola via Vite/Mix (disarankan).
    -   [✓] Perbarui referensi CSS di layout Blade.
-   [✓] **Halaman Utama (`HomeController@index`):**
    -   [✓] Ambil data buku (misal: terbaru) di Controller.
    -   [✓] Kirim data ke view `index.blade.php`.
    -   [✓] Tampilkan buku menggunakan `@foreach`.
-   [✓] **Halaman Detail Buku (`BookController@show`):**
    -   [✓] Gunakan Route Model Binding untuk mengambil data buku.
    -   [✓] Ambil ulasan terkait buku (dengan data user).
    -   [✓] Kirim data buku dan ulasan ke view `books/show.blade.php` (atau nama serupa).
    -   [✓] Tampilkan detail buku.
    -   [✓] Tampilkan daftar ulasan (gunakan `{!! $review->content !!}` untuk konten Trix - **perlu sanitasi!**).
-   [✓] **Halaman Ulasan (`ReviewController@index`):**
    -   [✓] Ambil daftar ulasan (misal: terbaru dengan pagination).
    -   [✓] Kirim data ke view `reviews/index.blade.php`.
    -   [✓] Tampilkan ulasan.
-   [✓] **Tambah Ulasan (`ReviewController@store`):**
    -   [✓] Buat form di `books/show.blade.php` (atau view terpisah).
    -   [✓] Integrasikan Trix Editor untuk input `content`.
    -   [✓] Tambahkan input untuk `rating`.
    -   [✓] Implementasikan logika validasi dan penyimpanan di `store` method.
    -   [✓] Pastikan hanya user yang login bisa menambah ulasan (`middleware('auth')`).
    -   [✓] **Implementasikan Sanitasi HTML** untuk konten Trix sebelum disimpan atau saat ditampilkan.

## Tahap 3: Fitur Tambahan (Opsional/Masa Depan)

-   [ ] Implementasi Pelacakan Bacaan (Reading Status: reading, read, want_to_read).
-   [ ] Implementasi Fitur Daftar Buku Kustom (Reading Lists).
-   [✓] Implementasi Fitur Pencarian Buku.
-   [✓] Buat Halaman Profil Pengguna.
-   [ ] Buat Panel Admin (jika diperlukan).

## Tahap 4: API Google Books

1. Persiapan API dan Konfigurasi
   [✓] Membuat akun Google Cloud Platform jika belum punya
   [✓] Mengaktifkan Google Books API di Google Cloud Console
   [✓] Mendapatkan API Key dari Google Cloud Console
   [✓] Menambahkan config untuk Google Books di config/services.php

2. Membuat Service Class untuk Google Books API
   [✓] Membuat app/Services/GoogleBooksService.php untuk menangani request ke API
   [✓] Mengimplementasikan fungsi searchBooks($query, $options = [])
   [✓] Mengimplementasikan fungsi getBookDetails($id)
   [✓] Menambahkan fungsi untuk konversi data API ke format yang dibutuhkan aplikasi

3. Mengupdate Controller dan Routes
   [✓] Membuat method searchExternal di BookController
   [✓] Menambahkan route untuk pencarian API di routes/web.php
   [✓] Membuat method importFromExternal untuk menyimpan buku dari API ke database lokal
   [✓] Menambahkan route untuk import buku ke database

4. Membuat View untuk Hasil Pencarian API
   [✓] Membuat file view resources/views/books/external-search.blade.php
   [✓] Mendesain tampilan hasil pencarian API
   [✓] Menambahkan form filter dan opsi lanjutan untuk pencarian

5. Mengintegrasikan dengan Fitur Pencarian yang Ada
   [✓] Mengupdate form pencarian di navbar untuk opsi "Pencarian API"
   [✓] Menambahkan tab di halaman pencarian untuk hasil lokal vs. hasil API
   [✓] Menambahkan tombol "Import ke Database" untuk admin

6. Implementasi Caching dan Optimasi
   [✓] Menambahkan caching untuk hasil pencarian API
   [✓] Membuat job untuk mengunduh gambar sampul buku saat import
   [✓] Mengimplementasikan rate limiting untuk API requests

7. Pengujian dan Debugging
   [✓] Membuat test untuk GoogleBooksService
   [✓] Menguji pencarian dengan berbagai parameter
   [✓] Menguji proses import buku dari API

8. Fitur Tambahan (Opsional)
   [ ] Menambahkan fitur pencarian otomatis saat mengetik (autocomplete)
   [✓] Mengimplementasikan lazy loading untuk hasil pencarian (via Pagination)
   [ ] Menambahkan fitur ekspor data buku ke CSV/PDF

9. Dokumentasi
   [✓] Mendokumentasikan cara penggunaan Google Books API (di `docs/google-books-api.md`)
   [✓] Membuat panduan untuk admin tentang cara import buku (di `docs/google-books-api.md`)
   [✓] Menambahkan komentar kode yang lengkap untuk fungsi API

10. Deployment dan Monitoring
    [✓] Memastikan API key diamankan di production (via `.env`)
    [✓] Menyiapkan monitoring penggunaan API (jumlah request) (via Google Cloud Console)
    [✓] Mengimplementasikan error handling untuk kegagalan API (di Service & Controller)

## Tahap 5: Penyempurnaan & Lain-lain (Contoh)

-   [ ] Implementasi Pelacakan Bacaan (Reading Status: reading, read, want_to_read).
-   [ ] Implementasi Fitur Daftar Buku Kustom (Reading Lists).
-   [ ] Selesaikan TODOs di kode (misal: implementasi import, rating detail, dll.)
-   [ ] **Perbarui Counter Halaman Utama:**
    -   [ ] Ganti angka hardcoded (12.5M, 1.2M, dll.) di `welcome.blade.php`.
    -   [ ] Ambil data agregat dari database (misal: `Book::count()`, `Review::count()`).
    -   [ ] Implementasikan caching untuk data counter agar tidak query database setiap request.
    -   [ ] (Tergantung Fitur Status Bacaan) Implementasi counter "Books Read".
-   [ ] Buat Panel Admin (jika diperlukan).
-   [ ] Testing lebih lanjut (Unit, Feature, Browser).
-   [ ] Perbaikan UI/UX
