<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi (fillable) melalui mass assignment
     */
    protected $fillable = [
        'title',
        'author_id',
        'genre_id',
        'description',
        'cover_image_path',
        'publication_year',
        'google_books_id',
        'page_count',
        'language'
    ];

    /**
     * Mendapatkan author yang menulis buku ini.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Mendapatkan genre buku ini.
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * Mendapatkan semua ulasan untuk buku ini.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
