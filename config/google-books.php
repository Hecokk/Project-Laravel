<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Books API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk Google Books API.
    |
    */

    // API Key dari Google Cloud Console
    'api_key' => env('GOOGLE_BOOKS_API_KEY', ''),

    // Base URL untuk Google Books API
    'base_url' => 'https://www.googleapis.com/books/v1',

    // Cache lifetime dalam menit
    'cache_lifetime' => 60,
];
