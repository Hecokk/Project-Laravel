<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Search categories to get popular books
        $categories = ['fiction', 'fantasy', 'science', 'history', 'romance'];
        $books = [];

        foreach ($categories as $category) {
            // Fetch popular books from Google Books API based on category
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => "subject:{$category}",
                'maxResults' => 5,
                'orderBy' => 'relevance',
                'langRestrict' => 'en'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['items']) && is_array($data['items'])) {
                    foreach ($data['items'] as $item) {
                        if (isset($item['volumeInfo'])) {
                            $volumeInfo = $item['volumeInfo'];

                            // Make sure all required data is available
                            if (isset($volumeInfo['title'])) {
                                // Assign random author_id and genre_id
                                // (Assuming you have authors and genres tables with data)
                                $author_id = rand(1, 10); // Adjust according to the number of authors
                                $genre_id = rand(1, 5);   // Adjust according to the number of genres

                                // Get thumbnail that can be accessed by the application
                                $thumbnail = null;
                                if (isset($volumeInfo['imageLinks']['thumbnail'])) {
                                    // Replace http with https for security
                                    $thumbnail = str_replace('http://', 'https://', $volumeInfo['imageLinks']['thumbnail']);
                                    // Replace zoom=1 with zoom=0 to get image without zoom effect
                                    $thumbnail = str_replace('&zoom=1', '&zoom=0', $thumbnail);
                                }

                                $books[] = [
                                    'title' => $volumeInfo['title'],
                                    'author_id' => $author_id,
                                    'genre_id' => $genre_id,
                                    'description' => $volumeInfo['description'] ?? 'No description available',
                                    'cover_image_path' => $thumbnail ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover',
                                    'publication_year' => isset($volumeInfo['publishedDate']) ? substr($volumeInfo['publishedDate'], 0, 4) : null,
                                    'google_books_id' => $item['id'] ?? null,
                                    'page_count' => $volumeInfo['pageCount'] ?? null,
                                    'language' => $volumeInfo['language'] ?? 'en',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ];
                            }
                        }
                    }
                }
            }
        }

        // If no books found from API, use default data
        if (empty($books)) {
            $books = [
                [
                    'title' => "Harry Potter and the Sorcerer's Stone",
                    'author_id' => 1,
                    'genre_id' => 1,
                    'description' => 'The first book in the Harry Potter series.',
                    'cover_image_path' => 'https://placehold.co/600x900/3d405b/FFFFFF?text=Harry+Potter',
                    'publication_year' => '1997',
                    'google_books_id' => null,
                    'page_count' => 309,
                    'language' => 'en',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'title' => 'A Game of Thrones',
                    'author_id' => 2,
                    'genre_id' => 1,
                    'description' => 'The first book in A Song of Ice and Fire.',
                    'cover_image_path' => 'https://placehold.co/600x900/3d405b/FFFFFF?text=Game+of+Thrones',
                    'publication_year' => '1996',
                    'google_books_id' => null,
                    'page_count' => 694,
                    'language' => 'en',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'title' => 'The Hobbit',
                    'author_id' => 3,
                    'genre_id' => 1,
                    'description' => 'A prelude to The Lord of the Rings.',
                    'cover_image_path' => 'https://placehold.co/600x900/3d405b/FFFFFF?text=The+Hobbit',
                    'publication_year' => '1937',
                    'google_books_id' => null,
                    'page_count' => 310,
                    'language' => 'en',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'title' => 'Bumi',
                    'author_id' => 4,
                    'genre_id' => 2,
                    'description' => 'Science fiction novel by Tere Liye.',
                    'cover_image_path' => 'https://placehold.co/600x900/3d405b/FFFFFF?text=Bumi',
                    'publication_year' => '2014',
                    'google_books_id' => null,
                    'page_count' => 440,
                    'language' => 'id',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ];
        }

        // Insert book data into database
        DB::table('books')->insert($books);
    }
}