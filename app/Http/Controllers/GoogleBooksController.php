<?php

namespace App\Http\Controllers;

use App\Jobs\DownloadBookCoverImage;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoogleBooksController extends Controller
{
    /**
     * Service untuk Google Books API
     */
    protected GoogleBooksService $googleBooksService;

    /**
     * Constructor
     */
    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    /**
     * Menampilkan halaman pencarian Google Books
     */
    public function search(Request $request): View
    {
        $query = $request->input('query');
        $books = [];
        $totalItems = 0;

        if ($query) {
            // Opsi tambahan dari request
            $options = [
                'maxResults' => $request->input('max_results', 10),
                'startIndex' => $request->input('start_index', 0),
                'langRestrict' => $request->input('lang', null),
                'orderBy' => $request->input('order_by', 'relevance'),
            ];

            // Filter opsi kosong
            $options = array_filter($options);

            // Panggil service untuk mencari buku
            $results = $this->googleBooksService->searchBooks($query, $options);

            // Ambil data buku
            $books = $results['items'] ?? [];
            $totalItems = $results['totalItems'] ?? 0;
        }

        return view('books.google-search', [
            'query' => $query,
            'books' => $books,
            'totalItems' => $totalItems,
        ]);
    }

    /**
     * Menampilkan detail buku dari Google Books
     */
    public function show(string $id): View
    {
        // Panggil service untuk mendapatkan detail buku
        $book = $this->googleBooksService->getBookDetails($id);

        // Jika buku tidak ditemukan, tampilkan halaman 404
        if (!$book) {
            abort(404, 'Buku tidak ditemukan');
        }

        return view('books.google-detail', [
            'book' => $book,
            'formattedBook' => $this->googleBooksService->convertToAppFormat($book),
        ]);
    }

    /**
     * Mengimpor buku dari Google Books ke database lokal
     */
    public function import(Request $request, string $id)
    {
        // Validasi request
        $request->validate([
            'status' => 'nullable|in:want_to_read,reading,read',
        ]);

        // Panggil service untuk mendapatkan detail buku
        $googleBook = $this->googleBooksService->getBookDetails($id);

        // Jika buku tidak ditemukan, tampilkan halaman 404
        if (!$googleBook) {
            abort(404, 'Buku tidak ditemukan');
        }

        // Konversi data ke format aplikasi
        $bookData = $this->googleBooksService->convertToAppFormat($googleBook);

        // Cari atau buat author
        $authorName = $bookData['author_name'] ?? 'Unknown';
        $author = \App\Models\Author::firstOrCreate(['name' => $authorName]);

        // Cari atau buat genre berdasarkan kategori pertama
        $genreName = $bookData['categories'][0] ?? 'Uncategorized';
        $genre = \App\Models\Genre::firstOrCreate(
            ['name' => $genreName],
            ['slug' => \Illuminate\Support\Str::slug($genreName)]
        );

        // Cek apakah buku sudah ada berdasarkan google_books_id
        $book = \App\Models\Book::where('google_books_id', $bookData['google_books_id'])->first();

        if (!$book) {
            // Buat buku baru jika belum ada
            $book = new \App\Models\Book();
            $book->google_books_id = $bookData['google_books_id'];
            $book->title = $bookData['title'];
            $book->author_id = $author->id;
            $book->genre_id = $genre->id;
            $book->description = $bookData['description'];

            // Simpan URL thumbnail sementara, akan diupdate oleh job
            $book->cover_image_path = $bookData['thumbnail'];

            $book->publication_year = substr($bookData['published_date'] ?? '', 0, 4) ?: null;
            $book->page_count = $bookData['page_count'];
            $book->language = $bookData['language'];
            $book->save();

            // Dispatch job untuk mengunduh gambar sampul
            if (!empty($bookData['thumbnail'])) {
                DownloadBookCoverImage::dispatch($book->id, $bookData['thumbnail']);
            }
        }

        // TODO: Jika implementasi user's reading status sudah dibuat,
        // tambahkan logika untuk menyimpan status buku user di sini
        // contoh: $user->books()->attach($book->id, ['status' => $request->input('status')]);

        // Redirect dengan pesan sukses
        return redirect()->route('books.my-books')
            ->with('success', 'Buku berhasil diimpor ke koleksi Anda.');
    }
}
