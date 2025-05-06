<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ReviewCommentController extends Controller
{
    /**
     * Store a new comment for a review.
     *
     * @param Request $request
     * @param Review $review
     * @return RedirectResponse
     */
    public function store(Request $request, Review $review): RedirectResponse
    {
        // Validate the request data
        $validated = $request->validate([
            'content' => 'required|string|max:2000', // Sesuaikan max length jika perlu
        ]);

        // Create the comment using the relationship
        $review->comments()->create([
            'user_id' => Auth::id(),
            'content' => clean($validated['content']) // Sanitasi input komentar
        ]);

        // Redirect back to the page where the comment was submitted
        return back()->with('success', 'Comment added successfully!');
        // TODO: Pertimbangkan respons JSON jika menggunakan AJAX/JavaScript
    }

    // Bisa tambahkan method lain seperti update(), destroy() jika diperlukan
}
