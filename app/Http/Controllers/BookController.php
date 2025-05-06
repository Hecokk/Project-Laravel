<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\GoogleBooksApiService;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Instance of the GoogleBooksApiService.
     * @var GoogleBooksApiService
     */
    protected $googleBooksApiService;

    /**
     * Constructor to inject GoogleBooksApiService dependency.
     *
     * @param GoogleBooksApiService $googleBooksApiService
     */
    public function __construct(GoogleBooksApiService $googleBooksApiService)
    {
        $this->googleBooksApiService = $googleBooksApiService;
    }

    /**
     * Display a listing of random trending books from Google Books API.
     *
     * @return View
     */
    public function index(): View
    {
        // Ambil buku acak/trending dari Google Books Service, prioritas Bahasa Indonesia
        $googleBooks = $this->googleBooksApiService->getRandomBooks(18, ['langRestrict' => 'id']); // Tambahkan langRestrict

        // Kirim data Google Books ke view yang sama (books.index)
        // View perlu disesuaikan untuk menangani format data Google Books
        return view('books.index', ['googleBooks' => $googleBooks]);
    }

    /**
     * Search for books by title, author, or genre in the local database and optionally Google Books.
     *
     * @param Request $request The HTTP request containing search parameters.
     * @return View The search results view.
     */
    public function search(Request $request): View
    {
        $query = $request->input('query');
        $genre_id = $request->input('genre_id');
        $include_google = $request->boolean('include_google', true);

        $booksQuery = Book::with(['author', 'genre']);

        if (!empty($query)) {
            $booksQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhereHas('author', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    });
            });
        }

        if (!empty($genre_id)) {
            $booksQuery->where('genre_id', $genre_id);
        }

        $books = $booksQuery->latest()->paginate(12)->withQueryString();

        $genres = Genre::orderBy('name')->get();

        $googleBooks = [];

        if ($include_google) {
            if (!empty($query)) {
                $googleBooks = $this->googleBooksApiService->searchBooks($query, ['maxResults' => 12]);
            } elseif ($books->isEmpty()) {
                $googleBooks = $this->googleBooksApiService->getRandomBooks(12);
            }
        }

        return view('books.search', compact('books', 'genres', 'query', 'genre_id', 'googleBooks', 'include_google'));
    }

    /**
     * Show the form for creating a new book (Placeholder).
     * @return void
     */
    public function create()
    {
        abort(501, 'Not Implemented');
    }

    /**
     * Store a newly created book in storage (Placeholder).
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        abort(501, 'Not Implemented');
    }

    /**
     * Display the specified book from the local database.
     *
     * @param Book $book The Book model instance (route model binding).
     * @return View
     */
    public function show(Book $book): View
    {
        $book->load(['author', 'genre', 'reviews.user']);

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book (Placeholder).
     * @param Book $book
     * @return void
     */
    public function edit(Book $book)
    {
        abort(501, 'Not Implemented');
    }

    /**
     * Update the specified book in storage (Placeholder).
     * @param Request $request
     * @param Book $book
     * @return void
     */
    public function update(Request $request, Book $book)
    {
        abort(501, 'Not Implemented');
    }

    /**
     * Remove the specified book from storage (Placeholder).
     * @param Book $book
     * @return void
     */
    public function destroy(Book $book)
    {
        abort(501, 'Not Implemented');
    }

    /**
     * Display the books associated with the logged-in user (Placeholder/Example).
     *
     * @return View
     */
    public function myBooks(): View
    {
        $user = Auth::user();

        $books = Book::with(['author', 'genre'])
            ->latest()
            ->paginate(12);

        return view('books.my-books', compact('books', 'user'));
    }

    /**
     * (DEPRECATED/Internal? Might not be used directly via route anymore)
     * Search Google Books API.
     *
     * @param Request $request
     * @return View
     */
    public function searchGoogleBooks(Request $request): View
    {
        $query = $request->input('q', '');
        $books = [];

        if (!empty($query)) {
            $books = $this->googleBooksApiService->searchBooks($query);
        }

        return view('books.search', compact('books', 'query'));
    }

    /**
     * (DEPRECATED/Internal? Might not be used directly via route anymore)
     * Show details for a specific book from Google Books API.
     *
     * @param string $id Google Books Volume ID.
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function showGoogleBooks($id)
    {
        try {
            $book = $this->googleBooksApiService->getBookDetails($id);
            return view('books.show', compact('book'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Book details not found: ' . $e->getMessage());
        }
    }

    /**
     * Handle the import of a book from Google Books API to the local database.
     *
     * @param string $googleBookId The Google Books Volume ID.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importGoogleBook(string $googleBookId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to import books.');
        }

        try {
            $importedBook = $this->googleBooksApiService->importBook($googleBookId);

            if ($importedBook) {
                return redirect()->route('books.show', $importedBook)->with('success', 'Book imported successfully!');
            } else {
                return back()->with('error', 'Failed to import book. It might already exist or an error occurred.');
            }
        } catch (\Exception $e) {
            Log::error('Error importing Google Book ID ' . $googleBookId . ': ' . $e->getMessage());
            return back()->with('error', 'An error occurred while importing the book.');
        }
    }
}
