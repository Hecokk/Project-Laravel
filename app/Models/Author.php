<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi (fillable) melalui mass assignment
     */
    protected $fillable = ['name', 'bio'];

    /**
     * Mendapatkan semua buku yang ditulis oleh author ini.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
