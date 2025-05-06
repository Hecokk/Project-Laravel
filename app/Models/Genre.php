<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi (fillable) melalui mass assignment
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Mendapatkan semua buku dalam genre ini.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
