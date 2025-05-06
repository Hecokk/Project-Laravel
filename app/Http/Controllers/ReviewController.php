<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // TODO: Ambil data ulasan dinamis dari database
        // $reviews = Review::with(['book', 'user'])->latest()->paginate(10);

        // Untuk sekarang, tampilkan view dengan data placeholder
        return view('reviews.index'); //, compact('reviews'));
        // abort(501); // Not Implemented
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     // Biasanya form ada di halaman detail buku
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        // Validasi request
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'min:10'], // Tambahkan validasi panjang minimum jika perlu
        ]);

        // Simpan ulasan ke database menggunakan relasi
        $book->reviews()->create([
            'user_id' => Auth::id(), // ID user yang sedang login
            'rating' => $validated['rating'],
            'content' => $validated['content'], // Simpan konten asli (sebelum sanitasi untuk tampilan)
        ]);

        // Redirect kembali ke halaman detail buku dengan pesan sukses
        return redirect()->route('books.show', $book)->with('success', 'Ulasan berhasil ditambahkan!');
        // abort(501); // Not Implemented
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     // Mungkin tidak perlu, ulasan ditampilkan di detail buku
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     // TODO: Implementasi jika perlu edit ulasan
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     // TODO: Implementasi jika perlu update ulasan
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     // TODO: Implementasi jika perlu hapus ulasan
    // }
}
