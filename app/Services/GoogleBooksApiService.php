<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Service class for interacting with the Google Books API.
 * Handles searching for books, retrieving details, and caching results.
 */
class GoogleBooksApiService
{
    /**
     * The API key for Google Books API.
     * @var string|null
     */
    protected $apiKey;

    /**
     * The base URL for the Google Books API.
     * @var string
     */
    protected $baseUrl = 'https://www.googleapis.com/books/v1';

    /**
     * Constructor to initialize the API key from config.
     */
    public function __construct()
    {
        $this->apiKey = config('services.google_books.key');
        if (!$this->apiKey) {
            Log::warning('Google Books API key is not configured in services.google_books.key');
        }
    }

    /**
     * Search books on Google Books API based on a query string.
     * Results are cached for 24 hours.
     *
     * @param string $query The search query.
     * @param array $params Additional parameters for the API request (e.g., maxResults, startIndex, orderBy).
     * @return array An array of book items found, or an empty array on failure.
     */
    public function searchBooks(string $query, array $params = []): array
    {
        // Default parameters for search
        $defaultParams = [
            'maxResults' => 12,         // Default number of results per page
            'startIndex' => 0,          // Default starting index for results
            'orderBy' => 'relevance',   // Default sort order
            'langRestrict' => null      // Optional: Restrict results to a specific language (e.g., 'en')
        ];

        $params = array_merge($defaultParams, $params);
        // Generate a unique cache key based on the query and parameters
        $cacheKey = 'google_books_search_' . md5($query . json_encode($params));
        $cacheDuration = now()->addHours(24); // Cache results for 24 hours

        // Attempt to retrieve from cache or fetch from API
        return Cache::remember($cacheKey, $cacheDuration, function () use ($query, $params) {
            if (!$this->apiKey) {
                Log::error('Cannot search Google Books: API key is missing.');
                return []; // Return empty if no API key
            }
            try {
                // Make the HTTP GET request to Google Books API
                $response = Http::get("{$this->baseUrl}/volumes", [
                    'q' => $query,
                    'key' => $this->apiKey,
                    'maxResults' => $params['maxResults'],
                    'startIndex' => $params['startIndex'],
                    'orderBy' => $params['orderBy'],
                    'langRestrict' => $params['langRestrict'],
                ]);

                // Check if the request was successful
                if ($response->successful()) {
                    $data = $response->json();
                    // Return the 'items' array which contains the books, or empty array if no items
                    return $data['items'] ?? [];
                }

                // Log error if the request failed
                Log::error('Google Books API Error on search: ' . $response->status() . ' - ' . $response->body(), [
                    'query' => $query,
                    'params' => $params
                ]);
                return []; // Return empty array on API error
            } catch (\Exception $e) {
                // Log any other exceptions during the API call
                Log::error('Google Books API Exception on search: ' . $e->getMessage(), [
                    'query' => $query,
                    'params' => $params
                ]);
                return []; // Return empty array on exception
            }
        });
    }

    /**
     * Get detailed information for a specific book by its Google Books Volume ID.
     * Results are cached for 7 days.
     *
     * @param string $id The Google Books Volume ID.
     * @return array|null Book details as an array, or null if not found or on error.
     * @throws \Exception If the book cannot be found or an API error occurs.
     */
    public function getBookDetails(string $id): ?array
    {
        // Generate a unique cache key for the book ID
        $cacheKey = 'google_books_details_' . $id; // Changed prefix for clarity
        $cacheDuration = now()->addDays(7); // Cache details for 7 days

        // Attempt to retrieve from cache or fetch from API
        return Cache::remember($cacheKey, $cacheDuration, function () use ($id) {
            if (!$this->apiKey) {
                Log::error('Cannot get Google Books details: API key is missing.');
                throw new \Exception('Google Books API key is not configured.');
            }
            try {
                // Make the HTTP GET request to get volume details
                $response = Http::get("{$this->baseUrl}/volumes/{$id}", [
                    'key' => $this->apiKey,
                ]);

                // Check if the request was successful
                if ($response->successful()) {
                    return $response->json(); // Return the full book details
                }

                // Log error if the request failed
                Log::error('Google Books API Error on getDetails: ' . $response->status() . ' - ' . $response->body(), ['id' => $id]);

                // Throw an exception if the book is not found or another error occurred
                if ($response->status() == 404) {
                    throw new \Exception('Book not found on Google Books with ID: ' . $id);
                }
                throw new \Exception('Failed to retrieve book details from Google Books API for ID: ' . $id);
            } catch (\Exception $e) {
                // Log any other exceptions during the API call
                Log::error('Google Books API Exception on getDetails: ' . $e->getMessage(), ['id' => $id]);
                // Re-throw the exception to be handled by the caller
                throw $e;
            }
        });
    }

