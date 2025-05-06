<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GoogleBooksService
{
    /**
     * Base URL untuk Google Books API
     */
    protected string $baseUrl;

    /**
     * API Key untuk Google Books API
     */
    protected string $apiKey;

    /**
     * Cache lifetime dalam menit
     */
    protected int $cacheLifetime = 60;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->baseUrl = config('google-books.base_url', 'https://www.googleapis.com/books/v1');
        $this->apiKey = config('google-books.api_key');
    }

    /**
     * Mencari buku berdasarkan query dan opsi tambahan
     *
     * @param string $query Query pencarian
     * @param array $options Opsi tambahan (maxResults, startIndex, langRestrict, orderBy, etc)
     * @return array
     */
    public function searchBooks(string $query, array $options = []): array
    {
        // Buat cache key berdasarkan query dan opsi
        $cacheKey = 'google_books_search_' . md5($query . serialize($options));

        // Cek apakah data ada di cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Siapkan parameter URL
        $params = array_merge([
            'q' => $query,
            'key' => $this->apiKey,
            'maxResults' => $options['maxResults'] ?? 10,
        ], $options);

        // Kirim request ke Google Books API
        $response = Http::get("{$this->baseUrl}/volumes", $params);

        // Jika response berhasil
        if ($response->successful()) {
            $data = $response->json();
            // Simpan hasil ke cache
            Cache::put($cacheKey, $data, now()->addMinutes($this->cacheLifetime));
            return $data;
        }

        // Return array kosong jika gagal
        return ['items' => [], 'totalItems' => 0];
    }

    /**
     * Mendapatkan detail buku berdasarkan ID
     *
     * @param string $id Google Books ID
     * @return array|null
     */
    public function getBookDetails(string $id): ?array
    {
        // Buat cache key berdasarkan ID
        $cacheKey = 'google_books_details_' . $id;

        // Cek apakah data ada di cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Kirim request ke Google Books API
        $response = Http::get("{$this->baseUrl}/volumes/{$id}", [
            'key' => $this->apiKey
        ]);

        // Jika response berhasil
        if ($response->successful()) {
            $data = $response->json();
            // Simpan hasil ke cache
            Cache::put($cacheKey, $data, now()->addMinutes($this->cacheLifetime));
            return $data;
        }

        // Return null jika gagal
        return null;
    }

    /**
     * Konversi data dari Google Books API ke format yang digunakan aplikasi
     *
     * @param array $googleBook Data dari Google Books API
     * @return array Data dalam format yang digunakan aplikasi
     */
    public function convertToAppFormat(array $googleBook): array
    {
        $volumeInfo = $googleBook['volumeInfo'] ?? [];

        return [
            'google_books_id' => $googleBook['id'] ?? null,
            'title' => $volumeInfo['title'] ?? 'Untitled',
            'author_name' => isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : 'Unknown',
            'description' => $volumeInfo['description'] ?? null,
            'publisher' => $volumeInfo['publisher'] ?? null,
            'published_date' => $volumeInfo['publishedDate'] ?? null,
            'page_count' => $volumeInfo['pageCount'] ?? null,
            'categories' => $volumeInfo['categories'] ?? [],
            'average_rating' => $volumeInfo['averageRating'] ?? null,
            'ratings_count' => $volumeInfo['ratingsCount'] ?? 0,
            'thumbnail' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
            'language' => $volumeInfo['language'] ?? null,
            'preview_link' => $volumeInfo['previewLink'] ?? null,
            'info_link' => $volumeInfo['infoLink'] ?? null,
        ];
    }

    /**
     * Mendapatkan buku-buku trending dari Google Books API
     * 
     * @param int $count Jumlah buku yang ingin diambil
     * @param string $subject Subject/kategori buku (opsional)
     * @return array
     */
    public function getTrendingBooks(int $count = 6, string $subject = ''): array
    {
        // Buat cache key
        $cacheKey = 'google_books_trending_' . md5($count . $subject);

        // Cek apakah data ada di cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Query pencarian
        $queryParts = [];
        if (!empty($subject)) {
            $queryParts[] = 'subject:' . $subject;
        }

        // Tambahkan filter untuk buku yang populer (dengan rating)
        $query = !empty($queryParts) ? implode('+', $queryParts) : 'subject:fiction';

        // Siapkan parameter 
        $params = [
            'q' => $query,
            'orderBy' => 'relevance', // Bisa juga 'newest'
            'maxResults' => $count,
            'key' => $this->apiKey,
            'printType' => 'books',
            'filter' => 'ebooks', // Hanya buku yang memiliki ebook (biasanya memiliki cover)
        ];

        // Kirim request ke Google Books API
        $response = Http::get("{$this->baseUrl}/volumes", $params);

        // Jika response berhasil
        if ($response->successful()) {
            $data = $response->json();

            // Format hasil ke format yang dibutuhkan
            $books = [];
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $books[] = $this->convertToAppFormat($item);
                }
            }

            // Simpan hasil ke cache (1 hari)
            Cache::put($cacheKey, $books, now()->addDay());
            return $books;
        }

        // Return array kosong jika gagal
        return [];
    }
}
