# BookTrackr

BookTrackr adalah aplikasi web yang dibangun dengan Laravel, dirancang untuk membantu pengguna mengatalogkan riwayat bacaan mereka, menulis ulasan, dan menemukan buku baru. Aplikasi ini bertujuan menyediakan platform terpusat untuk mengelola dan berbagi pengalaman membaca.

## Tujuan Proyek

-   Memungkinkan pengguna untuk melacak buku yang telah mereka baca, sedang dibaca, atau ingin dibaca.
-   Menyediakan platform untuk menulis dan berbagi ulasan serta peringkat buku.
-   Membantu pengguna menemukan buku baru melalui pencarian dan rekomendasi (termasuk integrasi dengan Google Books API).
-   Menawarkan antarmuka yang bersih dan mudah digunakan untuk mengelola koleksi buku pribadi.

## Fitur Utama (Saat Ini)

-   **Autentikasi Pengguna:**
    -   Registrasi dan Login Pengguna.
    -   Manajemen Profil Pengguna dasar.
    -   Proses Logout.
-   **Manajemen Buku (Lokal):**
    -   Menampilkan daftar buku dari database lokal.
    -   Menampilkan halaman detail untuk setiap buku (termasuk judul, penulis, genre, deskripsi, sampul).
-   **Sistem Ulasan:**
    -   Pengguna yang sudah login dapat menambahkan ulasan (dengan editor Trix) dan peringkat (bintang) pada halaman detail buku.
    -   Menampilkan daftar ulasan dari pengguna lain di halaman detail buku (konten disanitasi).
-   **Pencarian:**
    -   Mencari buku di database lokal berdasarkan judul atau penulis.
    -   Filter pencarian berdasarkan genre.
-   **Integrasi Google Books API:**
    -   Mencari buku melalui Google Books API dari halaman pencarian.
    -   Menampilkan hasil pencarian dari Google Books (termasuk sampul, judul, penulis).
    -   Menampilkan halaman detail untuk buku yang ditemukan via Google Books.
    -   Tombol untuk mengimpor buku dari Google Books ke database lokal (logika backend import masih _placeholder_ dan memerlukan implementasi penuh).
-   **Koleksi Pengguna ("My Books"):**
    -   Halaman khusus (`/my-books`) untuk pengguna yang login menampilkan daftar buku (saat ini menampilkan semua buku, perlu implementasi logika koleksi spesifik pengguna).
    -   Tombol aksi (placeholder) untuk menandai status baca dan mengelola koleksi.
-   **Halaman Beranda Berbeda:**
    -   Halaman sambutan (`/`) untuk pengguna tamu (guest).
    -   Halaman beranda terpisah (`/user-home`) untuk pengguna yang sudah login (diarahkan secara otomatis dari `/`).

---

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