    /**
     * Get random popular books using predefined search queries.
     * Results are cached for 12 hours.
     *
     * @param int $count Number of books to return.
     * @return array An array of book items, or an empty array on failure.
     */
    public function getRandomBooks(int $count = 12): array
    {
        // Predefined list of queries for popular/interesting books
        $randomSearches = [
            'subject:fiction bestseller',
            'subject:fantasy popular',
            'subject:science fiction top',
            'subject:romance bestseller',
            'subject:mystery thriller',
            'subject:history popular',
            'subject:biography bestseller',
            'subject:poetry award',
            'inauthor:rowling',
            'inauthor:stephen king',
            'subject:adventure',
            'subject:politics current'
        ];

        // Select a random query from the list
        $randomQuery = $randomSearches[array_rand($randomSearches)];
        $cacheKey = 'google_books_random_' . md5($randomQuery . $count);
        $cacheDuration = now()->addHours(12); // Cache random results for 12 hours

        // Use the searchBooks method with the random query and cache
        // Note: This reuses the caching logic from searchBooks, which might lead to double caching.
        // Consider refactoring if this becomes an issue.
        return $this->searchBooks($randomQuery, ['maxResults' => $count, 'orderBy' => 'relevance']);

        /* // Original implementation (kept for reference, now using searchBooks)
        return Cache::remember($cacheKey, $cacheDuration, function () use ($randomQuery, $count) {
            if (!$this->apiKey) {
                Log::error('Cannot get random Google Books: API key is missing.');
                return [];
            }
            try {
                $response = Http::get("{$this->baseUrl}/volumes", [
                    'q' => $randomQuery,
                    'key' => $this->apiKey,
                    'maxResults' => $count,
                    'orderBy' => 'relevance',
                    'printType' => 'books', // Ensure only books are returned
                    // 'filter' => 'ebooks' // Optional: Filter for ebooks only
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['items'] ?? [];
                }

                Log::error('Google Books API Error on getRandom: ' . $response->status() . ' - ' . $response->body(), ['query' => $randomQuery]);
                return [];
            } catch (\Exception $e) {
                Log::error('Google Books API Exception on getRandom: ' . $e->getMessage(), ['query' => $randomQuery]);
                return [];
            }
        });
        */
    }

    /**
     * Helper function to format authors array into a comma-separated string.
     *
     * @param array|null $authors Array of author names.
     * @return string Formatted author string or 'Unknown Author'.
     */
    protected function formatAuthors(?array $authors): string
    {
        if (empty($authors)) {
            return 'Unknown Author';
        }
        return implode(', ', $authors);
    }

    /**
     * Helper function to extract ISBN-13 or ISBN-10 from industry identifiers.
     * Prefers ISBN-13.
     *
     * @param array|null $identifiers Array of industry identifiers.
     * @return string|null The ISBN number or null if not found.
     */
    protected function getIsbn(?array $identifiers): ?string
    {
        if (empty($identifiers)) {
            return null;
        }

        // Prioritize ISBN-13
        foreach ($identifiers as $identifier) {
            if (isset($identifier['type']) && $identifier['type'] === 'ISBN_13') {
                return $identifier['identifier'] ?? null;
            }
        }

        // Fallback to ISBN-10
        foreach ($identifiers as $identifier) {
            if (isset($identifier['type']) && $identifier['type'] === 'ISBN_10') {
                return $identifier['identifier'] ?? null;
            }
        }

        return null;
    }

    /**
     * Helper function to get the best available thumbnail image URL.
     * Prefers larger images and ensures HTTPS.
     *
     * @param array|null $imageLinks Array of image links from API response.
     * @return string|null The image URL or null if not available.
     */
    protected function getThumbnail(?array $imageLinks): ?string
    {
        if (empty($imageLinks)) {
            return null;
        }

        // Define image size preference order (largest to smallest)
        $options = ['extraLarge', 'large', 'medium', 'small', 'thumbnail', 'smallThumbnail'];

        foreach ($options as $option) {
            if (isset($imageLinks[$option])) {
                // Ensure URL uses HTTPS
                return str_replace('http://', 'https://', $imageLinks[$option]);
            }
        }

        return null; // No image link found
    }

    /**
     * Import a book from Google Books to local database.
     * Placeholder - Implementation needs to be added.
     *
     * @param string $googleBooksId The Google Books Volume ID to import.
     * @return \App\Models\Book|null The created Book model or null on failure.
     */
    public function importBook(string $googleBooksId)
    {
        // TODO: Implement the logic to fetch book details using getBookDetails()
        // TODO: Find or create Author and Genre models based on API data.
        // TODO: Create and save the new Book record, handling potential duplicates.
        // TODO: Consider downloading the cover image locally.
        Log::info('Placeholder: importBook called for ID: ' . $googleBooksId);
        return null;
    }

    /**
     * Parses the table of contents string (if available) into an array of lines.
     * Basic implementation, might need refinement based on actual ToC formats.
     *
     * @param string|null $tableOfContents Raw table of contents string.
     * @return array|null Array of ToC lines or null.
     */
    protected function parseTableOfContents(?string $tableOfContents): ?array
    {
        if (empty($tableOfContents)) {
            return null;
        }

        // Split the string by newline characters
        $lines = preg_split('/\r\n|\r|\n/', $tableOfContents);
        // Remove empty lines
        return array_filter($lines, fn($line) => !empty(trim($line)));
    }
}
