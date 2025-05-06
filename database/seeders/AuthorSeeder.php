<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('authors')->insert([
            ['name' => 'J.K. Rowling', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'George R.R. Martin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'J.R.R. Tolkien', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tere Liye', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}