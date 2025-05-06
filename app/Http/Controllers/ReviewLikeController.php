<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ReviewLikeController extends Controller
{
    /**
     * Toggle the like status for a review.
     *
     * @param Review $review
     * @return RedirectResponse
     */
    public function toggleLike(Review $review): RedirectResponse
    {
        $user = Auth::user();

        // Gunakan toggle() pada relasi BelongsToMany untuk menambah/menghapus record di tabel pivot
        $user->likedReviews()->toggle($review->id);

        // Redirect kembali ke halaman sebelumnya (misalnya halaman detail buku atau user home)
        return back();
        // TODO: Pertimbangkan respons JSON jika menggunakan AJAX/JavaScript
    }
}
