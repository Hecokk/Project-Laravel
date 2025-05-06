<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GoogleBooksController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\ReviewCommentController;

// Home route
Route::get('/', [HomeController::class, 'index'])
    // ->middleware('guest.welcome') // Menggunakan nama kelas langsung untuk debug
    ->middleware(\App\Http\Middleware\RedirectAuthenticatedUserFromWelcome::class)
    ->name('home');

// Dashboard (Redirect to user.home)
Route::get('/dashboard', function () {
    // return view('dashboard'); // Komentari view lama
    return redirect()->route('user.home'); // Redirect ke user home baru
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk resource buku
Route::resource('books', BookController::class);

// Route untuk menampilkan buku pengguna yang login
Route::get('/my-books', [BookController::class, 'myBooks'])
    ->middleware('auth')
    ->name('books.my-books');

// Route untuk pencarian buku
Route::get('/search', [BookController::class, 'search'])->name('books.search');

// Route untuk Google Books API
Route::prefix('google-books')
    ->middleware('throttle:60,1') // Batasi 60 request per menit
    ->group(function () {
        // Route::get('/search', [GoogleBooksController::class, 'search'])->name('google-books.search'); // Dinonaktifkan
        Route::get('/show/{id}', [GoogleBooksController::class, 'show'])->name('google-books.show');
        Route::post('/import/{id}', [GoogleBooksController::class, 'import'])
            ->middleware('auth')
            ->name('google-books.import');
    });

// Route untuk menampilkan semua ulasan
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

// Route untuk menyimpan ulasan baru (terkait buku)
Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');

// Route untuk Like/Unlike Ulasan
Route::post('/reviews/{review}/like', [ReviewLikeController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('reviews.like.toggle');

// Route untuk menyimpan komentar ulasan
Route::post('/reviews/{review}/comments', [ReviewCommentController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.comments.store');

// User Home Page (for logged-in users)
Route::get('/user-home', [HomeController::class, 'userHome'])
    ->middleware(['auth', 'verified']) // Memastikan user login & email terverifikasi
    ->name('user.home');

// Authentication routes
require __DIR__ . '/auth.php';

// Simple health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Service is running'
    ]);
});
