# Dokumentasi Google Books API & Fitur Import

Dokumen ini menjelaskan cara kerja integrasi Google Books API di aplikasi BookTrackr dan panduan untuk mengimpor buku.

## 1. Konfigurasi Google Books API

Integrasi ini memerlukan API Key dari Google Cloud Platform.

-   **Mendapatkan API Key:**
    1.  Buka [Google Cloud Console](https://console.cloud.google.com/).
    2.  Buat proyek baru atau gunakan proyek yang sudah ada.
    3.  Aktifkan "Google Books API" di bagian "APIs & Services" > "Library".
    4.  Buat kredensial baru bertipe "API Key". Batasi key ini agar hanya bisa digunakan dari domain/IP server aplikasi Anda untuk keamanan.
-   **Konfigurasi di Laravel:**
    1.  Buka file `.env`.
    2.  Tambahkan baris berikut dan ganti `YOUR_API_KEY` dengan kunci yang Anda dapatkan:
        ```
        GOOGLE_BOOKS_API_KEY=YOUR_API_KEY
        ```
    3.  Pastikan file `config/services.php` memiliki entri untuk Google Books:
        ```php
        'google_books' => [
            'key' => env('GOOGLE_BOOKS_API_KEY'),
        ],
        ```
    4.  Jalankan `php artisan config:clear` untuk memastikan konfigurasi baru terbaca.

## 2. Penggunaan API

Interaksi dengan Google Books API ditangani oleh `App\Services\GoogleBooksApiService`.

-   **Pencarian Buku (`searchBooks`):**
    -   Method ini dipanggil oleh `BookController@search` ketika opsi pencarian Google Books diaktifkan.
    -   Mengambil query pencarian dan parameter tambahan (seperti `maxResults`).
    -   Melakukan request ke endpoint `/volumes` Google Books API.
    -   Hasil pencarian di-cache selama 24 jam untuk mengurangi panggilan API.
-   **Detail Buku (`getBookDetails`):**
    -   Method ini dipanggil saat pengguna melihat detail buku yang berasal dari Google Books (misalnya dari hasil pencarian atau rekomendasi).
    -   Mengambil Google Books Volume ID.
    -   Melakukan request ke endpoint `/volumes/{id}`.
    -   Hasil detail di-cache selama 7 hari.
-   **Buku Acak/Rekomendasi (`getRandomBooks`):**
    -   Digunakan di halaman pencarian jika tidak ada query dan hasil lokal kosong.
    -   Memilih query acak dari daftar predefined (misal: 'subject:fiction bestseller').
    -   Memanggil `searchBooks` untuk mendapatkan hasil.
    -   Hasil di-cache selama 12 jam.

## 3. Panduan Import Buku (Untuk Admin)

Fitur import memungkinkan Admin (atau pengguna dengan izin) untuk menambahkan buku dari hasil pencarian Google Books ke database lokal aplikasi.

-   **Cara Import:**
    1.  Lakukan pencarian buku di halaman pencarian (`/search`). Pastikan opsi untuk menyertakan hasil dari Google Books aktif.
    2.  Pada hasil pencarian yang berasal dari Google Books, akan muncul tombol "Impor".
    3.  Klik tombol "Impor" pada buku yang diinginkan.
-   \*\*Proses di Balik Layar (`importBook` di Service & Controller):
    1.  `BookController@importGoogleBook` menerima request POST dengan Google Books Volume ID.
    2.  Controller memanggil `GoogleBooksApiService@importBook` dengan ID tersebut.
    3.  _(TODO: Implementasi di `GoogleBooksApiService@importBook`)_
        -   Service akan memanggil `getBookDetails` untuk mendapatkan data lengkap buku dari API.
        -   Service akan mencari atau membuat data `Author` di database lokal berdasarkan nama penulis dari API.
        -   Service akan mencari atau membuat data `Genre` (jika ada kategori di API dan pemetaan memungkinkan).
        -   Service akan membuat record baru di tabel `books` dengan data dari API (judul, deskripsi, tahun terbit, ISBN, dll.).
        -   Service akan mencoba mengunduh gambar sampul dari URL API dan menyimpannya secara lokal (misalnya di `storage/app/public/covers`). Path gambar lokal akan disimpan di `cover_image_path` pada record buku.
        -   Pengecekan duplikat berdasarkan ISBN atau Google Books ID perlu dilakukan sebelum menyimpan.
    4.  Jika import berhasil, Service mengembalikan model `Book` yang baru dibuat.
    5.  Controller mengarahkan pengguna ke halaman detail buku yang baru diimpor (`books.show`).
    6.  Jika gagal (misal: buku sudah ada, error API), pengguna diarahkan kembali dengan pesan error.
-   **Penting:**
    -   Saat ini, logika import di `GoogleBooksApiService@importBook` masih berupa _placeholder_ dan perlu diimplementasikan sepenuhnya.
    -   Otorisasi (memastikan hanya admin yang bisa import) juga perlu ditambahkan (saat ini hanya memeriksa user login).

## 4. Error Handling

Service API sudah dilengkapi dengan pencatatan error dasar menggunakan `Log::error()` jika terjadi kegagalan saat berkomunikasi dengan Google Books API. Detail error bisa dilihat di file log Laravel (`storage/logs/laravel.log`). Controller juga menangani beberapa exception dasar dan mengarahkan pengguna dengan pesan error.
