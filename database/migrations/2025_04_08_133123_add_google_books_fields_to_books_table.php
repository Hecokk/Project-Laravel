<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('google_books_id')->nullable()->after('id')->index();
            $table->integer('page_count')->nullable()->after('publication_year');
            $table->string('language', 10)->nullable()->after('page_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['google_books_id', 'page_count', 'language']);
        });
    }
};
