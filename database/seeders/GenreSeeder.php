<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Fantasy'],
            ['name' => 'Science Fiction'],
            ['name' => 'Mystery'],
            ['name' => 'Thriller'],
            ['name' => 'Romance'],
            ['name' => 'Historical Fiction'],
        ];

        // Add slug and timestamps to each genre
        $dataToInsert = array_map(function ($genre) {
            return [
                'name' => $genre['name'],
                'slug' => Str::slug($genre['name']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $genres);

        DB::table('genres')->insert($dataToInsert);
    }
}
