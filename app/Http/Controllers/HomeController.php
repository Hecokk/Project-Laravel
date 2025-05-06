<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
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
     * Menampilkan halaman utama.
     */
    public function index(): View
    {
        // Ambil buku dari database lokal
        $localBooks = Book::with('author')->latest()->take(6)->get();

        // Ambil buku trending dari Google Books API
        $trendingBooks = $this->googleBooksService->getTrendingBooks(6, 'fiction');

        // Pengecekan apakah data trending kosong, jika ya gunakan data lokal
        if (empty($trendingBooks)) {
            $trendingBooks = $localBooks;
        }

        // Kirim data ke view welcome
        return view('welcome', compact('trendingBooks', 'localBooks'));
    }

    /**
     * Show the user-specific home page (for logged-in users).
     *
     * @return View
     */
    public function userHome(): View
    {
        $user = Auth::user();

        // Data untuk bagian (contoh, perlu disesuaikan & dioptimalkan)

        // 1. User Stats (Placeholder for now)
        $userStats = [
            'read' => 0, // TODO: Hitung dari status baca
            'reading' => 0, // TODO: Hitung dari status baca
            'want_to_read' => 0, // TODO: Hitung dari status baca
            'reviews' => $user->reviews()->count(), // Jumlah ulasan user
        ];

        // 2. Trending/Recent Books (Ambil 6 buku terbaru dari DB lokal)
        $trendingBooks = Book::with('author')->latest()->limit(6)->get();

        // 3. Popular Reviews (Ambil 4 ulasan terbaru dengan relasi dan count)
        $popularReviews = Review::with(['user', 'book'])
            ->withCount(['likers', 'comments'])
            ->latest()
            ->limit(4)
            ->get();

        // 4. Recently Added Books (Ambil 6 buku terbaru)
        // Jika sama dengan trending, bisa diganti query lain (misal, random)
        $recentlyAddedBooks = $trendingBooks; // Sementara pakai data yang sama


        return view('user-home', compact(
            'user',
            'userStats',
            'trendingBooks',
            'popularReviews',
            'recentlyAddedBooks'
        ));
    }
}